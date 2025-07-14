<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\MuridCourseReminderNotification; // Import notifikasi baru
use Carbon\Carbon;

class SendMuridCourseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'murid:send-course-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi in-app ke murid jika sisa waktu les mereka hampir habis.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambang batas notifikasi: 5 hari sebelum kursus habis
        $thresholdDays = 5;

        // Ambil semua murid yang aktif dan memiliki kursus yang ditugaskan
        $murids = User::where('role', 'murid')
                      ->where('status', 'active')
                      ->whereNotNull('swimming_course_id')
                      ->whereNotNull('course_assigned_at')
                      ->get();

        foreach ($murids as $murid) {
            // Gunakan accessor getRemainingLessonDaysAttribute() yang sudah ada
            // Perlu parsing string output dari accessor untuk mendapatkan nilai numerik hari
            $remainingString = $murid->remaining_lesson_days;

            // Coba ekstrak angka hari dari string
            $daysRemaining = null;
            if (str_contains($remainingString, 'hari')) {
                preg_match('/(\d+)\shari/', $remainingString, $matches);
                if (isset($matches[1])) {
                    $daysRemaining = (int) $matches[1];
                }
            } elseif (str_contains($remainingString, 'jam') || str_contains($remainingString, 'menit') || str_contains($remainingString, 'detik')) {
                // Jika hanya jam/menit/detik, berarti kurang dari 1 hari
                $daysRemaining = 0;
            } elseif (str_contains($remainingString, 'Kursus telah kedaluwarsa')) {
                $daysRemaining = -1; // Atau nilai lain yang menunjukkan sudah expired
            }


            // Cek apakah sisa hari memenuhi ambang batas DAN notifikasi belum dikirim atau sudah lama dikirim
            if ($daysRemaining !== null && $daysRemaining >= 0 && $daysRemaining <= $thresholdDays) {
                $canSendNotification = true;

                // Periksa kapan notifikasi terakhir dikirim
                if ($murid->last_course_reminder_sent_at) {
                    $lastSent = Carbon::parse($murid->last_course_reminder_sent_at);
                    // Jika notifikasi sudah dikirim dalam 24 jam terakhir, jangan kirim lagi
                    if ($lastSent->greaterThanOrEqualTo(Carbon::now()->subHours(24))) {
                        $canSendNotification = false;
                    }
                }

                if ($canSendNotification) {
                    // Kirim notifikasi
                    $murid->notify(new MuridCourseReminderNotification($murid, $daysRemaining));

                    // Update timestamp terakhir notifikasi dikirim
                    $murid->last_course_reminder_sent_at = Carbon::now();
                    $murid->save();

                    $this->info("Notifikasi sisa waktu les dikirim ke: " . $murid->name . " (Sisa: " . $daysRemaining . " hari)");
                } else {
                    $this->info("Notifikasi sisa waktu les tidak dikirim ke: " . $murid->name . " (Sudah dikirim baru-baru ini)");
                }
            } elseif ($daysRemaining === -1) {
                $this->info("Kursus murid " . $murid->name . " sudah kedaluwarsa.");
            }
        }

        $this->info('Proses pengiriman notifikasi sisa waktu les selesai.');
    }
}
