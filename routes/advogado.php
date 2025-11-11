<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Advogado\DashboardController;
use App\Http\Controllers\Advogado\ProcessoController;
use App\Http\Controllers\Advogado\ClienteController;
use App\Http\Controllers\Advogado\AudienciaController;
use App\Http\Controllers\Advogado\AgendaController;
use App\Http\Controllers\Advogado\DocumentoController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('processos', ProcessoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('audiencias', AudienciaController::class);
Route::resource('agenda', AgendaController::class);
Route::resource('documentos', DocumentoController::class);

// Rotas especiais
Route::post('processos/{processo}/concluir', [ProcessoController::class, 'concluir'])->name('processos.concluir');
Route::post('processos/{processo}/obs', [ProcessoController::class, 'observacao'])->name('processos.obs');
Route::get('processos/{processo}/cobranca', [ProcessoController::class, 'cobranca'])->name('processos.cobranca');
Route::get('clientes/buscar-nome', [ClienteController::class, 'buscarNome'])->name('clientes.buscar-nome');

