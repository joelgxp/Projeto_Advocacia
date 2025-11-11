<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::latest()->paginate(15);
        
        return view('admin.cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('admin.cargos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Cargo::create($validated);

        return redirect()->route('admin.cargos.index')
            ->with('success', 'Cargo criado com sucesso!');
    }

    public function edit(Cargo $cargo)
    {
        return view('admin.cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $cargo->update($validated);

        return redirect()->route('admin.cargos.index')
            ->with('success', 'Cargo atualizado com sucesso!');
    }

    public function destroy(Cargo $cargo)
    {
        $cargo->delete();

        return redirect()->route('admin.cargos.index')
            ->with('success', 'Cargo excluído com sucesso!');
    }
}


