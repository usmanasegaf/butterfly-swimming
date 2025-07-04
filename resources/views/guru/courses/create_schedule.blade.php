@extends('layouts.app')

@section('title', 'Buat Jadwal Baru')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Buat Jadwal Baru untuk Kursus: {{ $swimmingCourse->name }}</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Jadwal</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.schedules.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="swimming_course_id" value="{{ $swimmingCourse->id }}">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="location_id">Lokasi</label>
                        <select class="form-control @error('location_id') is-invalid @enderror" id="location_id"
                            name="location_id" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="day_of_week">Hari</label>
                        <select name="day_of_week" id="day_of_week" class="form-control" required>
                            <option value="">Pilih Hari</option>
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jumat</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Minggu</option>
                        </select>
                        @error('day_of_week')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_time_of_day">Waktu Mulai</label>
                        <input type="time" name="start_time_of_day" id="start_time_of_day" class="form-control" required>
                        @error('start_time_of_day')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_time_of_day">Waktu Selesai</label>
                        <input type="time" name="end_time_of_day" id="end_time_of_day" class="form-control" required>
                        @error('end_time_of_day')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="max_students">Maksimal Murid</label>
                        <input type="number" class="form-control @error('max_students') is-invalid @enderror"
                            id="max_students" name="max_students" value="{{ old('max_students') }}" min="1"
                            required>
                        @error('max_students')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    <a href="{{ route('guru.courses.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
