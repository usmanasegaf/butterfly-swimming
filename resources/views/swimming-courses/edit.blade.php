@extends('layouts.app')

@section('title', 'Edit Kursus Renang')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Kursus Renang</h6>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('swimming-courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Kursus <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $course->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="level">Level <span class="text-danger">*</span></label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="">Pilih Level</option>
                            <option value="Pemula" {{ old('level', $course->level) == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="Menengah" {{ old('level', $course->level) == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="Lanjutan" {{ old('level', $course->level) == 'Lanjutan' ? 'selected' : '' }}>Lanjutan</option>
                            <option value="Semua Level" {{ old('level', $course->level) == 'Semua Level' ? 'selected' : '' }}>Semua Level</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $course->price) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Durasi (minggu) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sessions_per_week">Sesi per Minggu <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sessions_per_week" name="sessions_per_week" value="{{ old('sessions_per_week', $course->sessions_per_week) }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_participants">Maksimal Peserta</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants', $course->max_participants) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="instructor">Instruktur</label>
                        <input type="text" class="form-control" id="instructor" name="instructor" value="{{ old('instructor', $course->instructor) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Gambar</label>
                        @if($course->image)
                            <div class="mb-2">
                                <img src="{{ asset('images/' . $course->image) }}" alt="{{ $course->name }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control-file" id="image" name="image">
                        <small class="form-text text-muted">Upload gambar baru untuk mengganti gambar yang ada. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('swimming-courses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
