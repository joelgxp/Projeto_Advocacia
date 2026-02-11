<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Verificar prazos vencendo diariamente às 8h
        $schedule->command('processos:verificar-prazos')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Limpar notificações antigas (mais de 90 dias)
        $schedule->command('model:prune', ['--model' => 'App\\Models\\Notificacao'])
            ->weekly()
            ->sundays()
            ->at('02:00');

        // Backup do banco de dados (se configurado)
        // $schedule->command('backup:run')
        //     ->daily()
        //     ->at('03:00')
        //     ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
