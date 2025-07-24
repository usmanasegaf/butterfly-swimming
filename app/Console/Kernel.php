<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendMuridCourseReminder; // <<< TAMBAHKAN INI
use App\Console\Commands\SendMuridExpiredReminder; // Sudah ada
use App\Console\Commands\SendMuridScheduleInAppReminder; // Sudah ada
use App\Console\Commands\SendMeetingExpiryReminder;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Daftarkan command baru di sini
        SendMuridCourseReminder::class, // <<< TAMBAHKAN INI
        // Pastikan command lain yang sudah ada juga terdaftar jika belum
        SendMuridExpiredReminder::class,
        SendMuridScheduleInAppReminder::class,
        SendMeetingExpiryReminder::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Jadwalkan command untuk berjalan setiap hari pada jam 8 pagi
        $schedule->command('murid:send-course-reminder')->dailyAt('08:00'); // <<< TAMBAHKAN INI

        // Jadwal command yang sudah ada sebelumnya:
        $schedule->command('murid:send-inapp-schedule-reminder')->everyMinute();
        $schedule->command('murid:send-expired-reminder')->daily();
        $schedule->command('murid:send-meeting-expiry-reminder')->dailyAt('08:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
