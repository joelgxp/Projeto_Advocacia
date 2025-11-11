<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\Cliente;
use App\Models\Advogado;
use App\Models\Vara;
use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProcessoController extends Controller
{

    public function index()
    {
        $processos = Processo::with(['cliente:id,nome', 'advogado.user:id,name', 'vara:id,nome', 'especialidade:id,nome'])
            ->latest()
            ->paginate(15);
        
        return view('admin.processos.index', compact('processos'));
    }

    public function create()
    {
        // Cache de listas estáticas por 1 hora
        $clientes = Cache::remember('clientes.ativos', 3600, function () {
            return Cliente::where('ativo', true)
                ->select('id', 'nome', 'cpf_cnpj')
                ->orderBy('nome')
                ->get();
        });
        
        $advogados = Cache::remember('advogados.ativos', 3600, function () {
            return Advogado::where('ativo', true)
                ->with('user:id,name,email')
                ->select('id', 'user_id', 'oab')
                ->get();
        });
        
        $varas = Cache::remember('varas.ativas', 3600, function () {
            return Vara::where('ativo', true)
                ->select('id', 'nome', 'comarca')
                ->orderBy('nome')
                ->get();
        });
        
        $especialidades = Cache::remember('especialidades.ativas', 3600, function () {
            return Especialidade::where('ativo', true)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get();
        });
        
        return view('admin.processos.create', compact('clientes', 'advogados', 'varas', 'especialidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_processo' => 'nullable|string|max:50|unique:processos',
            'cliente_id' => 'required|exists:clientes,id',
            'advogado_id' => 'required|exists:advogados,id',
            'vara_id' => 'required|exists:varas,id',
            'especialidade_id' => 'required|exists:especialidades,id',
            'status' => 'required|in:aberto,andamento,concluido,arquivado,cancelado',
            'data_abertura' => 'required|date',
        ]);

        Processo::create($validated);
        
        // Limpar cache do dashboard
        Cache::forget('admin.dashboard.stats.' . auth()->id());

        return redirect()->route('admin.processos.index')
            ->with('success', 'Processo criado com sucesso!');
    }

    public function show(Processo $processo)
    {
        $processo->load(['cliente', 'advogado', 'vara', 'especialidade', 'documentos', 'audiencias', 'prazos']);
        
        return view('admin.processos.show', compact('processo'));
    }

    public function edit(Processo $processo)
    {
        // Cache de listas estáticas por 1 hora
        $clientes = Cache::remember('clientes.ativos', 3600, function () {
            return Cliente::where('ativo', true)
                ->select('id', 'nome', 'cpf_cnpj')
                ->orderBy('nome')
                ->get();
        });
        
        $advogados = Cache::remember('advogados.ativos', 3600, function () {
            return Advogado::where('ativo', true)
                ->with('user:id,name,email')
                ->select('id', 'user_id', 'oab')
                ->get();
        });
        
        $varas = Cache::remember('varas.ativas', 3600, function () {
            return Vara::where('ativo', true)
                ->select('id', 'nome', 'comarca')
                ->orderBy('nome')
                ->get();
        });
        
        $especialidades = Cache::remember('especialidades.ativas', 3600, function () {
            return Especialidade::where('ativo', true)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get();
        });
        
        return view('admin.processos.edit', compact('processo', 'clientes', 'advogados', 'varas', 'especialidades'));
    }

    public function update(Request $request, Processo $processo)
    {
        $validated = $request->validate([
            'numero_processo' => 'nullable|string|max:50|unique:processos,numero_processo,' . $processo->id,
            'cliente_id' => 'required|exists:clientes,id',
            'advogado_id' => 'required|exists:advogados,id',
            'vara_id' => 'required|exists:varas,id',
            'especialidade_id' => 'required|exists:especialidades,id',
            'status' => 'required|in:aberto,andamento,concluido,arquivado,cancelado',
            'data_abertura' => 'required|date',
        ]);

        $processo->update($validated);
        
        // Limpar cache do dashboard
        Cache::forget('admin.dashboard.stats.' . auth()->id());

        return redirect()->route('admin.processos.index')
            ->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy(Processo $processo)
    {
        $processo->delete();
        
        // Limpar cache do dashboard
        Cache::forget('admin.dashboard.stats.' . auth()->id());

        return redirect()->route('admin.processos.index')
            ->with('success', 'Processo excluído com sucesso!');
    }
}

