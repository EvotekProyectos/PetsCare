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
        // $schedule->command('inspire')->hourly();

        // Regla de negocio: cuenta hospitalaria abierta sin anticipo nuevo
        // por más de 48 horas -> notifica a recepcionistas (ver
        // NotifyOverdueAdvancePayments). Hourly es suficiente precisión
        // para una ventana de 48h; withoutOverlapping() por si alguna
        // corrida tarda más de lo esperado.
        $schedule->command('advance-payments:notify-overdue')
            ->hourly()
            ->withoutOverlapping();
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
