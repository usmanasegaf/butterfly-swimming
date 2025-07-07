<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;     // Asumsi guru dan murid adalah model User
use App\Models\Schedule; // Pastikan model Schedule sudah diimport

class ScheduleMuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temukan guru pertama (asumsi ID 2 jika sesuai urutan seeding admin-guru-murid)
        // Atau cari berdasarkan role dan email jika lebih aman
        $guru = User::where('role', 'guru')->first();

        // Temukan murid pertama (asumsi ID 3 jika sesuai urutan seeding admin-guru-murid)
        // Atau cari berdasarkan role dan email jika lebih aman
        $murid = User::where('role', 'murid')->first();

        // Temukan jadwal pertama yang diampu oleh guru tersebut
        $schedule = Schedule::where('guru_id', $guru->id)->first();

        if ($guru && $murid && $schedule) {
            // Lampirkan murid ke jadwal ini menggunakan relasi many-to-many
            // Ini akan mengisi tabel pivot 'schedule_murid'
            $schedule->murids()->attach($murid->id);

            $this->command->info('Murid ' . $murid->name . ' berhasil dilampirkan ke jadwal ' . $schedule->id . ' guru ' . $guru->name . '.');
        } else {
            $this->command->warn('Gagal menemukan guru, murid, atau jadwal untuk seeding schedule_murid.');
        }
    }
}