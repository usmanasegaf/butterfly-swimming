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
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $course->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <input type="text" class="form-control @error('level') is-invalid @enderror"
                                    id="level" name="level" value="{{ old('level', $course->level) }}" required>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $course->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label">Durasi (minggu)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                        id="duration" name="duration" value="{{ old('duration', $course->duration) }}"
                                        required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jumlah_pertemuan" class="form-label">Jumlah Pertemuan</label>
                                <select class="form-control @error('jumlah_pertemuan') is-invalid @enderror"
                                    id="jumlah_pertemuan" name="jumlah_pertemuan" required>
                                    <option value="">Pilih Jumlah</option>
                                    <option value="4"
                                        {{ old('jumlah_pertemuan', $course->jumlah_pertemuan) == 4 ? 'selected' : '' }}>4x
                                        Pertemuan</option>
                                    <option value="8"
                                        {{ old('jumlah_pertemuan', $course->jumlah_pertemuan) == 8 ? 'selected' : '' }}>8x
                                        Pertemuan</option>
                                    <option value="12"
                                        {{ old('jumlah_pertemuan', $course->jumlah_pertemuan) == 12 ? 'selected' : '' }}>
                                        12x Pertemuan</option>
                                </select>
                                @error('jumlah_pertemuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('swimming-course-management.index') }}"
                                    class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
