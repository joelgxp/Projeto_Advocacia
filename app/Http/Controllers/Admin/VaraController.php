<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vara;
use Illuminate\Http\Request;

class VaraController extends Controller
{
    public function index()
    {
        $varas = Vara::latest()->paginate(15);
        
        return view('admin.varas.index', compact('varas'));
    }

    public function create()
    {
        return view('admin.varas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'comarca' => 'nullable|string|max:255',
            'tribunal' => 'nullable|string|max:255',
            'endereco' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
        ]);

        Vara::create($validated);

        return redirect()->route('admin.varas.index')
            ->with('success', 'Vara criada com sucesso!');
    }

    public function edit(Vara $vara)
    {
        return view('admin.varas.edit', compact('vara'));
    }

    public function update(Request $request, Vara $vara)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'comarca' => 'nullable|string|max:255',
            'tribunal' => 'nullable|string|max:255',
            'endereco' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
        ]);

        $vara->update($validated);

        return redirect()->route('admin.varas.index')
            ->with('success', 'Vara atualizada com sucesso!');
    }

    public function destroy(Vara $vara)
    {
        $vara->delete();

        return redirect()->route('admin.varas.index')
            ->with('success', 'Vara excluída com sucesso!');
    }
}



