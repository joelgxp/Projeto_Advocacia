<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendar jobs (será configurado no Kernel.php)
// Schedule::command('processos:consultar-tribunais')->daily();
// Schedule::command('notificacoes:enviar-prazos')->daily();
// Schedule::command('notificacoes:enviar-audiencias')->daily();
