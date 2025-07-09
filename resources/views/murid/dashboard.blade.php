{{-- filepath: resources/views/murid/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Murid</h2>

    <p>Selamat datang, {{ $user->name }}!</p>

    {{-- Informasi Kursus Ditugaskan --}}
    <div style="margin-top: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px;">
        <h4>Informasi Kursus Renang Anda</h4>
        @if ($assignedCourse)
            <p><strong>Kursus Anda:</strong> {{ $assignedCourse->name }}</p>
            <p><strong>Level:</strong> {{ $assignedCourse->level }}</p>
            <p><strong>Durasi Kursus:</strong> {{ $assignedCourse->duration }} minggu</p>
            <p><strong>Ditugaskan Pada:</strong> {{ $user->course_assigned_at->format('d M Y') }}</p>
            <p>
                <strong>Sisa Waktu Les:</strong>
                @if ($user->remaining_lesson_days === null)
                    Tidak ada data kursus.
                @elseif ($user->remaining_lesson_days > 0)
                    {{ $user->remaining_lesson_days }} hari tersisa.
                @else
                    Kursus telah kedaluwarsa.
                @endif
            </p>
        @else
            <p>Anda belum ditugaskan ke kursus renang manapun.</p>
        @endif
    </div>

    {{-- Informasi Jadwal Les Selanjutnya --}}
    <div style="margin-top: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px;">
        <h4>Jadwal Les Selanjutnya</h4>
        @if ($nextSchedule)
            <p>
                <strong>Tanggal:</strong> {{ $nextSchedule->occurrence->format('d M Y') }}
                <br>
                <strong>Jam:</strong> {{ \Carbon\Carbon::parse($nextSchedule->schedule->start_time_of_day)->format('H:i') }} - {{ \Carbon\Carbon::parse($nextSchedule->schedule->end_time_of_day)->format('H:i') }}
                <br>
                <strong>Lokasi:</strong> {{ $location ?? 'Belum diatur' }}
                ({{ $nextSchedule->schedule->location->address ?? '' }})
                <br>
                <strong>Guru:</strong> {{ $nextSchedule->schedule->guru->name ?? 'N/A' }}
            </p>
        @else
            <p>Belum ada jadwal les selanjutnya yang ditemukan untuk kursus Anda.</p>
        @endif
    </div>

    {{-- Anda bisa menambahkan konten dashboard lainnya di sini --}}

</div>
@endsection