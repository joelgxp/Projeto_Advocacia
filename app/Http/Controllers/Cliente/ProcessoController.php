<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessoController extends Controller
{
    public function index()
    {
        $cliente = \App\Models\Cliente::where('cpf_cnpj', Auth::user()->cpf)->first();
        
        if (!$cliente) {
            return view('cliente.processos.index', ['processos' => collect()]);
        }

        $processos = $cliente->processos()
            ->with(['advogado.user:id,name', 'vara:id,nome', 'especialidade:id,nome'])
            ->select('id', 'numero_processo', 'cliente_id', 'advogado_id', 'vara_id', 'especialidade_id', 'status', 'data_abertura', 'created_at')
            ->latest()
            ->paginate(15);
        
        return view('cliente.processos.index', compact('processos'));
    }

    public function show(Processo $processo)
    {
        $processo->load(['cliente', 'advogado', 'vara', 'especialidade', 'documentos', 'audiencias']);
        
        return view('cliente.processos.show', compact('processo'));
    }

    public function movimentacoes(Processo $processo)
    {
        $movimentacoes = $processo->movimentacoes()
            ->select('id', 'processo_id', 'titulo', 'descricao', 'data', 'created_at')
            ->latest()
            ->paginate(20);
        
        return view('cliente.processos.movimentacoes', compact('processo', 'movimentacoes'));
    }
}

