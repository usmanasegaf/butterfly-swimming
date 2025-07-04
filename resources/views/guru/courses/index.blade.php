@extends('layouts.app')

@section('title', 'Manajemen Jadwal Kursus Saya')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manajemen Jadwal Kursus Saya</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                                    <th>Lokasi</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Max Murid</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guruSchedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->swimmingCourse->name ?? 'Kursus Tidak Ditemukan' }}</td>
                                        <td>{{ $schedule->location->name ?? 'Lokasi Tidak Ditemukan' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('d M Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('d M Y H:i') }}</td>
                                        <td>{{ $schedule->max_students }}</td>
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
