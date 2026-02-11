<?php

namespace App\Events;

use App\Models\Prazo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrazoVencendo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Prazo $prazo,
        public int $diasRestantes
    ) {}
}
