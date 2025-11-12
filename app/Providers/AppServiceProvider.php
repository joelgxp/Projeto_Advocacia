<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Processo;
use App\Observers\ProcessoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Configurações de timezone - usar variável de ambiente diretamente
        $timezone = env('APP_TIMEZONE', 'America/Sao_Paulo');
        if ($timezone) {
            date_default_timezone_set($timezone);
        }
        
        // Otimizações de performance (comentado - pode causar travamento na inicialização)
        // if (app()->environment('production')) {
        //     \DB::connection()->disableQueryLog();
        // }
        
        // Registrar observers
        Processo::observe(ProcessoObserver::class);
    }
}

