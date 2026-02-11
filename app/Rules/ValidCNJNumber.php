<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validação de número CNJ de processos judiciais
 * 
 * Formato: NNNNNNN-DD.AAAA.J.TR.OOOO
 * - NNNNNNN: número sequencial (7 dígitos)
 * - DD: dígito verificador (2 dígitos)
 * - AAAA: ano de início (4 dígitos)
 * - J: segmento da justiça (1 dígito)
 * - TR: tribunal (2 dígitos)
 * - OOOO: vara/origem (4 dígitos)
 * 
 * Baseado na Resolução CNJ nº 65/2008
 */
class ValidCNJNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidCNJNumber($value)) {
            $fail('O número do processo não é válido segundo o padrão CNJ.');
        }
    }

    /**
     * Valida o número CNJ do processo
     */
    private function isValidCNJNumber(string $numero): bool
    {
        // Remove pontos e traços
        $numeroProcesso = preg_replace('/[.-]/', '', $numero);

        // Verifica se tem 20 dígitos e se é numérico
        if (strlen($numeroProcesso) !== 20 || !ctype_digit($numeroProcesso)) {
            return false;
        }

        // Extrai os componentes
        $numeroSequencial = substr($numeroProcesso, 0, 7);
        $digitoVerificador = (int) substr($numeroProcesso, 7, 2);
        $anoInicio = substr($numeroProcesso, 9, 4);
        $segmento = substr($numeroProcesso, 13, 1);
        $tribunal = substr($numeroProcesso, 14, 2);
        $origem = substr($numeroProcesso, 16, 4);

        // Calcula o dígito verificador
        $resto = $this->bcmod($numeroSequencial . $anoInicio . $segmento . $tribunal . $origem . '00', '97');
        $digitoCalculado = 98 - $resto;

        return $digitoVerificador === $digitoCalculado;
    }

    /**
     * Implementação de módulo para números grandes (bcmod)
     */
    private function bcmod(string $x, string $y): int
    {
        $take = 5;
        $mod = '';

        do {
            $a = (int) ($mod . substr($x, 0, $take));
            $x = substr($x, $take);
            $mod = $a % (int) $y;
        } while (strlen($x));

        return $mod;
    }
}
