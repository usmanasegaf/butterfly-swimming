<?php

namespace Database\Seeders;

use App\Models\SwimmingCourse;
use Illuminate\Database\Seeder;

class SwimmingCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Kelas Privat 4x',
                'level' => 'Privat',
                'description' => 'Kursus renang privat 4x pertemuan dengan guru berpengalaman',
                'price' => 500000,
                'duration' => 4,
                'is_active' => true,
                'jumlah_pertemuan' => 4,
            ],
            [
                'name' => 'Kelas Privat 8x',
                'level' => 'Privat',
                'description' => 'Kursus renang privat 8x pertemuan dengan guru berpengalaman',
                'price' => 1000000,
                'duration' => 8,
                'is_active' => true,
                'jumlah_pertemuan' => 8,
            ],
            [
                'name' => 'Hydrotherapy',
                'level' => 'Privat',
                'description' => 'Kursus untuk terapi air dengan instruktur khusus. Cocok untuk rehabilitasi cedera atau relaksasi.',
                'price' => 1500000,
                'duration' => 13,
                'is_active' => true,
                'jumlah_pertemuan' => 12,
            ],
            [
                'name' => 'Baby Class 4x',
                'level' => 'Baby',
                'description' => 'Kursus renang untuk bayi usia 6 bulan hingga 3 tahun, 4x pertemuan. Fokus pada pengenalan air dan bonding antara orang tua dan anak.',
                'price' => 1500000,
                'duration' => 4,
                'is_active' => true,
                'jumlah_pertemuan' => 4,
            ],
            [
                'name' => 'Baby Class 8x',
                'level' => 'Baby',
                'description' => 'Kursus renang untuk bayi usia 6 bulan hingga 3 tahun, 8x pertemuan. Fokus pada pengenalan air dan bonding antara orang tua dan anak.',
                'price' => 2500000,
                'duration' => 8,
                'is_active' => true,
                'jumlah_pertemuan' => 8,
            ],
        ];

        foreach ($courses as $course) {
            SwimmingCourse::create($course);
        }
    }
}
