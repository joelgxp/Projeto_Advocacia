<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use App\Models\ContaReceber;
use Illuminate\Http\Request;

class ReceberController extends Controller
{
    public function index()
    {
        $contasReceber = ContaReceber::with(['cliente', 'processo', 'advogado'])
            ->latest()
            ->paginate(15);
        
        return view('recepcao.receber.index', compact('contasReceber'));
    }

    public function concluir(ContaReceber $receber)
    {
        $receber->update([
            'status' => 'pago',
            'data_pagamento' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Conta marcada como paga!');
    }
}



