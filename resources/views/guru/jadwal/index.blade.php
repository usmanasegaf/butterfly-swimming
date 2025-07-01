{{-- filepath: resources/views/guru/jadwal/index.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Daftar Jadwal Kursus</h3>
<a href="{{ route('guru.jadwal.create') }}">Tambah Jadwal</a>
<table>
    <thead>
        <tr>
            <th>Kursus</th>
            <th>Lokasi</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->swimmingCourse->name ?? '-' }}</td>
            <td>{{ $schedule->location->name ?? '-' }}</td>
            <td>{{ $schedule->tanggal }}</td>
            <td>{{ $schedule->jam }}</td>
            <td>
                <a href="{{ route('guru.jadwal.edit', $schedule->id) }}">Edit</a>
                <form action="{{ route('guru.jadwal.destroy', $schedule->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection