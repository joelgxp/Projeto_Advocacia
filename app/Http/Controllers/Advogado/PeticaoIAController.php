<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\TemplatePeticao;
use App\Services\PeticaoIAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PeticaoIAController extends Controller
{
    public function __construct(
        private PeticaoIAService $peticaoService
    ) {}

    /**
     * Lista peças geradas
     */
    public function index()
    {
        $processos = Processo::where('advogado_id', Auth::user()->advogado->id ?? null)
            ->withRelations()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('advogado.pecas.index', compact('processos'));
    }

    /**
     * Formulário para gerar peça com IA
     */
    public function criar(Request $request)
    {
        $processoId = $request->query('processo_id');
        $processo = null;

        if ($processoId) {
            $processo = Processo::with(['cliente', 'advogado', 'vara', 'especialidade', 'processado'])
                ->findOrFail($processoId);

            // Verificar se o advogado tem acesso
            if ($processo->advogado_id !== (Auth::user()->advogado->id ?? null)) {
                abort(403, 'Você não tem permissão para acessar este processo.');
            }
        }

        $processos = Processo::where('advogado_id', Auth::user()->advogado->id ?? null)
            ->withRelations()
            ->orderBy('numero_processo')
            ->get();

        $templates = TemplatePeticao::ativos()
            ->orderBy('categoria')
            ->orderBy('nome')
            ->get()
            ->groupBy('categoria');

        return view('advogado.pecas.criar', compact('processo', 'processos', 'templates'));
    }

    /**
     * Gera peça com IA
     */
    public function gerar(Request $request)
    {
        $validated = $request->validate([
            'processo_id' => 'required|exists:processos,id',
            'tipo_peca' => 'required|in:peticao_inicial,contestacao,replica,agravo,apelacao,embargos_declaracao,recurso_especial,manifestacao',
            'contexto' => 'nullable|string|max:5000',
            'pedidos' => 'nullable|string|max:5000',
            'argumentos' => 'nullable|string|max:5000',
            'fundamentos' => 'nullable|string|max:5000',
            'valor_causa' => 'nullable|string|max:100',
            'template_id' => 'nullable|exists:templates_peticoes,id',
            'usar_template' => 'nullable|boolean',
        ]);

        try {
            $processo = Processo::with(['cliente', 'advogado', 'vara', 'especialidade', 'processado'])
                ->findOrFail($validated['processo_id']);

            // Verificar permissão
            if ($processo->advogado_id !== (Auth::user()->advogado->id ?? null)) {
                return response()->json([
                    'sucesso' => false,
                    'erro' => 'Você não tem permissão para gerar peças deste processo.',
                ], 403);
            }

            // Gerar com template ou IA pura
            if ($validated['usar_template'] ?? false) {
                $template = TemplatePeticao::findOrFail($validated['template_id']);
                $resultado = $this->peticaoService->gerarDeTemplate($template, $processo, $validated);
            } elseif ($validated['tipo_peca'] === 'peticao_inicial') {
                $resultado = $this->peticaoService->gerarPeticaoInicial($processo, $validated);
            } else {
                $resultado = $this->peticaoService->gerarPeticaoIntermediaria(
                    $processo,
                    $validated['tipo_peca'],
                    $validated
                );
            }

            if ($resultado['sucesso']) {
                Log::info('Peça gerada com sucesso', [
                    'processo_id' => $processo->id,
                    'tipo' => $validated['tipo_peca'],
                    'user_id' => Auth::id(),
                ]);

                return response()->json($resultado);
            }

            return response()->json($resultado, 422);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar peça', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'sucesso' => false,
                'erro' => 'Erro ao gerar peça: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview de template
     */
    public function previewTemplate(Request $request, TemplatePeticao $template)
    {
        $processoId = $request->query('processo_id');
        
        if ($processoId) {
            $processo = Processo::with(['cliente', 'advogado', 'vara', 'especialidade'])
                ->findOrFail($processoId);

            // Substituir variáveis básicas para preview
            $conteudo = $this->substituirVariaveisPreview($template->conteudo, $processo);
        } else {
            $conteudo = $template->conteudo;
        }

        return response()->json([
            'sucesso' => true,
            'conteudo' => $conteudo,
            'variaveis' => $template->variaveis,
        ]);
    }

    /**
     * Substitui variáveis para preview
     */
    private function substituirVariaveisPreview(string $conteudo, Processo $processo): string
    {
        $substituicoes = [
            '{{numero_processo}}' => $processo->numero_processo,
            '{{cliente_nome}}' => $processo->cliente->nome ?? '[NOME DO CLIENTE]',
            '{{cliente_cpf}}' => $processo->cliente->cpf_cnpj ?? '[CPF/CNPJ]',
            '{{advogado_nome}}' => $processo->advogado->nome ?? '[NOME DO ADVOGADO]',
            '{{advogado_oab}}' => $processo->advogado->oab ?? '[OAB]',
            '{{vara_nome}}' => $processo->vara->nome ?? '[VARA]',
            '{{data_hoje}}' => now()->format('d/m/Y'),
        ];

        return str_replace(array_keys($substituicoes), array_values($substituicoes), $conteudo);
    }

    /**
     * Salva peça gerada
     */
    public function salvar(Request $request)
    {
        $validated = $request->validate([
            'processo_id' => 'required|exists:processos,id',
            'tipo_peca' => 'required|string|max:100',
            'conteudo' => 'required|string',
            'titulo' => 'required|string|max:255',
        ]);

        try {
            $processo = Processo::findOrFail($validated['processo_id']);

            // Verificar permissão
            if ($processo->advogado_id !== (Auth::user()->advogado->id ?? null)) {
                return response()->json([
                    'sucesso' => false,
                    'erro' => 'Você não tem permissão para salvar peças deste processo.',
                ], 403);
            }

            // Salvar como documento
            $documento = $processo->documentos()->create([
                'titulo' => $validated['titulo'],
                'tipo' => 'peticao',
                'conteudo' => $validated['conteudo'],
                'user_id' => Auth::id(),
                'data_upload' => now(),
            ]);

            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Peça salva com sucesso!',
                'documento_id' => $documento->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao salvar peça', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'sucesso' => false,
                'erro' => 'Erro ao salvar peça: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exporta peça para PDF ou DOCX
     */
    public function exportar(Request $request)
    {
        $validated = $request->validate([
            'conteudo' => 'required|string',
            'titulo' => 'required|string|max:255',
            'formato' => 'required|in:pdf,docx',
        ]);

        try {
            // TODO: Implementar exportação para PDF/DOCX
            // Por enquanto, retorna o conteúdo para download como TXT
            
            $filename = \Str::slug($validated['titulo']) . '.txt';
            
            return response()->streamDownload(function () use ($validated) {
                echo $validated['conteudo'];
            }, $filename, [
                'Content-Type' => 'text/plain',
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao exportar peça', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'sucesso' => false,
                'erro' => 'Erro ao exportar peça: ' . $e->getMessage(),
            ], 500);
        }
    }
}
