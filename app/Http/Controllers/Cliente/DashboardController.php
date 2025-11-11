<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cliente = Auth::user()->cliente ?? null;
        
        if (!$cliente) {
            // Buscar cliente pelo CPF do usuário
            $cliente = \App\Models\Cliente::where('cpf_cnpj', Auth::user()->cpf)->first();
        }

        $processos = $cliente ? $cliente->processos()->latest()->get() : collect();

        return view('cliente.dashboard', compact('processos', 'cliente'));
    }
}

