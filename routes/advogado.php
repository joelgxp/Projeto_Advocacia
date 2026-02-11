<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Advogado\DashboardController;
use App\Http\Controllers\Advogado\ProcessoController;
use App\Http\Controllers\Advogado\ClienteController;
use App\Http\Controllers\Advogado\AudienciaController;
use App\Http\Controllers\Advogado\AgendaController;
use App\Http\Controllers\Advogado\DocumentoController;
use App\Http\Controllers\Advogado\PeticaoIAController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('processos', ProcessoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('audiencias', AudienciaController::class);
Route::resource('agenda', AgendaController::class);
Route::resource('documentos', DocumentoController::class);

// Rotas de Petições com IA
Route::prefix('pecas-geradas')->name('pecas.')->group(function () {
    Route::get('/', [PeticaoIAController::class, 'index'])->name('index');
    Route::get('/criar', [PeticaoIAController::class, 'criar'])->name('criar');
    Route::post('/gerar', [PeticaoIAController::class, 'gerar'])->name('gerar');
    Route::post('/salvar', [PeticaoIAController::class, 'salvar'])->name('salvar');
    Route::post('/exportar', [PeticaoIAController::class, 'exportar'])->name('exportar');
    Route::get('/template/{template}/preview', [PeticaoIAController::class, 'previewTemplate'])->name('template.preview');
});

// Rotas especiais
Route::post('processos/{processo}/concluir', [ProcessoController::class, 'concluir'])->name('processos.concluir');
Route::post('processos/{processo}/obs', [ProcessoController::class, 'observacao'])->name('processos.obs');
Route::get('processos/{processo}/cobranca', [ProcessoController::class, 'cobranca'])->name('processos.cobranca');
Route::get('clientes/buscar-nome', [ClienteController::class, 'buscarNome'])->name('clientes.buscar-nome');

