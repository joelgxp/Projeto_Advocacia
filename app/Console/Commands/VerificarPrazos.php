<?php

namespace App\Console\Commands;

use App\Jobs\VerificarPrazosVencendo;
use Illuminate\Console\Command;

class VerificarPrazos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processos:verificar-prazos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica prazos vencendo e envia notificações';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Verificando prazos vencendo...');

        VerificarPrazosVencendo::dispatch();

        $this->info('Job de verificação de prazos despachado com sucesso!');

        return Command::SUCCESS;
    }
}
