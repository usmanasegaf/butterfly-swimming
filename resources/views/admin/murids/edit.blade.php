@extends('layouts.app')

@section('title', 'Edit Murid ')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Murid : {{ $murid->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Murid</h6>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.murids.update', $murid->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Murid:</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $murid->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Murid:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $murid->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="guru_id">Guru Pembimbing:</label>
                    <select name="guru_id" id="guru_id" class="form-control @error('guru_id') is-invalid @enderror">
                        <option value="">-- Tidak Ada Guru --</option>
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id', $currentGuru->id ?? '') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="swimming_course_id">Kursus Ditugaskan:</label>
                    <select name="swimming_course_id" id="swimming_course_id" class="form-control @error('swimming_course_id') is-invalid @enderror">
                        <option value="">-- Tidak Ada Kursus --</option>
                        @foreach ($swimmingCourses as $course)
                            <option value="{{ $course->id }}" {{ old('swimming_course_id', $murid->swimming_course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }} ({{ $course->level }})</option>
                        @endforeach
                    </select>
                    @error('swimming_course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.murids.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
