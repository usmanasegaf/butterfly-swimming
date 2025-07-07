@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pilih Jadwal untuk Ambil Absensi</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($schedules->isEmpty())
        <div class="alert alert-info">
            Anda belum memiliki jadwal kursus yang aktif untuk diambil absensinya.
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Jadwal Kursus Anda</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Kursus</th>
                                <th>Lokasi</th>
                                <th>Hari</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->swimmingCourse->name }}</td>
                                    <td>{{ $schedule->location->name }}</td>
                                    <td>
                                        @php
                                            $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                        @endphp
                                        {{ $days[$schedule->day_of_week] ?? 'Tidak Ditemukan' }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i') }}</td>
                                    <td>
                                        <a href="{{ route('guru.schedules.show_attendance_form', $schedule->id) }}" class="btn btn-sm btn-primary">
                                            Ambil Absensi
                                        </a>
                                    </td>
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