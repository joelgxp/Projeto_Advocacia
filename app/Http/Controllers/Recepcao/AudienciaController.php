<?php

namespace App\Http\Controllers\Recepcao;

use App\Http\Controllers\Controller;
use App\Models\Audiencia;
use Illuminate\Http\Request;

class AudienciaController extends Controller
{
    public function index()
    {
        $audiencias = Audiencia::with(['processo', 'cliente', 'advogado'])
            ->latest()
            ->paginate(15);
        
        return view('recepcao.audiencias.index', compact('audiencias'));
    }
}




