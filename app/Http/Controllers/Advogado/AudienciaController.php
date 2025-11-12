<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Audiencia;
use Illuminate\Http\Request;

class AudienciaController extends Controller
{
    public function index()
    {
        $audiencias = Audiencia::with(['processo', 'cliente'])
            ->latest()
            ->paginate(15);
        
        return view('advogado.audiencias.index', compact('audiencias'));
    }
}




