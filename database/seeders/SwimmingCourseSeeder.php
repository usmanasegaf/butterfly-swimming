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
                'name' => 'Kelas Pemula',
                'level' => 'Pemula',
                'description' => 'Kursus dasar untuk pemula yang belum pernah berenang sebelumnya. Fokus pada pengenalan air, teknik dasar mengapung, dan gerakan kaki dasar.',
                'price' => 500000,
                'duration' => 4,
                'sessions_per_week' => 2,
                'max_participants' => 5,
                'instructor' => 'Muhammad daffa',
                'image' => 'beginner.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kelas Menengah',
                'level' => 'Menengah',
                'description' => 'Kursus untuk yang sudah memiliki dasar berenang dan ingin meningkatkan teknik. Fokus pada gaya bebas dan gaya dada yang benar.',
                'price' => 750000,
                'duration' => 6,
                'sessions_per_week' => 2,
                'max_participants' => 4,
                'instructor' => 'Dewi Kartini',
                'image' => 'intermediate.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kelas Lanjutan',
                'level' => 'Lanjutan',
                'description' => 'Kursus untuk perenang yang ingin menyempurnakan teknik dan kecepatan. Fokus pada gaya kupu-kupu dan gaya punggung.',
                'price' => 1000000,
                'duration' => 8,
                'sessions_per_week' => 3,
                'max_participants' => 3,
                'instructor' => 'Raden Dimas',
                'image' => 'advanced.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kelas Privat',
                'level' => 'Semua Level',
                'description' => 'Kursus privat one-on-one dengan instruktur berpengalaman. Disesuaikan dengan kebutuhan dan level kemampuan Anda.',
                'price' => 1500000,
                'duration' => 4,
                'sessions_per_week' => 2,
                'max_participants' => 1,
                'instructor' => 'Berbagai Instruktur',
                'image' => 'private.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kelas Anak-anak',
                'level' => 'Pemula',
                'description' => 'Kursus khusus untuk anak-anak usia 5-10 tahun. Fokus pada pengenalan air dan dasar-dasar berenang dengan metode yang menyenangkan.',
                'price' => 450000,
                'duration' => 4,
                'sessions_per_week' => 2,
                'max_participants' => 6,
                'instructor' => 'Dewi Lestari',
                'image' => 'kids.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            SwimmingCourse::create($course);
        }
    }
}
