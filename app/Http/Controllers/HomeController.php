<?php

namespace App\Http\Controllers;

use App\Models\SwimmingCourse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Data program untuk halaman utama
        $programs = [
            [
                'id' => 1,
                'name' => 'Kelas Pemula',
                'description' => 'Kursus dasar untuk pemula yang belum pernah berenang sebelumnya.',
                'level' => 'Pemula',
                'price' => 500000,
                'image' => 'program-pemula.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Kelas Menengah',
                'description' => 'Kursus untuk yang sudah memiliki dasar berenang dan ingin meningkatkan teknik.',
                'level' => 'Menengah',
                'price' => 750000,
                'image' => 'pool2.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Kelas Lanjutan',
                'description' => 'Kursus untuk perenang yang ingin menyempurnakan teknik dan kecepatan.',
                'level' => 'Lanjutan',
                'price' => 1000000,
                'image' => 'program-lanjutan.jpg'
            ]
        ];

        // Data testimonial
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'role' => 'Orang Tua Murid',
                'message' => 'Anak saya sangat menikmati kursus renang di Butterfly Swimming Course. Pelatihnya sangat profesional dan sabar.',
                'rating' => 5,
                'image' => 'testimonial-3.jpg'
            ],
            [
                'name' => 'Siti Rahayu',
                'role' => 'Pemula Renang',
                'message' => 'Saya belajar berenang dari nol di usia 30 tahun, dan berkat pelatih yang hebat, sekarang saya bisa berenang dengan percaya diri.',
                'rating' => 5,
                'image' => 'testimonial-2.jpg'
            ],
            [
                'name' => 'Ahmad Rizki',
                'role' => 'Atlet Renang',
                'message' => 'Program lanjutan sangat membantu meningkatkan teknik dan kecepatan saya. Fasilitas kolam juga sangat baik.',
                'rating' => 5,
                'image' => 'testimonial-1.jpg'
            ]
        ];

        return view('home', compact('programs', 'testimonials'));
    }
}
