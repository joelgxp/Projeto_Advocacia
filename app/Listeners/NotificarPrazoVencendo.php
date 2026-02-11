<?php

namespace App\Listeners;

use App\Events\PrazoVencendo;
use App\Models\Notificacao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotificarPrazoVencendo implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PrazoVencendo $event): void
    {
        try {
            $prazo = $event->prazo;
            $processo = $prazo->processo;

            $mensagem = $event->diasRestantes === 0
                ? "Prazo vence HOJE!"
                : "Prazo vence em {$event->diasRestantes} dia(s)!";

            Notificacao::create([
                'user_id' => $processo->advogado->user_id ?? null,
                'titulo' => 'Prazo Vencendo',
                'mensagem' => "{$mensagem} Processo: {$processo->numero_processo}",
                'tipo' => 'prazo',
                'data' => now(),
                'lida' => false,
            ]);

            Log::info('Notificação de prazo vencendo enviada', [
                'prazo_id' => $prazo->id,
                'processo_id' => $processo->id,
                'dias_restantes' => $event->diasRestantes,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao notificar prazo vencendo', [
                'error' => $e->getMessage(),
                'prazo_id' => $event->prazo->id,
            ]);
        }
    }
}
