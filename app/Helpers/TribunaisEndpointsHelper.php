<?php

namespace App\Helpers;

class TribunaisEndpointsHelper
{
    /**
     * Mapeamento de códigos de tribunal para endpoints da API DataJud
     * Formato: código_tribunal => endpoint_api
     */
    public static function getEndpoints(): array
    {
        return [
            // Tribunais Superiores
            '04' => 'api_publica_tst',      // TST
            '05' => 'api_publica_tse',      // TSE
            '02' => 'api_publica_stj',      // STJ
            '03' => 'api_publica_stm',      // STM
            
            // Justiça Federal
            '01' => 'api_publica_trf1',     // TRF1
            '02' => 'api_publica_trf2',     // TRF2
            '03' => 'api_publica_trf3',     // TRF3
            '04' => 'api_publica_trf4',     // TRF4
            '05' => 'api_publica_trf5',     // TRF5
            '06' => 'api_publica_trf6',     // TRF6
            
            // Justiça Estadual
            '01' => 'api_publica_tjac',     // TJAC
            '02' => 'api_publica_tjal',     // TJAL
            '04' => 'api_publica_tjam',     // TJAM
            '03' => 'api_publica_tjap',     // TJAP
            '05' => 'api_publica_tjba',     // TJBA
            '06' => 'api_publica_tjce',     // TJCE
            '07' => 'api_publica_tjdft',    // TJDFT
            '08' => 'api_publica_tjes',     // TJES
            '09' => 'api_publica_tjgo',     // TJGO
            '10' => 'api_publica_tjma',     // TJMA
            '13' => 'api_publica_tjmg',     // TJMG
            '12' => 'api_publica_tjms',     // TJMS
            '11' => 'api_publica_tjmt',     // TJMT
            '14' => 'api_publica_tjpa',     // TJPA
            '15' => 'api_publica_tjpb',     // TJPB
            '17' => 'api_publica_tjpe',     // TJPE
            '18' => 'api_publica_tjpi',     // TJPI
            '16' => 'api_publica_tjpr',     // TJPR
            '19' => 'api_publica_tjrj',     // TJRJ
            '20' => 'api_publica_tjrn',     // TJRN
            '22' => 'api_publica_tjro',     // TJRO
            '23' => 'api_publica_tjrr',     // TJRR
            '21' => 'api_publica_tjrs',     // TJRS
            '24' => 'api_publica_tjsc',     // TJSC
            '26' => 'api_publica_tjse',     // TJSE
            '25' => 'api_publica_tjsp',     // TJSP
            '27' => 'api_publica_tjto',     // TJTO
            
            // Justiça do Trabalho
            '01' => 'api_publica_trt1',     // TRT1
            '02' => 'api_publica_trt2',     // TRT2
            '03' => 'api_publica_trt3',     // TRT3
            '04' => 'api_publica_trt4',     // TRT4
            '05' => 'api_publica_trt5',     // TRT5
            '06' => 'api_publica_trt6',     // TRT6
            '07' => 'api_publica_trt7',     // TRT7
            '08' => 'api_publica_trt8',     // TRT8
            '09' => 'api_publica_trt9',     // TRT9
            '10' => 'api_publica_trt10',    // TRT10
            '11' => 'api_publica_trt11',    // TRT11
            '12' => 'api_publica_trt12',    // TRT12
            '13' => 'api_publica_trt13',    // TRT13
            '14' => 'api_publica_trt14',    // TRT14
            '15' => 'api_publica_trt15',    // TRT15
            '16' => 'api_publica_trt16',    // TRT16
            '17' => 'api_publica_trt17',    // TRT17
            '18' => 'api_publica_trt18',    // TRT18
            '19' => 'api_publica_trt19',    // TRT19
            '20' => 'api_publica_trt20',    // TRT20
            '21' => 'api_publica_trt21',    // TRT21
            '22' => 'api_publica_trt22',    // TRT22
            '23' => 'api_publica_trt23',    // TRT23
            '24' => 'api_publica_trt24',    // TRT24
            
            // Justiça Eleitoral
            '01' => 'api_publica_tre-ac',   // TRE-AC
            '02' => 'api_publica_tre-al',   // TRE-AL
            '04' => 'api_publica_tre-am',   // TRE-AM
            '03' => 'api_publica_tre-ap',   // TRE-AP
            '05' => 'api_publica_tre-ba',   // TRE-BA
            '06' => 'api_publica_tre-ce',   // TRE-CE
            '07' => 'api_publica_tre-dft',  // TRE-DFT
            '08' => 'api_publica_tre-es',   // TRE-ES
            '09' => 'api_publica_tre-go',   // TRE-GO
            '10' => 'api_publica_tre-ma',   // TRE-MA
            '13' => 'api_publica_tre-mg',   // TRE-MG
            '12' => 'api_publica_tre-ms',   // TRE-MS
            '11' => 'api_publica_tre-mt',   // TRE-MT
            '14' => 'api_publica_tre-pa',   // TRE-PA
            '15' => 'api_publica_tre-pb',   // TRE-PB
            '17' => 'api_publica_tre-pe',   // TRE-PE
            '18' => 'api_publica_tre-pi',   // TRE-PI
            '16' => 'api_publica_tre-pr',   // TRE-PR
            '19' => 'api_publica_tre-rj',   // TRE-RJ
            '20' => 'api_publica_tre-rn',   // TRE-RN
            '22' => 'api_publica_tre-ro',   // TRE-RO
            '23' => 'api_publica_tre-rr',   // TRE-RR
            '21' => 'api_publica_tre-rs',   // TRE-RS
            '24' => 'api_publica_tre-sc',   // TRE-SC
            '26' => 'api_publica_tre-se',   // TRE-SE
            '25' => 'api_publica_tre-sp',   // TRE-SP
            '27' => 'api_publica_tre-to',   // TRE-TO
            
            // Justiça Militar
            '02' => 'api_publica_tjmsp',    // TJMSP
            '03' => 'api_publica_tjmmg',    // TJMMG
            '04' => 'api_publica_tjmrs',    // TJMRS
        ];
    }

