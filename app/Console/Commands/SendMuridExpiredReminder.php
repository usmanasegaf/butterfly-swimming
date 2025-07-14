<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Gunakan User, bukan Murid
use App\Notifications\MuridExpiredNotification;
use Carbon\Carbon;

class SendMuridExpiredReminder extends Command
{
    protected $signature = 'murid:send-expired-reminder';
    protected $description = 'Kirim notifikasi ke murid yang expired atau akan expired.';

    public function handle()
    {
        // Ambil murid yang memiliki kolom expired_at dan email (atau cukup yang memiliki kursus dan course_assigned_at)
        // Kita akan menggunakan logika based on course_assigned_at dan duration dari swimmingCourse
        $murids = User::where('role', 'murid')
                      ->where('status', 'active')
                      ->whereNotNull('swimming_course_id')
                      ->whereNotNull('course_assigned_at')
                      ->get();

        foreach ($murids as $murid) {
            // Pastikan murid memiliki kursus yang ditugaskan
            if (!$murid->swimmingCourse || !$murid->course_assigned_at) {
                continue;
            }

            $durationWeeks = $murid->swimmingCourse->duration;
            $assignedDate = Carbon::parse($murid->course_assigned_at);
            $expirationDate = $assignedDate->copy()->addWeeks($durationWeeks);

            $daysUntilExpiration = Carbon::now()->diffInDays($expirationDate, false); // false = signed difference

            // Notifikasi 5 hari sebelum expired
            if ($daysUntilExpiration === 5) {
                // Cek apakah notifikasi ini sudah dikirim baru-baru ini (misal dalam 24 jam terakhir)
                // Anda perlu menambahkan kolom `last_will_expire_reminder_sent_at` di tabel users
                // Untuk kesederhanaan, kita bisa menggunakan `last_course_reminder_sent_at` yang sama
                // atau menambahkan kolom baru jika ingin kontrol lebih granular.
                // Untuk saat ini, kita asumsikan notifikasi ini berbeda dari "sisa X hari"
                // dan bisa dikirim jika belum pernah dikirim hari ini.
                
                // Jika ingin mencegah duplikasi, tambahkan kolom `last_will_expire_reminder_sent_at`
                // dan gunakan logika yang sama seperti di SendMuridCourseReminder
                $murid->notify(new MuridExpiredNotification($murid, 'will_expire'));
                $this->info("Notifikasi 'akan expired' dikirim ke: " . $murid->name);
            }
            // Notifikasi tepat saat expired
            elseif ($daysUntilExpiration === 0) {
                $murid->notify(new MuridExpiredNotification($murid, 'expired'));
                $this->info("Notifikasi 'expired' dikirim ke: " . $murid->name);
            }
        }

        $this->info('Proses pengiriman notifikasi expired selesai.');
    }
}
