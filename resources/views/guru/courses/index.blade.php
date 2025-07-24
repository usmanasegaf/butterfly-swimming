@extends('layouts.app')

@section('title', 'Manajemen Jadwal Kursus Saya')

@section('content')
    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kursus Renang Tersedia</h6>
            </div>
            <div class="card-body">
                @if ($swimmingCourses->isEmpty())
                    <p>Tidak ada kursus renang yang tersedia saat ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Kursus</th>
                                    <th>Deskripsi</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($swimmingCourses as $course)
                                    <tr>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->description }}</td>
                                        <td>Rp {{ number_format($course->price, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('guru.courses.create_schedule_form', $course->id) }}"
                                                class="btn btn-sm btn-primary">Buat Jadwal untuk Kursus Ini</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Jadwal Kursus yang Saya Buat</h6>
            </div>
            <div class="card-body">
                @if ($guruSchedules->isEmpty())
                    <p>Anda belum membuat jadwal kursus apa pun.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" id="mySchedulesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kursus</th>
                                    <th>Murid</th>
                                    <th>Lokasi</th>
                                    <th>Hari</th> {{-- MENAMBAHKAN TH UNTUK HARI --}}
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guruSchedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->swimmingCourse->name ?? 'Kursus Tidak Ditemukan' }}</td>
                                        <td>{{ $schedule->murid->name ?? 'Belum ada' }}</td> 
                                        <td>{{ $schedule->location->name ?? 'Lokasi Tidak Ditemukan' }}</td>
                                        {{-- MENAMBAHKAN TD UNTUK HARI DAN MENGUBAH ANGKA KE NAMA HARI --}}
                                        <td>
                                            @php
                                                $days = [
                                                    1 => 'Senin',
                                                    2 => 'Selasa',
                                                    3 => 'Rabu',
                                                    4 => 'Kamis',
                                                    5 => 'Jumat',
                                                    6 => 'Sabtu',
                                                    7 => 'Minggu',
                                                ];
                                            @endphp
                                            {{ $days[$schedule->day_of_week] ?? 'Tidak Ditemukan' }}
                                        </td>
                                        {{-- PERBAIKAN: Menggunakan nama kolom yang benar dan format waktu H:i --}}
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i') }}</td>
                                        {{-- PERBAIKAN: Menggunakan nama kolom yang benar dan format waktu H:i --}}
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i') }}</td>
                                        {{-- Ini sudah benar untuk status --}}
                                        <td>{{ ucfirst($schedule->status) }}</td>
                                        <td>
                                            <a href="{{ route('guru.schedules.edit', $schedule->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('guru.schedules.destroy', $schedule->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection