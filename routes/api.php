<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas da API para consulta processual
Route::prefix('v1')->group(function () {
    Route::post('consulta-processual', [\App\Http\Controllers\Api\ConsultaProcessualController::class, 'consultar']);
    Route::get('processos/{numero}/detalhes', [\App\Http\Controllers\Api\ProcessoController::class, 'detalhes']);
    Route::get('processos/{numero}/historico', [\App\Http\Controllers\Api\ProcessoController::class, 'historico']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('notificacoes', [\App\Http\Controllers\Api\NotificacaoController::class, 'index']);
        Route::post('notificacoes/{notificacao}/ler', [\App\Http\Controllers\Api\NotificacaoController::class, 'marcarComoLida']);
    });
});

