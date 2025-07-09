{{-- filepath: resources/views/murid/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kursus Saya') {{-- Judul diubah menjadi 'Kursus Saya' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Informasi Kursus Renang Anda</h1> {{-- Judul utama halaman ini --}}

    {{-- Informasi Kursus Ditugaskan (DIPINDAHKAN KE SINI DARI DASHBOARD) --}}
    @if ($assignedCourse) {{-- Menggunakan $assignedCourse yang dikirim dari MuridController@index --}}
        <div class="mb-6" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px;">
            <p><strong>Kursus Anda:</strong> {{ $assignedCourse->name }}</p>
            <p><strong>Level:</strong> {{ $assignedCourse->level }}</p>
            <p><strong>Durasi Kursus:</strong> {{ $assignedCourse->duration }} minggu</p>
            <p><strong>Ditugaskan Pada:</strong> {{ $user->course_assigned_at->format('d M Y') }}</p>
            <p>
                <strong>Sisa Waktu Les:</strong>
                {{-- Gunakan accessor remaining_lesson_days dari User model --}}
                @if ($user->remaining_lesson_days === null)
                    Tidak ada data kursus.
                @elseif (is_string($user->remaining_lesson_days)) {{-- Untuk pesan seperti "Kursus telah kedaluwarsa." --}}
                    {{ $user->remaining_lesson_days }}
                @else {{-- Untuk output sisa waktu yang diformat (sudah di-floor) --}}
                    {{ $user->remaining_lesson_days }}
                @endif
            </p>
        </div>
    @else
        <p>Anda belum ditugaskan ke kursus renang manapun.</p>
    @endif

    {{-- JADWAL LES SELANJUTNYA DIHAPUS DARI SINI --}}
    {{-- Blok ini akan ditampilkan di resources/views/murid/dashboard.blade.php --}}

</div>
@endsection