<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\GuruCourseMeetingReminder;
use Illuminate\Support\Facades\Log;

class SendMeetingExpiryReminder extends Command
{
    protected $signature = 'murid:send-meeting-expiry-reminder';
    protected $description = 'Kirim notifikasi ke guru jika paket pertemuan murid akan habis.';

    public function handle()
    {
        $this->info('Memulai pengecekan sisa pertemuan murid...');

        // Ambil semua murid yang punya kursus aktif
        $murids = User::where('role', 'murid')
                      ->where('status', 'active')
                      ->whereNotNull('swimming_course_id')
                      ->whereNotNull('jumlah_pertemuan_paket')
                      ->with('gurus') // Eager load relasi guru
                      ->get();

        foreach ($murids as $murid) {
            $sisaPertemuan = $murid->jumlah_pertemuan_paket - $murid->pertemuan_ke;

            // Kirim notifikasi jika sisa pertemuan 1 atau 2
            if ($sisaPertemuan > 0 && $sisaPertemuan <= 2) {
                foreach ($murid->gurus as $guru) {
                    try {
                        $guru->notify(new GuruCourseMeetingReminder($murid, $sisaPertemuan));
                        $this->info("Notifikasi sisa pertemuan untuk murid '{$murid->name}' dikirim ke guru '{$guru->name}'.");
                    } catch (\Exception $e) {
                        Log::error("Gagal mengirim notifikasi ke guru {$guru->id} untuk murid {$murid->id}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Pengecekan sisa pertemuan murid selesai.');
    }
}

