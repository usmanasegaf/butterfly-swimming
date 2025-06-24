<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Notifications\StudentScheduleReminder;
use Carbon\Carbon;

class SendStudentScheduleInAppReminder extends Command
{
    protected $signature = 'student:send-inapp-schedule-reminder';
    protected $description = 'Kirim in-app notification ke murid 10 menit sebelum jadwal les';

    public function handle()
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addMinutes(10)->format('H:i:00');
        $today = $now->toDateString();

        $schedules = Schedule::where('date', $today)
            ->whereTime('start_time', $targetTime)
            ->get();

        foreach ($schedules as $schedule) {
            $student = $schedule->student;
            if ($student) {
                $student->notify(new StudentScheduleReminder($schedule));
            }
        }

        $this->info('In-app reminder dikirim.');
    }
}