<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::select('id', 'nome', 'cpf_cnpj', 'tipo_pessoa', 'email', 'telefone', 'celular', 'ativo', 'created_at')
            ->latest()
            ->paginate(15);
        
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        // View não precisa de dados adicionais
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:18|unique:clientes',
            'tipo_pessoa' => 'required|in:PF,PJ',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
        ]);

        Cliente::create($validated);
        
        // Limpar cache de clientes ativos
        Cache::forget('clientes.ativos');
        Cache::forget('admin.dashboard.stats.' . auth()->id());

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['processos', 'documentos']);
        
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:18|unique:clientes,cpf_cnpj,' . $cliente->id,
            'tipo_pessoa' => 'required|in:PF,PJ',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
        ]);

        $cliente->update($validated);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}

