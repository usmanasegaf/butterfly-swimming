{{-- filepath: resources/views/guru/jadwal/edit.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Edit Jadwal Kursus</h3>
<form method="POST" action="{{ route('guru.jadwal.update', $schedule->id) }}">
    @csrf
    @method('PUT')
    <label>Kursus:</label>
    <select name="swimming_course_id" required>
        @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ $schedule->swimming_course_id == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
        @endforeach
    </select>
    <label>Lokasi:</label>
    <select name="location_id" required>
        @foreach($locations as $location)
            <option value="{{ $location->id }}" {{ $schedule->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
        @endforeach
    </select>
    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="{{ $schedule->tanggal }}" required>
    <label>Jam:</label>
    <input type="time" name="jam" value="{{ $schedule->jam }}" required>
    <button type="submit">Update</button>
</form>
@endsection