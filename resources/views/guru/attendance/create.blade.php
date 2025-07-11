{{-- filepath: resources/views/guru/attendance/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <h3>Absensi Murid</h3>
    <form method="POST" action="{{ route('guru.attendance.store') }}">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <label for="murid_id">Murid:</label>
        <select name="murid_id" required>
            @foreach ($murids as $murid)
                <option value="{{ $murid->id }}">{{ $murid->name }}</option>
            @endforeach
        </select>
        <label for="location_id">Lokasi:</label>
        <select name="location_id" required>
            @foreach ($locations as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>
        <label for="date">Tanggal:</label>
        <input type="date" name="date" required>
        <label for="is_present">Status Hadir:</label>
        <select name="is_present" required>
            <option value="1">Hadir</option>
            <option value="0">Tidak Hadir</option>
        </select>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <script>
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    alert('Gagal mendapatkan lokasi. Aktifkan GPS!');
                });
            } else {
                alert('Browser tidak mendukung geolocation.');
            }
        </script>
        <button type="submit">Simpan</button>
    </form>
@endsection
