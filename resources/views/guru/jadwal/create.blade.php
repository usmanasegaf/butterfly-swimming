{{-- filepath: resources/views/guru/jadwal/create.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Tambah Jadwal Kursus</h3>
<form method="POST" action="{{ route('guru.jadwal.store') }}">
    @csrf
    <label>Kursus:</label>
    <select name="swimming_course_id" required>
        @foreach($courses as $course)
            <option value="{{ $course->id }}">{{ $course->name }}</option>
        @endforeach
    </select>
    <label>Lokasi:</label>
    <select name="location_id" required>
        @foreach($locations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
    <label>Tanggal:</label>
    <input type="date" name="tanggal" required>
    <label>Jam:</label>
    <input type="time" name="jam" required>
    <button type="submit">Simpan</button>
</form>
@endsection