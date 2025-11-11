<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\AdvogadoController;
use App\Http\Controllers\Admin\FuncionarioController;
use App\Http\Controllers\Admin\ProcessoController;
use App\Http\Controllers\Admin\VaraController;
use App\Http\Controllers\Admin\EspecialidadeController;
use App\Http\Controllers\Admin\CargoController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rotas de recursos
Route::resource('clientes', ClienteController::class);
Route::resource('advogados', AdvogadoController::class);
Route::resource('funcionarios', FuncionarioController::class);
Route::resource('processos', ProcessoController::class);
Route::resource('varas', VaraController::class);
Route::resource('especialidades', EspecialidadeController::class);
Route::resource('cargos', CargoController::class);

// Consulta processual
Route::prefix('consulta-processual')->name('consulta-processual.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ConsultaProcessualController::class, 'index'])->name('index');
    Route::post('/consultar', [\App\Http\Controllers\Admin\ConsultaProcessualController::class, 'consultar'])->name('consultar');
    Route::get('/detalhes/{numero}', [\App\Http\Controllers\Admin\ConsultaProcessualController::class, 'detalhes'])->name('detalhes');
    Route::get('/historico/{numero}', [\App\Http\Controllers\Admin\ConsultaProcessualController::class, 'historico'])->name('historico');
});

// Sincronização de processos
Route::post('/processos/{processo}/sincronizar', [\App\Http\Controllers\Admin\ConsultaProcessualController::class, 'sincronizar'])
    ->name('processos.sincronizar');

