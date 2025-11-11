<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Recepcao\DashboardController;
use App\Http\Controllers\Recepcao\ProcessoController;
use App\Http\Controllers\Recepcao\ClienteController;
use App\Http\Controllers\Recepcao\AudienciaController;
use App\Http\Controllers\Recepcao\PagamentoController;
use App\Http\Controllers\Recepcao\ReceberController;
use App\Http\Controllers\Recepcao\PagarController;
use App\Http\Controllers\Recepcao\MovimentacaoController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('processos', ProcessoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('audiencias', AudienciaController::class);
Route::resource('pagamentos', PagamentoController::class);
Route::resource('receber', ReceberController::class);
Route::resource('pagar', PagarController::class);
Route::resource('movimentacoes', MovimentacaoController::class);

// Rotas especiais
Route::post('receber/{receber}/concluir', [ReceberController::class, 'concluir'])->name('receber.concluir');
Route::post('processos/{processo}/inserir-audiencia', [ProcessoController::class, 'inserirAudiencia'])->name('processos.inserir-audiencia');

