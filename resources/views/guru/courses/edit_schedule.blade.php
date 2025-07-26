@extends('layouts.app')

@section('title', 'Edit Jadwal Kursus')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Jadwal Kursus:
            {{ $schedule->swimmingCourse->name ?? 'Kursus Tidak Ditemukan' }}</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Jadwal</h6>
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
                <form action="{{ route('guru.schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="swimming_course_id">Kursus Renang</label>
                        <select class="form-control @error('swimming_course_id') is-invalid @enderror"
                            id="swimming_course_id" name="swimming_course_id" required>
                            <option value="">Pilih Kursus</option>
                            @foreach ($swimmingCourses as $course)
                                <option value="{{ $course->id }}"
                                    {{ (old('swimming_course_id', $schedule->swimming_course_id) == $course->id) ? 'selected' : '' }}>
                                    {{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('swimming_course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location_id">Lokasi</label>
                        <select class="form-control @error('location_id') is-invalid @enderror" id="location_id"
                            name="location_id" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ (old('location_id', $schedule->location_id) == $location->id) ? 'selected' : '' }}>
                                    {{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                                    <div class="form-group">
                    <label for="murid_id">Murid Bimbingan</label>
                    <select class="form-control @error('murid_id') is-invalid @enderror" id="murid_id" name="murid_id" required>
                        <option value="">Pilih Murid</option>
                        @foreach ($murids as $murid)
                            <option value="{{ $murid->id }}" {{ old('murid_id', $schedule->murid_id) == $murid->id ? 'selected' : '' }}>{{ $murid->name }}</option>
                        @endforeach
                    </select>
                    @error('murid_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                    {{-- MENAMBAHKAN FIELD UNTUK HARI --}}
                    <div class="form-group">
                        <label for="day_of_week">Hari</label>
                        <select name="day_of_week" id="day_of_week" class="form-control @error('day_of_week') is-invalid @enderror" required>
                            <option value="">Pilih Hari</option>
                            <option value="1" {{ (old('day_of_week', $schedule->day_of_week) == 1) ? 'selected' : '' }}>Senin</option>
                            <option value="2" {{ (old('day_of_week', $schedule->day_of_week) == 2) ? 'selected' : '' }}>Selasa</option>
                            <option value="3" {{ (old('day_of_week', $schedule->day_of_week) == 3) ? 'selected' : '' }}>Rabu</option>
                            <option value="4" {{ (old('day_of_week', $schedule->day_of_week) == 4) ? 'selected' : '' }}>Kamis</option>
                            <option value="5" {{ (old('day_of_week', $schedule->day_of_week) == 5) ? 'selected' : '' }}>Jumat</option>
                            <option value="6" {{ (old('day_of_week', $schedule->day_of_week) == 6) ? 'selected' : '' }}>Sabtu</option>
                            <option value="7" {{ (old('day_of_week', $schedule->day_of_week) == 7) ? 'selected' : '' }}>Minggu</option>
                        </select>
                        @error('day_of_week')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_time_of_day">Waktu Mulai</label>
                        <input type="time" class="form-control @error('start_time_of_day') is-invalid @enderror"
                            id="start_time_of_day" name="start_time_of_day"
                            value="{{ old('start_time_of_day', \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i')) }}"
                            required>
                        @error('start_time_of_day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_time_of_day">Waktu Selesai</label>
                        <input type="time" class="form-control @error('end_time_of_day') is-invalid @enderror"
                            id="end_time_of_day" name="end_time_of_day"
                            value="{{ old('end_time_of_day', \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i')) }}"
                            required>
                        @error('end_time_of_day')
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