<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Comunicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComunicacaoController extends Controller
{
    public function index()
    {
        $cliente = \App\Models\Cliente::where('cpf_cnpj', Auth::user()->cpf)->first();
        
        if (!$cliente) {
            return view('cliente.comunicacoes.index', ['comunicacoes' => collect()]);
        }

        $comunicacoes = $cliente->comunicacoes()->latest()->paginate(15);
        
        return view('cliente.comunicacoes.index', compact('comunicacoes'));
    }

    public function store(Request $request)
    {
        $cliente = \App\Models\Cliente::where('cpf_cnpj', Auth::user()->cpf)->first();
        
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        }

        $validated = $request->validate([
            'processo_id' => 'nullable|exists:processos,id',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string',
        ]);

        Comunicacao::create([
            'cliente_id' => $cliente->id,
            'processo_id' => $validated['processo_id'] ?? null,
            'user_id' => Auth::id(),
            'tipo' => 'sistema',
            'assunto' => $validated['assunto'],
            'mensagem' => $validated['mensagem'],
            'direcao' => 'enviada',
        ]);

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
    }
}





