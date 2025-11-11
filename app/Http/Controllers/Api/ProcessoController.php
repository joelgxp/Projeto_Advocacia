<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use Illuminate\Http\Request;

class ProcessoController extends Controller
{
    public function detalhes($numero)
    {
        $processo = Processo::where('numero_processo', $numero)
            ->with(['cliente', 'advogado', 'vara', 'especialidade'])
            ->first();

        if (!$processo) {
            return response()->json([
                'success' => false,
                'message' => 'Processo não encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $processo,
        ]);
    }

    public function historico($numero)
    {
        $processo = Processo::where('numero_processo', $numero)->first();

        if (!$processo) {
            return response()->json([
                'success' => false,
                'message' => 'Processo não encontrado',
            ], 404);
        }

        $movimentacoes = $processo->movimentacoes()->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $movimentacoes,
        ]);
    }
}

