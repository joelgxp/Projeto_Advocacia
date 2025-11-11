<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advogado;
use App\Models\User;
use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AdvogadoController extends Controller
{
    public function index()
    {
        $advogados = Advogado::with('user:id,name,email')
            ->select('id', 'user_id', 'oab', 'ativo', 'created_at')
            ->latest()
            ->paginate(15);
        
        return view('admin.advogados.index', compact('advogados'));
    }

    public function create()
    {
        $especialidades = \Illuminate\Support\Facades\Cache::remember('especialidades.ativas', 3600, function () {
            return Especialidade::where('ativo', true)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get();
        });
        
        return view('admin.advogados.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'cpf' => 'required|string|max:14|unique:users',
            'password' => 'required|string|min:6',
            'oab' => 'nullable|string|max:20|unique:advogados',
            'telefone' => 'nullable|string|max:20',
            'biografia' => 'nullable|string',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'password' => Hash::make($validated['password']),
            'telefone' => $validated['telefone'] ?? null,
        ]);

        $user->assignRole('advogado');

        // Criar advogado
        $advogado = Advogado::create([
            'user_id' => $user->id,
            'oab' => $validated['oab'] ?? null,
            'biografia' => $validated['biografia'] ?? null,
        ]);

        // Vincular especialidades
        if ($request->has('especialidades')) {
            $advogado->especialidades()->sync($request->especialidades);
        }

        // Limpar cache
        Cache::forget('advogados.ativos');
        Cache::forget('especialidades.ativas');
        
        return redirect()->route('admin.advogados.index')
            ->with('success', 'Advogado criado com sucesso!');
    }

    public function show(Advogado $advogado)
    {
        $advogado->load(['user', 'especialidades', 'processos']);
        
        return view('admin.advogados.show', compact('advogado'));
    }

    public function edit(Advogado $advogado)
    {
        $especialidades = Cache::remember('especialidades.ativas', 3600, function () {
            return Especialidade::where('ativo', true)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get();
        });
        $advogado->load(['user:id,name,email,cpf,telefone', 'especialidades:id,nome']);
        
        return view('admin.advogados.edit', compact('advogado', 'especialidades'));
    }

    public function update(Request $request, Advogado $advogado)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $advogado->user_id,
            'cpf' => 'required|string|max:14|unique:users,cpf,' . $advogado->user_id,
            'password' => 'nullable|string|min:6',
            'oab' => 'nullable|string|max:20|unique:advogados,oab,' . $advogado->id,
            'telefone' => 'nullable|string|max:20',
            'biografia' => 'nullable|string',
        ]);

        // Atualizar usuário
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $advogado->user->update($userData);

        // Atualizar advogado
        $advogado->update([
            'oab' => $validated['oab'] ?? null,
            'biografia' => $validated['biografia'] ?? null,
        ]);

        // Atualizar especialidades
        if ($request->has('especialidades')) {
            $advogado->especialidades()->sync($request->especialidades);
        }

        // Limpar cache
        Cache::forget('advogados.ativos');
        Cache::forget('especialidades.ativas');
        
        return redirect()->route('admin.advogados.index')
            ->with('success', 'Advogado atualizado com sucesso!');
    }

    public function destroy(Advogado $advogado)
    {
        $advogado->delete();
        
        // Limpar cache
        Cache::forget('advogados.ativos');

        return redirect()->route('admin.advogados.index')
            ->with('success', 'Advogado excluído com sucesso!');
    }
}

