<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{
    public function index()
    {
        $especialidades = Especialidade::latest()->paginate(15);
        
        return view('admin.especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        return view('admin.especialidades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Especialidade::create($validated);

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidade criada com sucesso!');
    }

    public function edit(Especialidade $especialidade)
    {
        return view('admin.especialidades.edit', compact('especialidade'));
    }

    public function update(Request $request, Especialidade $especialidade)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $especialidade->update($validated);

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidade atualizada com sucesso!');
    }

    public function destroy(Especialidade $especialidade)
    {
        $especialidade->delete();

        return redirect()->route('admin.especialidades.index')
            ->with('success', 'Especialidade excluída com sucesso!');
    }
}


