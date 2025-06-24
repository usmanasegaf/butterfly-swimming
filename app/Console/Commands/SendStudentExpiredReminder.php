<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Notifications\StudentExpiredNotification;
use Carbon\Carbon;

class SendStudentExpiredReminder extends Command
{
    protected $signature = 'student:send-expired-reminder';
    protected $description = 'Kirim email ke murid yang expired tepat 5 minggu';

public function handle()
{
    $students = Student::whereNotNull('expired_at')->whereNotNull('email')->get();

    foreach ($students as $student) {
        $days = Carbon::now()->diffInDays($student->expired_at, false);

        if ($days === 5) {
            // 5 hari sebelum expired
            $student->notify(new StudentExpiredNotification($student, 'will_expire'));
        }
        if ($days === 0) {
            // Hari H expired
            $student->notify(new StudentExpiredNotification($student, 'expired'));
        }
    }

    $this->info('Notifikasi expired dikirim.');
}
}
