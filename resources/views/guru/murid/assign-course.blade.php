@extends('layouts.app')

@section('title', 'Tugaskan Kursus ke Murid')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tugaskan Kursus ke Murid: {{ $murid->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Penugasan Kursus</h6>
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guru.murid.assign_course', $murid->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="swimming_course_id">Pilih Kursus Renang:</label>
                    <select name="swimming_course_id" id="swimming_course_id" class="form-control @error('swimming_course_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kursus --</option>
                        @foreach ($availableCourses as $course)
                            <option value="{{ $course->id }}" {{ old('swimming_course_id', $currentRegistration->swimming_course_id ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->level }}) - Rp{{ number_format($course->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('swimming_course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">Tanggal Mulai Kursus:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $currentRegistration->start_date ?? date('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">Tanggal Selesai Kursus:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $currentRegistration->end_date ?? '') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('guru.murid.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Tugaskan Kursus</button>
                </div>
            </form>
        </div>
    </div>

    @if ($currentRegistration)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kursus Aktif Murid Saat Ini</h6>
        </div>
        <div class="card-body">
            <p><strong>Kursus:</strong> {{ $currentRegistration->swimmingCourse->name ?? 'N/A' }}</p>
            <p><strong>Level:</strong> {{ $currentRegistration->swimmingCourse->level ?? 'N/A' }}</p>
            <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($currentRegistration->start_date)->format('d M Y') }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($currentRegistration->end_date)->format('d M Y') }}</p>
            <p><strong>Status:</strong> <span class="badge badge-success">{{ $currentRegistration->status }}</span></p>
            <p><strong>Biaya:</strong> Rp{{ number_format($currentRegistration->biaya, 0, ',', '.') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
