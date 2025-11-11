<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index()
    {
        $advogado = Auth::user()->advogado;
        
        if (!$advogado) {
            return view('advogado.clientes.index', ['clientes' => collect()]);
        }

        $clientes = Cliente::whereHas('processos', function($query) use ($advogado) {
            $query->where('advogado_id', $advogado->id);
        })->latest()->paginate(15);
        
        return view('advogado.clientes.index', compact('clientes'));
    }

    public function buscarNome(Request $request)
    {
        $termo = $request->get('termo');
        
        $clientes = Cliente::where('nome', 'like', "%{$termo}%")
            ->orWhere('cpf_cnpj', 'like', "%{$termo}%")
            ->limit(10)
            ->get(['id', 'nome', 'cpf_cnpj']);
        
        return response()->json($clientes);
    }

    public function create()
    {
        return view('advogado.clientes.create');
    }

    public function store(Request $request)
    {
        // Implementar store
    }

    public function show(Cliente $cliente)
    {
        return view('advogado.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('advogado.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        // Implementar update
    }

    public function destroy(Cliente $cliente)
    {
        // Implementar destroy
    }
}


