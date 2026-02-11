<?php

namespace App\Services;

use App\Models\Processo;
use App\Models\TemplatePeticao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de Geração de Petições com IA
 * 
 * Gera petições jurídicas seguindo as regras do CPC Art. 319:
 * I - endereçamento ao juízo
 * II - qualificação das partes
 * III - fatos e fundamentos jurídicos
 * IV - pedido com especificações
 * V - valor da causa
 * VI - provas que pretende produzir
 * VII - opção ou não pelo julgamento antecipado
 */
class PeticaoIAService
{
    private string $apiKey;
    private string $model = 'gpt-4';
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.key', env('OPENAI_API_KEY'));
    }

    /**
     * Gera petição inicial usando IA
     */
    public function gerarPeticaoInicial(Processo $processo, array $parametros = []): array
    {
        try {
            // Validar dados necessários
            $this->validarDadosProcesso($processo);

            // Montar prompt seguindo regras do CPC
            $prompt = $this->montarPromptPeticaoInicial($processo, $parametros);

            // Chamar API da IA
            $conteudo = $this->chamarIA($prompt);

            // Validar estrutura jurídica da petição gerada
            $validacao = $this->validarEstruturaPeticao($conteudo);

            if (!$validacao['valida']) {
                throw new \Exception('Petição gerada não atende aos requisitos do CPC: ' . implode(', ', $validacao['erros']));
            }

            return [
                'sucesso' => true,
                'conteudo' => $conteudo,
                'metadata' => [
                    'processo_id' => $processo->id,
                    'numero_processo' => $processo->numero_processo,
                    'tipo' => 'peticao_inicial',
                    'gerado_em' => now()->toDateTimeString(),
                ],
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao gerar petição com IA', [
                'processo_id' => $processo->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'sucesso' => false,
                'erro' => $e->getMessage(),
            ];
        }
    }

    /**
     * Gera petição intermediária (contestação, réplica, etc.)
     */
    public function gerarPeticaoIntermediaria(Processo $processo, string $tipo, array $parametros = []): array
    {
        try {
            $prompt = $this->montarPromptPeticaoIntermediaria($processo, $tipo, $parametros);
            $conteudo = $this->chamarIA($prompt);

            return [
                'sucesso' => true,
                'conteudo' => $conteudo,
                'metadata' => [
                    'processo_id' => $processo->id,
                    'tipo' => $tipo,
                    'gerado_em' => now()->toDateTimeString(),
                ],
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao gerar petição intermediária', [
                'processo_id' => $processo->id,
                'tipo' => $tipo,
                'error' => $e->getMessage(),
            ]);

            return [
                'sucesso' => false,
                'erro' => $e->getMessage(),
            ];
        }
    }

    /**
     * Gera petição a partir de template
     */
    public function gerarDeTemplate(TemplatePeticao $template, Processo $processo, array $variaveis = []): array
    {
        try {
            // Substituir variáveis no template
            $conteudo = $this->substituirVariaveis($template->conteudo, $variaveis, $processo);

            // Melhorar com IA se solicitado
            if ($variaveis['melhorar_com_ia'] ?? false) {
                $prompt = $this->montarPromptMelhoriaTemplate($conteudo, $processo);
                $conteudo = $this->chamarIA($prompt);
            }

            return [
                'sucesso' => true,
                'conteudo' => $conteudo,
                'template_id' => $template->id,
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao gerar petição de template', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'sucesso' => false,
                'erro' => $e->getMessage(),
            ];
        }
    }

    /**
     * Monta prompt para petição inicial seguindo CPC Art. 319
     */
    private function montarPromptPeticaoInicial(Processo $processo, array $parametros): string
    {
        $cliente = $processo->cliente;
        $advogado = $processo->advogado;
        $vara = $processo->vara;
        $especialidade = $processo->especialidade;

        $prompt = <<<PROMPT
Você é um advogado especialista em {$especialidade->nome} no Brasil. Gere uma PETIÇÃO INICIAL seguindo RIGOROSAMENTE o Código de Processo Civil (CPC) Art. 319.

DADOS DO PROCESSO:
- Número: {$processo->numero_processo}
- Vara: {$vara->nome}
- Comarca: {$vara->comarca ?? 'Não informada'}

AUTOR (CLIENTE):
- Nome: {$cliente->nome}
- CPF/CNPJ: {$cliente->cpf_cnpj}
- Endereço: {$cliente->endereco}, {$cliente->cidade}/{$cliente->estado}
- Email: {$cliente->email}
- Telefone: {$cliente->telefone}

ADVOGADO:
- Nome: {$advogado->nome}
- OAB: {$advogado->oab}
- Email: {$advogado->email}
- Telefone: {$advogado->telefone}

RÉU:
{$this->formatarDadosReu($processo)}

CONTEXTO:
{$parametros['contexto'] ?? $processo->observacoes ?? 'Não informado'}

PEDIDOS:
{$parametros['pedidos'] ?? 'Não especificado'}

VALOR DA CAUSA:
{$parametros['valor_causa'] ?? 'A ser definido'}

INSTRUÇÕES OBRIGATÓRIAS:
1. A petição DEVE conter TODOS os requisitos do Art. 319 do CPC:
   I - Endereçamento correto ao juízo competente
   II - Qualificação completa das partes (nome, CPF/CNPJ, endereço, profissão)
   III - Fatos e fundamentos jurídicos do pedido
   IV - Pedido com suas especificações
   V - Valor da causa
   VI - Provas com que pretende demonstrar a verdade dos fatos
   VII - Opção ou não pelo julgamento antecipado

2. ESTRUTURA OBRIGATÓRIA:
   - CABEÇALHO: Endereçamento ao juízo
   - QUALIFICAÇÃO DAS PARTES
   - DOS FATOS
   - DO DIREITO (fundamentos jurídicos com citação de leis)
   - DOS PEDIDOS (numerados e específicos)
   - DO VALOR DA CAUSA
   - DAS PROVAS
   - REQUERIMENTOS FINAIS

3. LINGUAGEM JURÍDICA:
   - Use linguagem técnica e formal
   - Cite artigos de lei pertinentes
   - Use jurisprudência quando relevante
   - Evite linguagem coloquial

4. FORMATAÇÃO:
   - Use parágrafos numerados
   - Destaque títulos em MAIÚSCULAS
   - Mantenha estrutura clara e organizada

GERE A PETIÇÃO INICIAL COMPLETA:
PROMPT;

        return $prompt;
    }

    /**
     * Monta prompt para petição intermediária
     */
    private function montarPromptPeticaoIntermediaria(Processo $processo, string $tipo, array $parametros): string
    {
        $tiposValidos = [
            'contestacao' => 'CONTESTAÇÃO',
            'replica' => 'RÉPLICA',
            'agravo' => 'AGRAVO DE INSTRUMENTO',
            'apelacao' => 'APELAÇÃO',
            'embargos_declaracao' => 'EMBARGOS DE DECLARAÇÃO',
            'recurso_especial' => 'RECURSO ESPECIAL',
            'manifestacao' => 'MANIFESTAÇÃO',
        ];

        $nomeTipo = $tiposValidos[$tipo] ?? strtoupper($tipo);

        $prompt = <<<PROMPT
Você é um advogado especialista. Gere uma {$nomeTipo} para o processo {$processo->numero_processo}.

CONTEXTO DO PROCESSO:
{$parametros['contexto'] ?? $processo->observacoes ?? 'Não informado'}

ARGUMENTOS:
{$parametros['argumentos'] ?? 'Não especificado'}

FUNDAMENTOS JURÍDICOS:
{$parametros['fundamentos'] ?? 'A definir'}

INSTRUÇÕES:
1. Siga a estrutura adequada para {$nomeTipo}
2. Use linguagem jurídica técnica
3. Cite leis e jurisprudência pertinentes
4. Seja objetivo e fundamentado
5. Inclua todos os requerimentos necessários

GERE A {$nomeTipo} COMPLETA:
PROMPT;

        return $prompt;
    }

    /**
     * Monta prompt para melhorar template
     */
    private function montarPromptMelhoriaTemplate(string $conteudo, Processo $processo): string
    {
        return <<<PROMPT
Você é um advogado especialista. Revise e melhore a seguinte petição, mantendo a estrutura mas aprimorando:
1. Linguagem jurídica
2. Fundamentação legal
3. Clareza e objetividade
4. Conformidade com o CPC

PETIÇÃO ORIGINAL:
{$conteudo}

DADOS DO PROCESSO:
- Número: {$processo->numero_processo}
- Especialidade: {$processo->especialidade->nome ?? 'Não informada'}

GERE A VERSÃO MELHORADA:
PROMPT;
    }

    /**
     * Chama API da IA (OpenAI)
     */
    private function chamarIA(string $prompt): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('Chave da API OpenAI não configurada. Configure OPENAI_API_KEY no .env');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um advogado especialista em direito brasileiro, com profundo conhecimento do CPC e das melhores práticas jurídicas. Gere petições tecnicamente perfeitas e juridicamente fundamentadas.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 4000,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Erro na API OpenAI: ' . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Valida estrutura da petição gerada
     */
    private function validarEstruturaPeticao(string $conteudo): array
    {
        $erros = [];

        // Verificar elementos obrigatórios do Art. 319 CPC
        $elementosObrigatorios = [
            'endereçamento' => ['excelentíssimo', 'juiz', 'vara', 'comarca'],
            'qualificacao' => ['cpf', 'cnpj', 'endereço', 'residente'],
            'fatos' => ['dos fatos', 'ocorrido', 'aconteceu'],
            'direito' => ['do direito', 'fundamento', 'artigo', 'lei'],
            'pedidos' => ['dos pedidos', 'requer', 'pede'],
            'valor_causa' => ['valor da causa', 'valor de'],
            'provas' => ['das provas', 'prova', 'documento'],
        ];

        $conteudoLower = mb_strtolower($conteudo);

        foreach ($elementosObrigatorios as $elemento => $palavrasChave) {
            $encontrado = false;
            foreach ($palavrasChave as $palavra) {
                if (str_contains($conteudoLower, mb_strtolower($palavra))) {
                    $encontrado = true;
                    break;
                }
            }
            
            if (!$encontrado) {
                $erros[] = "Elemento obrigatório ausente: {$elemento}";
            }
        }

        // Verificar tamanho mínimo
        if (strlen($conteudo) < 500) {
            $erros[] = "Petição muito curta (mínimo 500 caracteres)";
        }

        return [
            'valida' => empty($erros),
            'erros' => $erros,
        ];
    }

    /**
     * Valida dados do processo antes de gerar petição
     */
    private function validarDadosProcesso(Processo $processo): void
    {
        if (!$processo->cliente) {
            throw new \Exception('Processo sem cliente vinculado');
        }

        if (!$processo->advogado) {
            throw new \Exception('Processo sem advogado vinculado');
        }

        if (!$processo->vara) {
            throw new \Exception('Processo sem vara definida');
        }

        if (!$processo->especialidade) {
            throw new \Exception('Processo sem especialidade definida');
        }
    }

    /**
     * Formata dados do réu
     */
    private function formatarDadosReu(Processo $processo): string
    {
        if (!$processo->processado) {
            return "Não informado";
        }

        $reu = $processo->processado;
        return <<<REU
- Nome: {$reu->nome}
- CPF/CNPJ: {$reu->cpf_cnpj}
- Endereço: {$reu->endereco}, {$reu->cidade}/{$reu->estado}
REU;
    }

    /**
     * Substitui variáveis no template
     */
    private function substituirVariaveis(string $conteudo, array $variaveis, Processo $processo): string
    {
        // Variáveis do processo
        $substituicoes = [
            '{{numero_processo}}' => $processo->numero_processo,
            '{{cliente_nome}}' => $processo->cliente->nome ?? '',
            '{{cliente_cpf}}' => $processo->cliente->cpf_cnpj ?? '',
            '{{advogado_nome}}' => $processo->advogado->nome ?? '',
            '{{advogado_oab}}' => $processo->advogado->oab ?? '',
            '{{vara_nome}}' => $processo->vara->nome ?? '',
            '{{data_hoje}}' => now()->format('d/m/Y'),
        ];

        // Variáveis customizadas
        foreach ($variaveis as $chave => $valor) {
            $substituicoes["{{" . $chave . "}}"] = $valor;
        }

        return str_replace(array_keys($substituicoes), array_values($substituicoes), $conteudo);
    }
}
