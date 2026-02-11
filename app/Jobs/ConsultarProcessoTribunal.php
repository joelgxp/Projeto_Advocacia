<?php

namespace App\Jobs;

use App\Models\Processo;
use App\Services\ConsultaProcessualService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ConsultarProcessoTribunal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Processo $processo
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ConsultaProcessualService $service): void
    {
        try {
            Log::info('Iniciando consulta processual', [
                'processo_id' => $this->processo->id,
                'numero_processo' => $this->processo->numero_processo,
            ]);

            $resultado = $service->consultarProcesso($this->processo->numero_processo);

            if ($resultado) {
                $this->processo->update([
                    'ultima_movimentacao' => $resultado['ultima_movimentacao'] ?? null,
                    'ultima_movimentacao_data' => $resultado['data_movimentacao'] ?? null,
                ]);

                Log::info('Consulta processual concluída', [
                    'processo_id' => $this->processo->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro na consulta processual', [
                'processo_id' => $this->processo->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de consulta processual falhou após todas as tentativas', [
            'processo_id' => $this->processo->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
