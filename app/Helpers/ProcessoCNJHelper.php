<?php

namespace App\Helpers;

class ProcessoCNJHelper
{
    /**
     * Validar formato do número de processo CNJ
     * Formato: NNNNNNN-DD.AAAA.J.TR.OOOO
     */
    public static function validarFormato(string $numeroProcesso): bool
    {
        // Remover espaços
        $numero = trim($numeroProcesso);
        
        // Padrão CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO
        $pattern = '/^(\d{7})-(\d{2})\.(\d{4})\.(\d{1})\.(\d{2})\.(\d{4})$/';
        
        return (bool) preg_match($pattern, $numero);
    }

    /**
     * Validar dígito verificador do processo CNJ
     */
    public static function validarDigitoVerificador(string $numeroProcesso): bool
    {
        if (!self::validarFormato($numeroProcesso)) {
            return false;
        }

        // Extrair partes do número
        $partes = self::extrairPartes($numeroProcesso);
        
        if (!$partes) {
            return false;
        }

        // Calcular dígito verificador
        $numeroSequencial = $partes['numero_sequencial'];
        $ano = $partes['ano'];
        $segmento = $partes['segmento'];
        $tribunal = $partes['tribunal'];
        $origem = $partes['origem'];

        // Concatenação para cálculo: NNNNNNN + AAAA + J + TR + OOOO
        $numeroCalculo = $numeroSequencial . $ano . $segmento . $tribunal . $origem;

        // Calcular dígito verificador (módulo 97)
        $resto = bcmod($numeroCalculo, '97');
        $digitoCalculado = 98 - (int) $resto;

        // Garantir 2 dígitos
        $digitoCalculado = str_pad($digitoCalculado, 2, '0', STR_PAD_LEFT);

        return $digitoCalculado === $partes['digito_verificador'];
    }

    /**
     * Extrair partes do número de processo CNJ
     * Retorna array com todas as partes ou null se inválido
     */
    public static function extrairPartes(string $numeroProcesso): ?array
    {
        // Limpar número (remover formatação)
        $numeroLimpo = preg_replace('/[^0-9]/', '', $numeroProcesso);

        // Verificar se tem 20 dígitos (7+2+4+1+2+4)
        if (strlen($numeroLimpo) !== 20) {
            return null;
        }

        return [
            'numero_sequencial' => substr($numeroLimpo, 0, 7),      // NNNNNNN
            'digito_verificador' => substr($numeroLimpo, 7, 2),     // DD
            'ano' => substr($numeroLimpo, 9, 4),                    // AAAA
            'segmento' => substr($numeroLimpo, 13, 1),              // J
            'tribunal' => substr($numeroLimpo, 14, 2),              // TR
            'origem' => substr($numeroLimpo, 16, 4),                // OOOO
            'numero_formatado' => self::formatarNumero($numeroLimpo),
            'numero_limpo' => $numeroLimpo,
        ];
    }

    /**
     * Formatar número de processo no padrão CNJ
     */
    public static function formatarNumero(string $numeroLimpo): string
    {
        if (strlen($numeroLimpo) !== 20) {
            return $numeroLimpo;
        }

        return sprintf(
            '%s-%s.%s.%s.%s.%s',
            substr($numeroLimpo, 0, 7),   // NNNNNNN
            substr($numeroLimpo, 7, 2),   // DD
            substr($numeroLimpo, 9, 4),   // AAAA
            substr($numeroLimpo, 13, 1),  // J
            substr($numeroLimpo, 14, 2),  // TR
            substr($numeroLimpo, 16, 4)   // OOOO
        );
    }

    /**
     * Extrair código do tribunal (TR) do número de processo
     */
    public static function extrairTribunal(string $numeroProcesso): ?string
    {
        $partes = self::extrairPartes($numeroProcesso);
        return $partes['tribunal'] ?? null;
    }

    /**
     * Extrair segmento da justiça (J) do número de processo
     */
    public static function extrairSegmento(string $numeroProcesso): ?string
    {
        $partes = self::extrairPartes($numeroProcesso);
        return $partes['segmento'] ?? null;
    }

    /**
     * Obter nome do segmento da justiça
     * Conforme tabela oficial do CNJ - Valor "J" (Segmento/O órgão do Judiciário)
     */
    public static function getNomeSegmento(string $segmento): string
    {
        return match($segmento) {
            '1' => 'Supremo Tribunal Federal (STF)',
            '2' => 'Conselho Nacional de Justiça (CNJ)',
            '3' => 'Superior Tribunal de Justiça (STJ)',
            '4' => 'Justiça Federal',
            '5' => 'Justiça do Trabalho',
            '6' => 'Justiça Eleitoral',
            '7' => 'Justiça Militar da União',
            '8' => 'Justiça dos Estados e do Distrito Federal e Territórios',
            '9' => 'Justiça Militar Estadual',
            default => 'Desconhecido',
        };
    }

    /**
     * Validar número de processo completo (formato + dígito verificador)
     */
    public static function validar(string $numeroProcesso): array
    {
        $resultado = [
            'valido' => false,
            'erros' => [],
            'partes' => null,
        ];

        // Validar formato
        if (!self::validarFormato($numeroProcesso)) {
            $resultado['erros'][] = 'Formato inválido. Use o padrão: NNNNNNN-DD.AAAA.J.TR.OOOO';
            return $resultado;
        }

        // Extrair partes
        $partes = self::extrairPartes($numeroProcesso);
        if (!$partes) {
            $resultado['erros'][] = 'Não foi possível extrair as partes do número de processo.';
            return $resultado;
        }

        $resultado['partes'] = $partes;

        // Validar dígito verificador
        if (!self::validarDigitoVerificador($numeroProcesso)) {
            $resultado['erros'][] = 'Dígito verificador inválido.';
            return $resultado;
        }

        // Validar ano (não pode ser futuro)
        $anoAtual = (int) date('Y');
        $anoProcesso = (int) $partes['ano'];
        if ($anoProcesso > $anoAtual) {
            $resultado['erros'][] = 'Ano do processo não pode ser futuro.';
            return $resultado;
        }

        // Validar ano mínimo (ex: 1900)
        if ($anoProcesso < 1900) {
            $resultado['erros'][] = 'Ano do processo inválido.';
            return $resultado;
        }

        $resultado['valido'] = true;
        return $resultado;
    }

    /**
     * Normalizar número de processo (aceita com ou sem formatação)
     */
    public static function normalizar(string $numeroProcesso): ?string
    {
        // Remover tudo exceto números
        $numeroLimpo = preg_replace('/[^0-9]/', '', $numeroProcesso);

        // Verificar se tem 20 dígitos
        if (strlen($numeroLimpo) !== 20) {
            return null;
        }

        // Retornar formatado
        return self::formatarNumero($numeroLimpo);
    }

    /**
     * Obter número limpo (apenas dígitos) para API
     */
    public static function getNumeroLimpo(string $numeroProcesso): ?string
    {
        $partes = self::extrairPartes($numeroProcesso);
        return $partes['numero_limpo'] ?? null;
    }
}

