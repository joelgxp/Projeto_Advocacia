<?php

namespace App\Providers;

use App\Repositories\Contracts\ProcessoRepositoryInterface;
use App\Repositories\ProcessoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind dos Repositories
        $this->app->bind(ProcessoRepositoryInterface::class, ProcessoRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}