    /**
     * Obter endpoint completo para um tribunal e segmento
     * 
     * @param string $segmento Segmento da justiça (1-8)
     * @param string $tribunal Código do tribunal (2 dígitos)
     * @return string|null
     */
    public static function getEndpoint(string $segmento, string $tribunal): ?string
    {
        $endpoints = self::getEndpointsPorSegmento();
        
        return $endpoints[$segmento][$tribunal] ?? null;
    }

    /**
     * Obter endpoints organizados por segmento
     * Baseado na numeração única do CNJ - Valor "J" (Segmento/O órgão do Judiciário)
     * 
     * 1 - Supremo Tribunal Federal (STF)
     * 2 - Conselho Nacional de Justiça (CNJ)
     * 3 - Superior Tribunal de Justiça (STJ)
     * 4 - Justiça Federal
     * 5 - Justiça do Trabalho
     * 6 - Justiça Eleitoral
     * 7 - Justiça Militar da União
     * 8 - Justiça dos Estados e do Distrito Federal e Territórios
     * 9 - Justiça Militar Estadual
     */
    public static function getEndpointsPorSegmento(): array
    {
        return [
            // 1 - Supremo Tribunal Federal (STF)
            '1' => [
                '90' => 'api_publica_stf',   // STF
            ],
            
            // 2 - Conselho Nacional de Justiça (CNJ)
            '2' => [
                '90' => 'api_publica_cnj',   // CNJ (se disponível na API)
            ],
            
            // 3 - Superior Tribunal de Justiça (STJ)
            '3' => [
                '90' => 'api_publica_stj',   // STJ
            ],
            
            // 4 - Justiça Federal (TRFs)
            '4' => [
                '01' => 'api_publica_trf1',  // TRF 1ª Região
                '02' => 'api_publica_trf2',  // TRF 2ª Região
                '03' => 'api_publica_trf3',  // TRF 3ª Região
                '04' => 'api_publica_trf4',  // TRF 4ª Região
                '05' => 'api_publica_trf5',  // TRF 5ª Região
                '06' => 'api_publica_trf6',  // TRF 6ª Região
            ],
            
            // 5 - Justiça do Trabalho (TRTs)
            '5' => [
                '01' => 'api_publica_trt1',   // TRT 1ª Região (RJ)
                '02' => 'api_publica_trt2',   // TRT 2ª Região (SP)
                '03' => 'api_publica_trt3',   // TRT 3ª Região (MG)
                '04' => 'api_publica_trt4',   // TRT 4ª Região (RS)
                '05' => 'api_publica_trt5',   // TRT 5ª Região (BA)
                '06' => 'api_publica_trt6',   // TRT 6ª Região (PE)
                '07' => 'api_publica_trt7',   // TRT 7ª Região (CE)
                '08' => 'api_publica_trt8',   // TRT 8ª Região (PA/AP)
                '09' => 'api_publica_trt9',   // TRT 9ª Região (PR)
                '10' => 'api_publica_trt10',  // TRT 10ª Região (DF/TO)
                '11' => 'api_publica_trt11',  // TRT 11ª Região (AM/RR)
                '12' => 'api_publica_trt12',  // TRT 12ª Região (SC)
                '13' => 'api_publica_trt13',  // TRT 13ª Região (PB)
                '14' => 'api_publica_trt14',  // TRT 14ª Região (RO/AC)
                '15' => 'api_publica_trt15',  // TRT 15ª Região (Campinas/SP)
                '16' => 'api_publica_trt16',  // TRT 16ª Região (MA)
                '17' => 'api_publica_trt17',  // TRT 17ª Região (ES)
                '18' => 'api_publica_trt18',  // TRT 18ª Região (GO)
                '19' => 'api_publica_trt19',  // TRT 19ª Região (AL)
                '20' => 'api_publica_trt20',  // TRT 20ª Região (SE)
                '21' => 'api_publica_trt21',  // TRT 21ª Região (RN)
                '22' => 'api_publica_trt22',  // TRT 22ª Região (PI)
                '23' => 'api_publica_trt23',  // TRT 23ª Região (MT)
                '24' => 'api_publica_trt24',  // TRT 24ª Região (MS)
            ],
            
            // 6 - Justiça Eleitoral (TREs)
            '6' => [
                '01' => 'api_publica_tre-ac',  // TRE-AC
                '02' => 'api_publica_tre-al',  // TRE-AL
                '03' => 'api_publica_tre-ap',  // TRE-AP
                '04' => 'api_publica_tre-am',  // TRE-AM
                '05' => 'api_publica_tre-ba',  // TRE-BA
                '06' => 'api_publica_tre-ce',  // TRE-CE
                '07' => 'api_publica_tre-dft', // TRE-DF
                '08' => 'api_publica_tre-es',  // TRE-ES
                '09' => 'api_publica_tre-go',  // TRE-GO
                '10' => 'api_publica_tre-ma',  // TRE-MA
                '11' => 'api_publica_tre-mt',  // TRE-MT
                '12' => 'api_publica_tre-ms',  // TRE-MS
                '13' => 'api_publica_tre-mg',  // TRE-MG
                '14' => 'api_publica_tre-pa',  // TRE-PA
                '15' => 'api_publica_tre-pb',  // TRE-PB
                '16' => 'api_publica_tre-pr',  // TRE-PR
                '17' => 'api_publica_tre-pe',  // TRE-PE
                '18' => 'api_publica_tre-pi',  // TRE-PI
                '19' => 'api_publica_tre-rj',  // TRE-RJ
                '20' => 'api_publica_tre-rn',  // TRE-RN
                '21' => 'api_publica_tre-rs',  // TRE-RS
                '22' => 'api_publica_tre-ro',  // TRE-RO
                '23' => 'api_publica_tre-rr',  // TRE-RR
                '24' => 'api_publica_tre-sc',  // TRE-SC
                '25' => 'api_publica_tre-se',  // TRE-SE
                '26' => 'api_publica_tre-sp',  // TRE-SP
                '27' => 'api_publica_tre-to',  // TRE-TO
            ],
            
            // 7 - Justiça Militar da União (STM)
            '7' => [
                '90' => 'api_publica_stm',   // STM - Superior Tribunal Militar
            ],
            
            // 8 - Justiça dos Estados e do Distrito Federal e Territórios (TJs)
            '8' => [
                '01' => 'api_publica_tjac',  // TJAC
                '02' => 'api_publica_tjal',  // TJAL
                '03' => 'api_publica_tjap',  // TJAP
                '04' => 'api_publica_tjam',  // TJAM
                '05' => 'api_publica_tjba',  // TJBA
                '06' => 'api_publica_tjce',  // TJCE
                '07' => 'api_publica_tjdft', // TJDFT
                '08' => 'api_publica_tjes',  // TJES
                '09' => 'api_publica_tjgo',  // TJGO
                '10' => 'api_publica_tjma',  // TJMA
                '11' => 'api_publica_tjmt',  // TJMT
                '12' => 'api_publica_tjms',  // TJMS
                '13' => 'api_publica_tjmg',  // TJMG
                '14' => 'api_publica_tjpa',  // TJPA
                '15' => 'api_publica_tjpb',  // TJPB
                '16' => 'api_publica_tjpr',  // TJPR
                '17' => 'api_publica_tjpe',  // TJPE
                '18' => 'api_publica_tjpi',  // TJPI
                '19' => 'api_publica_tjrj',  // TJRJ
                '20' => 'api_publica_tjrn',  // TJRN
                '21' => 'api_publica_tjrs',  // TJRS
                '22' => 'api_publica_tjro',  // TJRO
                '23' => 'api_publica_tjrr',  // TJRR
                '24' => 'api_publica_tjsc',  // TJSC
                '25' => 'api_publica_tjse',  // TJSE
                '26' => 'api_publica_tjsp',  // TJSP
                '27' => 'api_publica_tjto',  // TJTO
            ],
            
            // 9 - Justiça Militar Estadual (TJMs)
            '9' => [
                '13' => 'api_publica_tjmmg', // TJM-MG
                '21' => 'api_publica_tjmrs', // TJM-RS
                '26' => 'api_publica_tjmsp', // TJM-SP
            ],
        ];
    }

    /**
     * Obter URL completa do endpoint
     */
    public static function getUrlCompleta(string $segmento, string $tribunal): ?string
    {
        $endpoint = self::getEndpoint($segmento, $tribunal);
        
        if (!$endpoint) {
            return null;
        }
        
        return "https://api-publica.datajud.cnj.jus.br/{$endpoint}/_search";
    }
}

