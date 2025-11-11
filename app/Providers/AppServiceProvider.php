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
        
        // Configurações de timezone
        date_default_timezone_set(config('app.timezone'));
        
        // Otimizações de performance
        if (app()->environment('production')) {
            // Desabilitar query logging em produção
            \DB::connection()->disableQueryLog();
        }
        
        // Registrar observers
        Processo::observe(ProcessoObserver::class);
    }
}

