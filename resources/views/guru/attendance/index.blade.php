{{-- filepath: resources/views/guru/attendance/index.blade.php --}}
@extends('layouts.app')

@section('content')
<h3>Daftar Absensi</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Murid</th>
            <th>Jadwal</th>
            <th>Lokasi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->murid->name ?? '-' }}</td>
            <td>{{ $attendance->schedule->id ?? '-' }}</td>
            <td>{{ $attendance->location->name ?? '-' }}</td>
            <td>{{ $attendance->is_present ? 'Hadir' : 'Tidak Hadir' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection