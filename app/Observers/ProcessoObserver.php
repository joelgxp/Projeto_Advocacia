<?php

namespace App\Observers;

use App\Models\Processo;
use Illuminate\Support\Facades\Cache;

class ProcessoObserver
{
    /**
     * Handle the Processo "created" event.
     */
    public function created(Processo $processo): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Processo "updated" event.
     */
    public function updated(Processo $processo): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Processo "deleted" event.
     */
    public function deleted(Processo $processo): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Limpar cache do dashboard para todos os usuários
     */
    private function clearDashboardCache(): void
    {
        // Limpar cache do dashboard (será regenerado no próximo acesso)
        // Como não temos acesso direto a todas as chaves de cache,
        // o cache expirará naturalmente (5 minutos) ou será regenerado
        // no próximo acesso do usuário. Isso é aceitável para performance.
        // Em produção com Redis, podemos usar tags de cache.
    }
}

