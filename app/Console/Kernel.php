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
        $schedule->command('internship:complete-expired')->daily();
        $schedule->command('app:send-ending-notifications')->dailyAt('08:00');
        $schedule->command('backups:prune')->hourly();

        // Pembersihan berkala untuk menjaga ukuran database tetap ramping
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->command('queue:prune-failed --hours=168')->weekly();
        $schedule->command('queue:prune-batches --hours=168')->weekly();
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
