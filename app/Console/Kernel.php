<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
            $schedule->command('murid:send-inapp-schedule-reminder')->everyMinute();
        $schedule->command('murid:send-expired-reminder')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}