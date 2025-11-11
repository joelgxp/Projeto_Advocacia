<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\Cliente;
use App\Models\Prazo;
use App\Models\Audiencia;
use App\Enums\ProcessoStatus;
use App\Enums\PrazoStatus;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache de estatísticas por 5 minutos
        $cacheKey = 'admin.dashboard.stats.' . auth()->id();
        
        $stats = Cache::remember($cacheKey, 300, function () {
            return [
                'processosAtivos' => Processo::where('status', ProcessoStatus::ANDAMENTO->value)->count(),
                'totalClientes' => Cliente::count(),
                'prazosProximos' => Prazo::where('status', PrazoStatus::PENDENTE->value)
                    ->where('data_vencimento', '<=', now()->addDays(7))
                    ->count(),
                'audienciasProximas' => Audiencia::where('status', 'agendada')
                    ->where('data', '>=', now())
                    ->where('data', '<=', now()->addDays(30))
                    ->count(),
            ];
        });

        return view('admin.dashboard', $stats);
    }
}

