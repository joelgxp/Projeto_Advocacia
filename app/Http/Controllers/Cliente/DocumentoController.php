<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $cliente = \App\Models\Cliente::where('cpf_cnpj', Auth::user()->cpf)->first();
        
        if (!$cliente) {
            return view('cliente.documentos.index', ['documentos' => collect()]);
        }

        $documentos = $cliente->documentos()->latest()->paginate(15);
        
        return view('cliente.documentos.index', compact('documentos'));
    }

    public function download(Documento $documento)
    {
        if (!Storage::exists($documento->arquivo)) {
            abort(404, 'Arquivo não encontrado');
        }

        return Storage::download($documento->arquivo, $documento->nome_original);
    }
}





