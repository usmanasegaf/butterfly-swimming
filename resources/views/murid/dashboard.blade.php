{{-- filepath: resources/views/murid/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Murid')

@section('content')
<div class="container">

    <p>Selamat datang, {{ $user->name }}!</p>

    {{-- INFORMASI KURSUS RENANG ANDA DIHAPUS DARI SINI --}}
    {{-- Blok ini akan dipindahkan ke resources/views/murid/index.blade.php --}}

    {{-- Informasi Jadwal Les Selanjutnya (INI TETAP DI SINI) --}}
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