<?php

namespace App\Services;

use App\Models\Processo;
use App\Models\MovimentacaoProcessual;
use App\Helpers\ProcessoCNJHelper;
use App\Helpers\TribunaisEndpointsHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ConsultaProcessualService
{
    private string $apiBaseUrl = 'https://api-publica.datajud.cnj.jus.br';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('API_CNJ_KEY', '');
    }

    /**
     * Consultar processo na API do CNJ/DataJud
     * 
     * @param string $numeroProcesso Número do processo (formatado ou não)
     * @param string|null $tribunal Código do tribunal (opcional, será extraído se não informado)
     * @return array
     */
    public function consultarProcesso(string $numeroProcesso, ?string $tribunal = null): array
    {
        // Normalizar número primeiro
        $numeroNormalizado = ProcessoCNJHelper::normalizar($numeroProcesso);
        if (!$numeroNormalizado) {
            return [
                'success' => false,
                'message' => 'Número de processo inválido. Use o padrão CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO',
                'erros' => ['Formato inválido'],
            ];
        }
        
        // Extrair partes
        $partes = ProcessoCNJHelper::extrairPartes($numeroNormalizado);
        if (!$partes) {
            return [
                'success' => false,
                'message' => 'Não foi possível extrair as partes do número de processo.',
                'erros' => ['Extração de partes falhou'],
            ];
        }
        
        // Extrair tribunal e segmento
        if (!$tribunal) {
            $tribunal = $partes['tribunal'];
        }
        $segmento = $partes['segmento'];

        // Obter número limpo para API
        $numeroLimpo = $partes['numero_limpo'];
        
        // Obter endpoint correto baseado no segmento e tribunal
        $endpoint = TribunaisEndpointsHelper::getUrlCompleta($segmento, $tribunal);
        
        if (!$endpoint) {
            return [
                'success' => false,
                'message' => "Endpoint não encontrado para o segmento {$segmento} e tribunal {$tribunal}.",
            ];
        }
        
        // Cache de 1 hora para consultas
        $cacheKey = "consulta_processual.{$segmento}.{$tribunal}.{$numeroLimpo}";
        
        return Cache::remember($cacheKey, 3600, function () use ($numeroLimpo, $endpoint, $partes, $segmento, $tribunal) {
            try {
                // A API do DataJud usa formato Elasticsearch: /{endpoint}/_search
                // Requisição POST com body JSON contendo o número do processo
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'APIKey ' . $this->apiKey,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, [
                        'query' => [
                            'match' => [
                                'numeroProcesso' => $numeroLimpo
                            ]
                        ]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Adicionar informações extraídas do número
                    $data['numero_formatado'] = $partes['numero_formatado'];
                    $data['partes_processo'] = $partes;
                    
                    Log::info('Consulta processual realizada com sucesso', [
                        'tribunal' => $tribunal,
                        'numero' => $numeroLimpo,
                        'numero_formatado' => $partes['numero_formatado'],
                    ]);
                    
                    return [
                        'success' => true,
                        'data' => $data,
                        'partes' => $partes,
                    ];
                } else {
                    Log::warning('Erro na consulta processual', [
                        'tribunal' => $tribunal,
                        'numero' => $numeroLimpo,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $this->getErrorMessage($response->status()),
                        'status' => $response->status(),
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Erro ao consultar processo na API', [
                    'tribunal' => $tribunal,
                    'numero' => $numeroLimpo,
                    'error' => $e->getMessage(),
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Erro ao conectar com a API: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Importar movimentações do processo para o banco
     */
    public function importarMovimentacoes(Processo $processo, array $dadosApi): int
    {
        $importadas = 0;
        
        if (!isset($dadosApi['movimentos']) || !is_array($dadosApi['movimentos'])) {
            return $importadas;
        }

        foreach ($dadosApi['movimentos'] as $movimento) {
            // Verificar se já existe
            $existe = MovimentacaoProcessual::where('processo_id', $processo->id)
                ->where('importado_api', true)
                ->whereJsonContains('dados_api->id', $movimento['id'] ?? null)
                ->exists();

            if (!$existe) {
                MovimentacaoProcessual::create([
                    'processo_id' => $processo->id,
                    'user_id' => auth()->id(),
                    'titulo' => $movimento['nome'] ?? 'Movimentação',
                    'descricao' => $movimento['descricao'] ?? '',
                    'data' => $movimento['dataHora'] ?? now(),
                    'origem' => 'api_cnj',
                    'dados_api' => $movimento,
                    'importado_api' => true,
                ]);
                
                $importadas++;
            }
        }

        // Atualizar última movimentação do processo
        if ($importadas > 0) {
            $ultimaMovimentacao = collect($dadosApi['movimentos'])->first();
            $processo->update([
                'ultima_movimentacao' => $ultimaMovimentacao['nome'] ?? null,
                'ultima_movimentacao_data' => $ultimaMovimentacao['dataHora'] ?? now(),
            ]);
        }

        return $importadas;
    }

    /**
     * Sincronizar processo com dados da API
     */
    public function sincronizarProcesso(Processo $processo): array
    {
        // Extrair informações do número de processo
        $partes = ProcessoCNJHelper::extrairPartes($processo->numero_processo);
        
        if (!$partes) {
            return [
                'success' => false,
                'message' => 'Número de processo inválido. Verifique o formato.',
            ];
        }

        $tribunal = $partes['tribunal'];

        $consulta = $this->consultarProcesso($processo->numero_processo, $tribunal);
        
        if (!$consulta['success']) {
            return $consulta;
        }

        $dadosApi = $consulta['data'];
        
        // Importar movimentações
        $movimentacoesImportadas = $this->importarMovimentacoes($processo, $dadosApi);
        
        return [
            'success' => true,
            'movimentacoes_importadas' => $movimentacoesImportadas,
            'data' => $dadosApi,
        ];
    }

    /**
     * Extrair código do tribunal do número do processo
     * @deprecated Use ProcessoCNJHelper::extrairTribunal() diretamente
     */
    public function extrairTribunal(string $numeroProcesso): ?string
    {
        return ProcessoCNJHelper::extrairTribunal($numeroProcesso);
    }

    /**
     * Obter mensagem de erro baseada no status HTTP
     */
    private function getErrorMessage(int $status): string
    {
        return match($status) {
            400 => 'Requisição inválida. Verifique o número do processo.',
            401 => 'Não autorizado. Verifique a chave da API.',
            404 => 'Processo não encontrado.',
            429 => 'Muitas requisições. Tente novamente mais tarde.',
            500 => 'Erro interno do servidor da API.',
            default => 'Erro ao consultar processo.',
        };
    }

    /**
     * Lista de tribunais disponíveis organizados por segmento
     */
    public function getTribunais(): array
    {
        return [
            '1' => [
                'nome' => 'Justiça Federal / Militar',
                'tribunais' => array_merge($this->getTribunaisFederais(), $this->getTribunaisMilitares()),
            ],
            '2' => [
                'nome' => 'Justiça Eleitoral',
                'tribunais' => $this->getTribunaisEleitorais(),
            ],
            '3' => [
                'nome' => 'Justiça do Trabalho',
                'tribunais' => $this->getTribunaisTrabalho(),
            ],
            '4' => [
                'nome' => 'Justiça Federal / Militar',
                'tribunais' => array_merge($this->getTribunaisFederais(), $this->getTribunaisMilitares()),
            ],
            '5' => [
                'nome' => 'Justiça Estadual',
                'tribunais' => $this->getTribunaisEstaduais(),
            ],
            '6' => [
                'nome' => 'Justiça do DF e Territórios',
                'tribunais' => $this->getTribunaisDF(),
            ],
            '7' => [
                'nome' => 'Tribunais Superiores',
                'tribunais' => $this->getTribunaisSuperiores(),
            ],
            '8' => [
                'nome' => 'Justiça Estadual',
                'tribunais' => $this->getTribunaisEstaduais(),
            ],
        ];
    }

    /**
     * Obter lista plana de todos os tribunais
     */
    public function getTribunaisListaPlana(): array
    {
        $todos = [];
        foreach ($this->getTribunais() as $segmento => $dados) {
            foreach ($dados['tribunais'] as $codigo => $nome) {
                $todos[$codigo] = $nome;
            }
        }
        return $todos;
    }

    /**
     * Obter tribunais por segmento
     */
    public function getTribunaisPorSegmento(string $segmento): array
    {
        $tribunais = $this->getTribunais();
        return $tribunais[$segmento]['tribunais'] ?? [];
    }

    /**
     * Tribunais Superiores
     * Nota: Código TR 90 é usado para STF, STJ, TSE, TST, STM, CJF, CNJ, CSJT
     */
    private function getTribunaisSuperiores(): array
    {
        return [
            '90' => 'STF/STJ/TSE/TST/STM/CJF/CNJ/CSJT - Tribunais Superiores',
        ];
    }

    /**
     * Justiça Federal
     */
    private function getTribunaisFederais(): array
    {
        return [
            '01' => 'TRF1 - 1ª Região (DF, GO, MS, MT, TO, BA, MA, PA, PI, AM, AC, AP, RO, RR)',
            '02' => 'TRF2 - 2ª Região (RJ, ES)',
            '03' => 'TRF3 - 3ª Região (SP, MS)',
            '04' => 'TRF4 - 4ª Região (RS, SC, PR)',
            '05' => 'TRF5 - 5ª Região (PE, AL, PB, RN, CE, SE)',
            '06' => 'TRF6 - 6ª Região (MG)',
        ];
    }

    /**
     * Justiça Eleitoral
     */
    private function getTribunaisEleitorais(): array
    {
        return [
            '01' => 'TRE-AC - Acre',
            '02' => 'TRE-AL - Alagoas',
            '03' => 'TRE-AP - Amapá',
            '04' => 'TRE-AM - Amazonas',
            '05' => 'TRE-BA - Bahia',
            '06' => 'TRE-CE - Ceará',
            '07' => 'TRE-DF - Distrito Federal',
            '08' => 'TRE-ES - Espírito Santo',
            '09' => 'TRE-GO - Goiás',
            '10' => 'TRE-MA - Maranhão',
            '11' => 'TRE-MT - Mato Grosso',
            '12' => 'TRE-MS - Mato Grosso do Sul',
            '13' => 'TRE-MG - Minas Gerais',
            '14' => 'TRE-PA - Pará',
            '15' => 'TRE-PB - Paraíba',
            '16' => 'TRE-PR - Paraná',
            '17' => 'TRE-PE - Pernambuco',
            '18' => 'TRE-PI - Piauí',
            '19' => 'TRE-RJ - Rio de Janeiro',
            '20' => 'TRE-RN - Rio Grande do Norte',
            '21' => 'TRE-RS - Rio Grande do Sul',
            '22' => 'TRE-RO - Rondônia',
            '23' => 'TRE-RR - Roraima',
            '24' => 'TRE-SC - Santa Catarina',
            '25' => 'TRE-SE - Sergipe',
            '26' => 'TRE-SP - São Paulo',
            '27' => 'TRE-TO - Tocantins',
        ];
    }

    /**
     * Justiça do Trabalho
     */
    private function getTribunaisTrabalho(): array
    {
        return [
            '01' => 'TRT1 - 1ª Região (RJ)',
            '02' => 'TRT2 - 2ª Região (SP)',
            '03' => 'TRT3 - 3ª Região (MG)',
            '04' => 'TRT4 - 4ª Região (RS)',
            '05' => 'TRT5 - 5ª Região (BA)',
            '06' => 'TRT6 - 6ª Região (PE)',
            '07' => 'TRT7 - 7ª Região (CE)',
            '08' => 'TRT8 - 8ª Região (PA/AP)',
            '09' => 'TRT9 - 9ª Região (PR)',
            '10' => 'TRT10 - 10ª Região (DF/TO)',
            '11' => 'TRT11 - 11ª Região (AM/RR)',
            '12' => 'TRT12 - 12ª Região (SC)',
            '13' => 'TRT13 - 13ª Região (PB)',
            '14' => 'TRT14 - 14ª Região (RO/AC)',
            '15' => 'TRT15 - 15ª Região (SP - Campinas)',
            '16' => 'TRT16 - 16ª Região (MA)',
            '17' => 'TRT17 - 17ª Região (ES)',
            '18' => 'TRT18 - 18ª Região (GO)',
            '19' => 'TRT19 - 19ª Região (AL)',
            '20' => 'TRT20 - 20ª Região (SE)',
            '21' => 'TRT21 - 21ª Região (RN)',
            '22' => 'TRT22 - 22ª Região (PI)',
            '23' => 'TRT23 - 23ª Região (MT)',
            '24' => 'TRT24 - 24ª Região (MS)',
        ];
    }

    /**
     * Justiça Militar
     */
    private function getTribunaisMilitares(): array
    {
        return [
            '10' => 'STM - Superior Tribunal Militar',
            '13' => 'TJM-MG - Tribunal de Justiça Militar de Minas Gerais',
            '21' => 'TJM-RS - Tribunal de Justiça Militar do Rio Grande do Sul',
            '26' => 'TJM-SP - Tribunal de Justiça Militar de São Paulo',
        ];
    }

    /**
     * Justiça Estadual
     */
    private function getTribunaisEstaduais(): array
    {
        return [
            '01' => 'TJAC - Acre',
            '02' => 'TJAL - Alagoas',
            '03' => 'TJAP - Amapá',
            '04' => 'TJAM - Amazonas',
            '05' => 'TJBA - Bahia',
            '06' => 'TJCE - Ceará',
            '07' => 'TJDFT - Distrito Federal e Territórios',
            '08' => 'TJES - Espírito Santo',
            '09' => 'TJGO - Goiás',
            '10' => 'TJMA - Maranhão',
            '11' => 'TJMT - Mato Grosso',
            '12' => 'TJMS - Mato Grosso do Sul',
            '13' => 'TJMG - Minas Gerais',
            '14' => 'TJPA - Pará',
            '15' => 'TJPB - Paraíba',
            '16' => 'TJPR - Paraná',
            '17' => 'TJPE - Pernambuco',
            '18' => 'TJPI - Piauí',
            '19' => 'TJRJ - Rio de Janeiro',
            '20' => 'TJRN - Rio Grande do Norte',
            '21' => 'TJRS - Rio Grande do Sul',
            '22' => 'TJRO - Rondônia',
            '23' => 'TJRR - Roraima',
            '24' => 'TJSC - Santa Catarina',
            '25' => 'TJSE - Sergipe',
            '26' => 'TJSP - São Paulo',
            '27' => 'TJTO - Tocantins',
        ];
    }

    /**
     * Justiça do DF e Territórios
     */
    private function getTribunaisDF(): array
    {
        return [
            '07' => 'TJDFT - Distrito Federal e Territórios',
        ];
    }
}

