@extends('layouts.app')

@section('title', 'Edit Jadwal Kursus')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Jadwal Kursus: {{ $schedule->swimmingCourse->name ?? 'Kursus Tidak Ditemukan' }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Jadwal</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="form-group">
                    <label for="swimming_course_id">Kursus Renang</label>
                    <select class="form-control @error('swimming_course_id') is-invalid @enderror" id="swimming_course_id" name="swimming_course_id" required>
                        <option value="">Pilih Kursus</option>
                        @foreach ($swimmingCourses as $course)
                            <option value="{{ $course->id }}" {{ (old('swimming_course_id', $schedule->swimming_course_id) == $course->id) ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('swimming_course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location_id">Lokasi</label>
                    <select class="form-control @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ (old('location_id', $schedule->location_id) == $location->id) ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Waktu Mulai</label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d\TH:i')) }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">Waktu Selesai</label>
                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('Y-m-d\TH:i')) }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_students">Maksimal Murid</label>
                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" id="max_students" name="max_students" value="{{ old('max_students', $schedule->max_students) }}" min="1" required>
                    @error('max_students')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Perbarui Jadwal</button>
                <a href="{{ route('guru.courses.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection