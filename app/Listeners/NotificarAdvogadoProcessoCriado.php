<?php

namespace App\Listeners;

use App\Events\ProcessoCriado;
use App\Models\Notificacao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotificarAdvogadoProcessoCriado implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ProcessoCriado $event): void
    {
        try {
            $processo = $event->processo;

            Notificacao::create([
                'user_id' => $processo->advogado->user_id ?? null,
                'titulo' => 'Novo Processo Atribuído',
                'mensagem' => "Processo {$processo->numero_processo} foi atribuído a você.",
                'tipo' => 'processo',
                'data' => now(),
                'lida' => false,
            ]);

            Log::info('Notificação de processo criado enviada', [
                'processo_id' => $processo->id,
                'advogado_id' => $processo->advogado_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao notificar advogado sobre processo criado', [
                'error' => $e->getMessage(),
                'processo_id' => $event->processo->id,
            ]);
        }
    }
}
