@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Kursus Renang</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('swimming-course-management.update', $course->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kursus</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <input type="text" class="form-control @error('level') is-invalid @enderror" id="level" name="level" value="{{ old('level', $course->level) }}" required>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $course->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Durasi (minggu)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sessions_per_week" class="form-label">Sesi per Minggu</label>
                                <input type="number" class="form-control @error('sessions_per_week') is-invalid @enderror" id="sessions_per_week" name="sessions_per_week" value="{{ old('sessions_per_week', $course->sessions_per_week) }}" required>
                                @error('sessions_per_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_participants" class="form-label">Maksimum Peserta</label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror" id="max_participants" name="max_participants" value="{{ old('max_participants', $course->max_participants) }}">
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instructor" class="form-label">Instruktur</label>
                                <input type="text" class="form-control @error('instructor') is-invalid @enderror" id="instructor" name="instructor" value="{{ old('instructor', $course->instructor) }}">
                                @error('instructor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar URL</label>
                            <input type="text" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $course->image) }}">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('swimming-course-management.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection