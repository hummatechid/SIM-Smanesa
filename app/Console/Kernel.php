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
        $schedule->command('app:make-attendences')->daily()->onFailureWithOutput(function($item){
            var_dump("failed : ". $item);
        });
        $schedule->command('app:make-attendences')->hourly()->between("1:00","6:00")->onFailureWithOutput(function($item){
            var_dump("failed : ". $item);
        });
        $schedule->command('app:make-setting-time')->daily()->onFailureWithOutput(function($item){
            var_dump("failed : ". $item);
        });
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
