@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Jadwal Kursus</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Jadwal</h6>
            {{-- @can('create schedule') --}}
            <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm">Tambah Jadwal Baru</a>
            {{-- @endcan --}}
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($schedules->isEmpty())
                <div class="alert alert-info">
                    Tidak ada jadwal kursus yang tersedia saat ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kursus</th>
                                <th>Guru</th>
                                <th>Lokasi</th>
                                <th>Hari</th>
                                <th>Waktu</th>
                                <th>Max Murid</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->id }}</td>
                                    <td>{{ $schedule->swimmingCourse->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->guru->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->location->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                        @endphp
                                        {{ $days[$schedule->day_of_week] ?? 'N/A' }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i') }}</td>
                                    <td>{{ $schedule->max_students }}</td>
                                    <td>
                                        @if($schedule->status == 'active')
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Aksi Jadwal">
                                            {{-- @can('edit schedule') --}}
                                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            {{-- @endcan --}}
                                            {{-- @can('delete schedule') --}}
                                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            {{-- @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
