<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Processo;
use App\Models\Prazo;
use App\Models\Audiencia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $advogado = Auth::user()->advogado;
        
        if (!$advogado) {
            return redirect()->route('advogado.processos.index')
                ->with('error', 'Perfil de advogado não encontrado.');
        }

        $processosAtivos = Processo::where('advogado_id', $advogado->id)
            ->where('status', 'andamento')
            ->count();
        
        $prazosProximos = Prazo::whereHas('processo', function($query) use ($advogado) {
            $query->where('advogado_id', $advogado->id);
        })
        ->where('status', 'pendente')
        ->where('data_vencimento', '<=', now()->addDays(7))
        ->count();
        
        $audienciasProximas = Audiencia::where('advogado_id', $advogado->id)
            ->where('status', 'agendada')
            ->where('data', '>=', now())
            ->where('data', '<=', now()->addDays(30))
            ->count();

        return view('advogado.dashboard', compact(
            'processosAtivos',
            'prazosProximos',
            'audienciasProximas'
        ));
    }
}



