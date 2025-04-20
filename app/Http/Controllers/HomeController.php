<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Data untuk program kursus renang
        $programs = [
            [
                'id' => 1,
                'name' => 'Kelas Pemula',
                'description' => 'Kursus dasar untuk pemula yang belum pernah berenang sebelumnya.',
                'image' => 'program-pemula.jpg',
                'level' => 'Pemula',
                'price' => 1500000
            ],
            [
                'id' => 2,
                'name' => 'Kelas Menengah',
                'description' => 'Untuk yang sudah menguasai dasar-dasar berenang dan ingin meningkatkan teknik.',
                'image' => 'program-menengah.jpg',
                'level' => 'Menengah',
                'price' => 1800000
            ],
            [
                'id' => 3,
                'name' => 'Kelas Lanjutan',
                'description' => 'Pelatihan intensif untuk teknik lanjutan dan persiapan kompetisi.',
                'image' => 'program-lanjutan.jpg',
                'level' => 'Lanjutan',
                'price' => 2200000
            ]
        ];

        // Data untuk testimoni
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'role' => 'Orang Tua Siswa',
                'message' => 'Anak saya sangat menikmati kursus renang di sini. Dalam waktu 3 bulan, dia sudah bisa berenang dengan percaya diri. Pelatihnya sangat sabar dan profesional!',
                'image' => 'testimonial-1.jpg',
                'rating' => 5
            ],
            [
                'name' => 'Siti Rahayu',
                'role' => 'Siswa Dewasa',
                'message' => 'Di usia 35 tahun, saya akhirnya bisa berenang berkat Butterfly Swimming Course. Metode pengajarannya sangat efektif dan menyenangkan.',
                'image' => 'testimonial-2.jpg',
                'rating' => 5
            ],
            [
                'name' => 'Dimas Pratama',
                'role' => 'Atlet Renang Junior',
                'message' => 'Berkat pelatihan di Butterfly, saya berhasil meraih medali emas di kejuaraan renang tingkat provinsi. Pelatihnya benar-benar memahami teknik yang tepat.',
                'image' => 'testimonial-3.jpg',
                'rating' => 5
            ]
        ];

        return view('home', compact('programs', 'testimonials'));
    }

    public function program()
    {
        // Implementasi halaman program
        return view('program');
    }

    public function programDetail($id)
    {
        // Implementasi halaman detail program
        return view('program-detail', compact('id'));
    }

    public function jadwal()
    {
        // Implementasi halaman jadwal
        return view('jadwal');
    }

    public function pelatih()
    {
        // Implementasi halaman pelatih
        return view('pelatih');
    }

    public function tentang()
    {
        // Implementasi halaman tentang
        return view('tentang');
    }

    public function kontak()
    {
        // Implementasi halaman kontak
        return view('kontak');
    }

    public function daftar()
    {
        // Implementasi halaman pendaftaran
        return view('daftar');
    }
}