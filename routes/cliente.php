<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\ProcessoController;
use App\Http\Controllers\Cliente\DocumentoController;
use App\Http\Controllers\Cliente\ComunicacaoController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('processos', [ProcessoController::class, 'index'])->name('processos.index');
Route::get('processos/{processo}', [ProcessoController::class, 'show'])->name('processos.show');
Route::get('processos/{processo}/movimentacoes', [ProcessoController::class, 'movimentacoes'])->name('processos.movimentacoes');

Route::get('documentos', [DocumentoController::class, 'index'])->name('documentos.index');
Route::get('documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');

Route::get('comunicacoes', [ComunicacaoController::class, 'index'])->name('comunicacoes.index');
Route::post('comunicacoes', [ComunicacaoController::class, 'store'])->name('comunicacoes.store');

