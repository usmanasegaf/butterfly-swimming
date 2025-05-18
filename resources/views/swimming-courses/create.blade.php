@extends('layouts.app')

@section('title', 'Tambah Kursus Renang')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Kursus Renang Baru</h6>
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

        <form action="{{ route('swimming-courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Kursus <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="level">Level <span class="text-danger">*</span></label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="">Pilih Level</option>
                            <option value="Pemula" {{ old('level') == 'Pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="Menengah" {{ old('level') == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="Lanjutan" {{ old('level') == 'Lanjutan' ? 'selected' : '' }}>Lanjutan</option>
                            <option value="Semua Level" {{ old('level') == 'Semua Level' ? 'selected' : '' }}>Semua Level</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Durasi (minggu) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sessions_per_week">Sesi per Minggu <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sessions_per_week" name="sessions_per_week" value="{{ old('sessions_per_week') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_participants">Maksimal Peserta</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="instructor">Instruktur</label>
                        <input type="text" class="form-control" id="instructor" name="instructor" value="{{ old('instructor') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Gambar</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        <small class="form-text text-muted">Upload gambar dengan format JPG, PNG, atau GIF. Maksimal 2MB.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('swimming-courses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
