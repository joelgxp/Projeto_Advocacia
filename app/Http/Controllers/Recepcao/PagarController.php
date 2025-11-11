<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use App\Models\ContaPagar;
use Illuminate\Http\Request;

class PagarController extends Controller
{
    public function index()
    {
        $contasPagar = ContaPagar::latest()->paginate(15);
        
        return view('recepcao.pagar.index', compact('contasPagar'));
    }
}


