<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// Penting: Tambahkan ini untuk menggunakan DB facade

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO `schedules` (`id`, `swimming_course_id`, `guru_id`, `location_id`, `day_of_week`, `start_time_of_day`, `end_time_of_day`, `max_students`, `status`, `created_at`, `updated_at`)
            VALUES (1, 1, 2, 1, 1, '10:08:20', '12:08:20', 5, 'active', NOW(), NOW());
        ");
        // Catatan: Saya mengganti NULL untuk created_at dan updated_at dengan NOW() agar terisi otomatis oleh database.

        $this->command->info('Jadwal dengan ID 1 telah ditambahkan sesuai permintaan.');
    }
}
