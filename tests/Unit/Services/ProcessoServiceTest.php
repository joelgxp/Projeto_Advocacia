<?php

namespace Tests\Unit\Services;

use App\Enums\ProcessoStatus;
use App\Models\Advogado;
use App\Models\Cliente;
use App\Models\Processo;
use App\Models\Vara;
use App\Services\ProcessoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessoServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProcessoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProcessoService();
    }

    public function test_criar_processo_com_dados_validos(): void
    {
        $cliente = Cliente::factory()->create();
        $advogado = Advogado::factory()->create();

        $data = [
            'numero_processo' => '1500345-31.2017.8.26.0248',
            'cliente_id' => $cliente->id,
            'advogado_id' => $advogado->id,
            'status' => ProcessoStatus::ANDAMENTO->value,
            'data_abertura' => now()->subDays(10),
        ];

        $processo = $this->service->criarProcesso($data);

        $this->assertInstanceOf(Processo::class, $processo);
        $this->assertEquals($data['numero_processo'], $processo->numero_processo);
        $this->assertEquals($cliente->id, $processo->cliente_id);
        $this->assertDatabaseHas('processos', [
            'numero_processo' => $data['numero_processo'],
        ]);
    }

    public function test_nao_permite_criar_processo_duplicado(): void
    {
        $cliente = Cliente::factory()->create();
        $advogado = Advogado::factory()->create();

        $numeroProcesso = '1500345-31.2017.8.26.0248';

        Processo::factory()->create([
            'numero_processo' => $numeroProcesso,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Processo com este número já existe');

        $this->service->criarProcesso([
            'numero_processo' => $numeroProcesso,
            'cliente_id' => $cliente->id,
            'advogado_id' => $advogado->id,
            'status' => ProcessoStatus::ANDAMENTO->value,
            'data_abertura' => now(),
        ]);
    }

    public function test_atualizar_processo(): void
    {
        $processo = Processo::factory()->create();

        $novosDados = [
            'observacoes' => 'Observação atualizada',
        ];

        $processoAtualizado = $this->service->atualizarProcesso($processo, $novosDados);

        $this->assertEquals('Observação atualizada', $processoAtualizado->observacoes);
    }

    public function test_alterar_status_processo(): void
    {
        $processo = Processo::factory()->create([
            'status' => ProcessoStatus::ANDAMENTO,
        ]);

        $resultado = $this->service->alterarStatus(
            $processo,
            ProcessoStatus::ARQUIVADO,
            'Processo finalizado'
        );

        $this->assertTrue($resultado);
        $processo->refresh();
        $this->assertEquals(ProcessoStatus::ARQUIVADO, $processo->status);
    }

    public function test_buscar_processos_ativos(): void
    {
        Processo::factory()->count(3)->create([
            'status' => ProcessoStatus::ANDAMENTO,
        ]);

        Processo::factory()->count(2)->create([
            'status' => ProcessoStatus::ARQUIVADO,
        ]);

        $processosAtivos = $this->service->buscarProcessosAtivos();

        $this->assertCount(3, $processosAtivos);
    }

    public function test_contar_processos_ativos(): void
    {
        Processo::factory()->count(5)->create([
            'status' => ProcessoStatus::ANDAMENTO,
        ]);

        Processo::factory()->count(3)->create([
            'status' => ProcessoStatus::SUSPENSO,
        ]);

        $count = $this->service->contarProcessosAtivos();

        $this->assertEquals(5, $count);
    }

    public function test_excluir_processo(): void
    {
        $processo = Processo::factory()->create();
        $processoId = $processo->id;

        $resultado = $this->service->excluirProcesso($processo);

        $this->assertTrue($resultado);
        $this->assertSoftDeleted('processos', ['id' => $processoId]);
    }
}
