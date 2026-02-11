<?php

namespace Tests\Unit\Rules;

use App\Rules\ValidCNJNumber;
use PHPUnit\Framework\TestCase;

class ValidCNJNumberTest extends TestCase
{
    private ValidCNJNumber $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new ValidCNJNumber();
    }

    public function test_valida_numero_cnj_correto_com_formatacao(): void
    {
        $numero = '1500345-31.2017.8.26.0248';
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'Número CNJ válido foi rejeitado');
    }

    public function test_valida_numero_cnj_correto_sem_formatacao(): void
    {
        $numero = '15003453120178260248';
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'Número CNJ válido foi rejeitado');
    }

    public function test_rejeita_numero_cnj_com_digito_verificador_incorreto(): void
    {
        $numero = '1500345-34.2017.8.26.0248'; // DV incorreto (deveria ser 31)
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'Número CNJ inválido foi aceito');
    }

    public function test_rejeita_numero_com_tamanho_incorreto(): void
    {
        $numero = '1500345-31.2017.8.26.024'; // Faltando um dígito
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'Número com tamanho incorreto foi aceito');
    }

    public function test_rejeita_numero_nao_numerico(): void
    {
        $numero = '1500345-AB.2017.8.26.0248';
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'Número não numérico foi aceito');
    }

    public function test_rejeita_string_vazia(): void
    {
        $numero = '';
        $failed = false;

        $this->rule->validate('numero_processo', $numero, function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, 'String vazia foi aceita');
    }
}
