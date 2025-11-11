<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ConsultaProcessualService;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultaProcessualController extends Controller
{
    public function __construct(
        private ConsultaProcessualService $consultaService
    ) {}

    public function index()
    {
        $tribunaisPorSegmento = $this->consultaService->getTribunais();
        $tribunaisListaPlana = $this->consultaService->getTribunaisListaPlana();
        return view('admin.consulta-processual.index', compact('tribunaisPorSegmento', 'tribunaisListaPlana'));
    }

    public function consultar(Request $request)
    {
        $request->validate([
            'numero_processo' => 'required|string|min:10',
            'tribunal' => 'nullable|string|size:2',
        ]);

        // Normalizar número de processo
        $numeroProcesso = \App\Helpers\ProcessoCNJHelper::normalizar($request->numero_processo);
        
        if (!$numeroProcesso) {
            return back()
                ->withInput()
                ->with('error', 'Número de processo inválido. Use o padrão CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO');
        }

        // Tribunal é opcional (será extraído automaticamente)
        $tribunal = $request->tribunal;

        $resultado = $this->consultaService->consultarProcesso($numeroProcesso, $tribunal);

        if ($request->expectsJson()) {
            return response()->json($resultado);
        }

        if ($resultado['success']) {
            return redirect()
                ->route('admin.consulta-processual.detalhes', [
                    'numero' => $numeroProcesso,
                ])
                ->with('dados_consulta', $resultado['data']);
        }

        return back()
            ->withInput()
            ->with('error', $resultado['message'] ?? 'Erro ao consultar processo.');
    }

    public function detalhes(Request $request, $numero)
    {
        // Normalizar número se necessário
        $numeroNormalizado = \App\Helpers\ProcessoCNJHelper::normalizar($numero) ?? $numero;
        
        // Extrair partes (sem validar dígito verificador)
        $partes = \App\Helpers\ProcessoCNJHelper::extrairPartes($numeroNormalizado);
        
        if (!$partes) {
            return back()->with('error', 'Número de processo inválido. Verifique o formato.');
        }

        // Tribunal será extraído automaticamente pelo service
        $resultado = $this->consultaService->consultarProcesso($numeroNormalizado);
        
        if (!$resultado['success']) {
            return back()->with('error', $resultado['message']);
        }

        $dados = $resultado['data'];
        $partes = $resultado['partes'] ?? $partes;
        
        // Verificar se processo existe no sistema
        $processo = Processo::where('numero_processo', $numeroNormalizado)
            ->orWhere('numero_processo', $partes['numero_limpo'])
            ->first();

        return view('admin.consulta-processual.detalhes', [
            'numero' => $numeroNormalizado,
            'tribunal' => $partes['tribunal'],
            'dados' => $dados,
            'partes' => $partes,
            'processo' => $processo,
            'debug' => $resultado['debug'] ?? null,
        ]);
    }

    public function historico($numero)
    {
        // Normalizar número se necessário
        $numeroNormalizado = \App\Helpers\ProcessoCNJHelper::normalizar($numero) ?? $numero;
        
        // Extrair partes (sem validar dígito verificador)
        $partes = \App\Helpers\ProcessoCNJHelper::extrairPartes($numeroNormalizado);
        
        if (!$partes) {
            return back()->with('error', 'Número de processo inválido. Verifique o formato.');
        }

        // Tribunal será extraído automaticamente pelo service
        $resultado = $this->consultaService->consultarProcesso($numeroNormalizado);
        
        if (!$resultado['success']) {
            return back()->with('error', $resultado['message']);
        }

        $movimentacoes = $resultado['data']['movimentos'] ?? [];
        $partes = $resultado['partes'] ?? $partes;

        return view('admin.consulta-processual.historico', [
            'numero' => $numeroNormalizado,
            'partes' => $partes,
            'movimentacoes' => $movimentacoes,
        ]);
    }

    public function sincronizar(Processo $processo)
    {
        try {
            $resultado = $this->consultaService->sincronizarProcesso($processo);
            
            if ($resultado['success']) {
                return redirect()
                    ->route('admin.processos.show', $processo)
                    ->with('success', "Sincronização concluída. {$resultado['movimentacoes_importadas']} movimentações importadas.");
            }

            return back()->with('error', $resultado['message']);
        } catch (\Exception $e) {
            Log::error('Erro ao sincronizar processo', [
                'processo_id' => $processo->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erro ao sincronizar processo: ' . $e->getMessage());
        }
    }
}

