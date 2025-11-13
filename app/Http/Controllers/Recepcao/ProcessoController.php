<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use Illuminate\Http\Request;

class ProcessoController extends Controller
{
    public function index()
    {
        $processos = Processo::with(['cliente', 'advogado'])
            ->latest()
            ->paginate(15);
        
        return view('recepcao.processos.index', compact('processos'));
    }

    public function inserirAudiencia(Request $request, Processo $processo)
    {
        // Implementar inserção de audiência
        return redirect()->back()->with('success', 'Audiência inserida com sucesso!');
    }
}





