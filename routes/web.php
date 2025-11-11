<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas por role
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        require __DIR__.'/admin.php';
    });
    
    Route::middleware('role:advogado')->prefix('advogado')->name('advogado.')->group(function () {
        require __DIR__.'/advogado.php';
    });
    
    Route::middleware('role:recepcionista|tesoureiro')->prefix('recepcao')->name('recepcao.')->group(function () {
        require __DIR__.'/recepcao.php';
    });
    
    Route::middleware('role:cliente')->prefix('cliente')->name('cliente.')->group(function () {
        require __DIR__.'/cliente.php';
    });
});

