<?php

namespace App\Http\Controllers\Advogado;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with(['processo', 'cliente'])
            ->latest()
            ->paginate(15);
        
        return view('advogado.documentos.index', compact('documentos'));
    }
}




