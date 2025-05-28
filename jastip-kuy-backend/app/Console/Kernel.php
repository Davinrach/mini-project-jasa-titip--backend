<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ImportGacoanMenu;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command artisan yang kustom.
     */
    protected $commands = [
        \App\Console\Commands\ImportGacoanMenu::class,
    ];


    /**
     * Daftarkan schedule (opsional).
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('import:gacoan-menu')->daily();
    }

    /**
     * Register command dan file routes/console.php.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
