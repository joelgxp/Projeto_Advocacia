<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessoController extends Controller
{
    public function index()
    {
        $advogado = Auth::user()->advogado;
        
        if (!$advogado) {
            return view('advogado.processos.index', ['processos' => collect()]);
        }

        $processos = Processo::where('advogado_id', $advogado->id)
            ->with(['cliente:id,nome,cpf_cnpj', 'vara:id,nome', 'especialidade:id,nome'])
            ->select('id', 'numero_processo', 'cliente_id', 'advogado_id', 'vara_id', 'especialidade_id', 'status', 'data_abertura', 'created_at')
            ->latest()
            ->paginate(15);
        
        return view('advogado.processos.index', compact('processos'));
    }

    public function create()
    {
        // Implementar criação de processo
        return view('advogado.processos.create');
    }

    public function store(Request $request)
    {
        // Implementar store
    }

    public function show(Processo $processo)
    {
        $processo->load(['cliente', 'vara', 'especialidade', 'documentos', 'audiencias', 'prazos']);
        
        return view('advogado.processos.show', compact('processo'));
    }

    public function edit(Processo $processo)
    {
        return view('advogado.processos.edit', compact('processo'));
    }

    public function update(Request $request, Processo $processo)
    {
        // Implementar update
    }

    public function destroy(Processo $processo)
    {
        // Implementar destroy
    }

    public function concluir(Processo $processo)
    {
        $processo->update(['status' => 'concluido']);
        
        return redirect()->back()->with('success', 'Processo concluído com sucesso!');
    }

    public function observacao(Request $request, Processo $processo)
    {
        $processo->update(['observacoes' => $request->observacoes]);
        
        return redirect()->back()->with('success', 'Observação adicionada com sucesso!');
    }

    public function cobranca(Processo $processo)
    {
        return view('advogado.processos.cobranca', compact('processo'));
    }
}

