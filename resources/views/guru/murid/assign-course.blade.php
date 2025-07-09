{{-- filepath: resources/views/guru/murid/assign-course.blade.php --}}
@extends('layouts.app')
@section('content')
    <h3>Tugaskan Kursus kepada {{ $murid->name }}</h3>

    <form method="POST" action="{{ route('guru.murid.assign_course', $murid->id) }}">
        @csrf

        <div>
            <label for="swimming_course_id">Pilih Kursus:</label>
            <select name="swimming_course_id" id="swimming_course_id" required>
                <option value="">-- Pilih Kursus --</option>
                @forelse($availableCourses as $course)
                    <option value="{{ $course->id }}" {{ $murid->swimming_course_id == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} (Level: {{ $course->level }}, Durasi: {{ $course->duration }} minggu)
                    </option>
                @empty
                    <option value="" disabled>Tidak ada kursus yang tersedia untuk Anda.</option>
                @endforelse
            </select>
            @error('swimming_course_id')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>
        <br>

        <button type="submit">Tugaskan Kursus</button>
        <a href="{{ route('guru.murid.index') }}">Batal</a>
    </form>
@endsection
