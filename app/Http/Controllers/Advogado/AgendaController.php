<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
        
        return view('advogado.agenda.index', compact('tarefas'));
    }
}


