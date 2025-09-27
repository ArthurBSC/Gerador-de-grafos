<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Comandos Artisan da aplicação
     */
    protected $commands = [
        //
    ];

    /**
     * Definir agenda de comandos
     */
    protected function schedule(Schedule $schedule)
    {
        // Limpar cache diariamente em produção
        if (app()->environment('production')) {
            $schedule->command('cache:clear')->daily();
        }
    }

    /**
     * Registrar comandos
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
