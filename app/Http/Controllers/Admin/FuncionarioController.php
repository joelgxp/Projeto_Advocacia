<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FuncionarioController extends Controller
{
    public function index()
    {
        $funcionarios = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['recepcionista', 'tesoureiro']);
        })->latest()->paginate(15);
        
        return view('admin.funcionarios.index', compact('funcionarios'));
    }

    public function create()
    {
        $cargos = Cargo::where('ativo', true)->get();
        
        return view('admin.funcionarios.create', compact('cargos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'cpf' => 'required|string|max:14|unique:users',
            'password' => 'required|string|min:6',
            'telefone' => 'nullable|string|max:20',
            'cargo_id' => 'required|exists:cargos,id',
        ]);

        $cargo = Cargo::find($validated['cargo_id']);
        $roleName = $this->getRoleByCargo($cargo->nome);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'password' => Hash::make($validated['password']),
            'telefone' => $validated['telefone'] ?? null,
        ]);

        $user->assignRole($roleName);

        return redirect()->route('admin.funcionarios.index')
            ->with('success', 'Funcionário criado com sucesso!');
    }

    public function edit(User $funcionario)
    {
        $cargos = Cargo::where('ativo', true)->get();
        
        return view('admin.funcionarios.edit', compact('funcionario', 'cargos'));
    }

    public function update(Request $request, User $funcionario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $funcionario->id,
            'cpf' => 'required|string|max:14|unique:users,cpf,' . $funcionario->id,
            'password' => 'nullable|string|min:6',
            'telefone' => 'nullable|string|max:20',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $funcionario->update($userData);

        return redirect()->route('admin.funcionarios.index')
            ->with('success', 'Funcionário atualizado com sucesso!');
    }

    public function destroy(User $funcionario)
    {
        $funcionario->delete();

        return redirect()->route('admin.funcionarios.index')
            ->with('success', 'Funcionário excluído com sucesso!');
    }

    private function getRoleByCargo($cargoNome)
    {
        $map = [
            'Recepcionista' => 'recepcionista',
            'Tesoureiro' => 'tesoureiro',
        ];

        return $map[$cargoNome] ?? 'recepcionista';
    }
}




