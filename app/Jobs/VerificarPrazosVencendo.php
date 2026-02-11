<?php

namespace App\Jobs;

use App\Events\PrazoVencendo;
use App\Models\Prazo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerificarPrazosVencendo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $prazos = Prazo::whereNull('data_cumprimento')
                ->where('data_vencimento', '>=', now())
                ->where('data_vencimento', '<=', now()->addDays(7))
                ->with('processo.advogado')
                ->get();

            foreach ($prazos as $prazo) {
                $diasRestantes = now()->diffInDays($prazo->data_vencimento, false);

                // Notificar em 7, 3, 1 e 0 dias
                if (in_array($diasRestantes, [7, 3, 1, 0])) {
                    event(new PrazoVencendo($prazo, $diasRestantes));
                }
            }

            Log::info('Verificação de prazos concluída', [
                'prazos_encontrados' => $prazos->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar prazos vencendo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
