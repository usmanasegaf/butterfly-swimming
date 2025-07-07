{{-- filepath: resources/views/guru/attendance/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Absensi Murid') {{-- Tambahkan title --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Riwayat Absensi Murid</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($attendances->isEmpty())
        <div class="alert alert-info">
            Belum ada riwayat absensi untuk jadwal yang Anda ampu.
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Absensi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal Absensi</th>
                                <th>Murid</th>
                                <th>Kursus</th> {{-- Mengganti Jadwal menjadi Kursus agar lebih jelas --}}
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Waktu Dicatat</th> {{-- Tambahkan kolom waktu dicatat --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                {{-- Menggunakan attendance_date dari model Attendance --}}
                                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                {{-- Menggunakan relasi student() yang mengarah ke User/Murid --}}
                                <td>{{ $attendance->student->name ?? '-' }}</td>
                                {{-- Menggunakan relasi schedule.swimmingCourse untuk nama kursus --}}
                                <td>{{ $attendance->schedule->swimmingCourse->name ?? '-' }}</td>
                                {{-- Menggunakan relasi schedule.location untuk nama lokasi --}}
                                <td>{{ $attendance->schedule->location->name ?? '-' }}</td>
                                {{-- Menggunakan status dari model Attendance --}}
                                <td>{{ ucfirst($attendance->status) }}</td>
                                {{-- Menampilkan waktu absensi dicatat --}}
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection