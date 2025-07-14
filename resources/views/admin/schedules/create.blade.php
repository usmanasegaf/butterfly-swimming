@extends('layouts.app')

@section('title', 'Buat Jadwal Kursus Baru')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Buat Jadwal Kursus Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Jadwal Baru</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('schedules.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="swimming_course_id">Kursus Renang</label>
                    <select class="form-control @error('swimming_course_id') is-invalid @enderror" id="swimming_course_id" name="swimming_course_id" required>
                        <option value="">Pilih Kursus</option>
                        @foreach ($swimmingCourses as $course)
                            <option value="{{ $course->id }}" {{ old('swimming_course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }} ({{ $course->level }})</option>
                        @endforeach
                    </select>
                    @error('swimming_course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="guru_id">Guru Pengajar</label>
                    <select class="form-control @error('guru_id') is-invalid @enderror" id="guru_id" name="guru_id" required>
                        <option value="">Pilih Guru</option>
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location_id">Lokasi</label>
                    <select class="form-control @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="day_of_week">Hari</label>
                    <select name="day_of_week" id="day_of_week" class="form-control @error('day_of_week') is-invalid @enderror" required>
                        <option value="">Pilih Hari</option>
                        <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Senin</option>
                        <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Selasa</option>
                        <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Rabu</option>
                        <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Kamis</option>
                        <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Jumat</option>
                        <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Sabtu</option>
                        <option value="7" {{ old('day_of_week') == 7 ? 'selected' : '' }}>Minggu</option>
                    </select>
                    @error('day_of_week')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time_of_day">Waktu Mulai</label>
                    <input type="time" name="start_time_of_day" id="start_time_of_day" class="form-control @error('start_time_of_day') is-invalid @enderror" value="{{ old('start_time_of_day') }}" required>
                    @error('start_time_of_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time_of_day">Waktu Selesai</label>
                    <input type="time" name="end_time_of_day" id="end_time_of_day" class="form-control @error('end_time_of_day') is-invalid @enderror" value="{{ old('end_time_of_day') }}" required>
                    @error('end_time_of_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_students">Maksimal Murid</label>
                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" id="max_students" name="max_students" value="{{ old('max_students') }}" min="1" required>
                    @error('max_students')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status Jadwal</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
