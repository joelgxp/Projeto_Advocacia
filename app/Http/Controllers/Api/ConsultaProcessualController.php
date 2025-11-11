<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConsultaProcessualService;
use Illuminate\Http\Request;

class ConsultaProcessualController extends Controller
{
    public function __construct(
        private ConsultaProcessualService $consultaService
    ) {}

    /**
     * Consultar processo via API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultar(Request $request)
    {
        $request->validate([
            'numero_processo' => 'required|string|min:10',
            'tribunal' => 'nullable|string|size:2',
        ]);

        // Normalizar número de processo
        $numeroProcesso = \App\Helpers\ProcessoCNJHelper::normalizar($request->numero_processo);
        
        if (!$numeroProcesso) {
            return response()->json([
                'success' => false,
                'message' => 'Número de processo inválido. Use o padrão CNJ: NNNNNNN-DD.AAAA.J.TR.OOOO',
            ], 400);
        }

        // Tribunal é opcional (será extraído automaticamente)
        $tribunal = $request->tribunal;

        $resultado = $this->consultaService->consultarProcesso($numeroProcesso, $tribunal);

        $statusCode = $resultado['success'] ? 200 : ($resultado['status'] ?? 400);

        return response()->json($resultado, $statusCode);
    }
}

