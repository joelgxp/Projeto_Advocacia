<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index()
    {
        return view('recepcao.pagamentos.index');
    }
}




