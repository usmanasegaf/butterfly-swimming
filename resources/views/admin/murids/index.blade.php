@extends('layouts.app')

@section('title', 'Manajemen Murid')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Murid </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.murids.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search">Cari Murid (Nama/Email):</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Cari nama atau email...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="guru_id">Guru Pembimbing:</label>
                        <select name="guru_id" id="guru_id" class="form-control">
                            <option value="">Semua Guru</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="course_id">Kursus Ditugaskan:</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">Semua Kursus</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mr-2">Terapkan Filter</button>
                        <a href="{{ route('admin.murids.index') }}" class="btn btn-secondary">Reset Filter</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Murid </h6>
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

            @if ($murids->isEmpty())
                <div class="alert alert-info">
                    Tidak ada murid  yang ditemukan dengan filter yang dipilih.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Guru Pembimbing</th>
                                <th>Kursus Ditugaskan</th>
                                <th>Ditugaskan Sejak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($murids as $murid)
                                <tr>
                                    <td>{{ $murid->id }}</td>
                                    <td>{{ $murid->name }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>
                                        @if ($murid->gurus->isNotEmpty())
                                            {{ $murid->gurus->first()->name }}
                                        @else
                                            <span class="text-muted">Belum Ditugaskan</span>
                                        @endif
                                    </td>
                                    <td>{{ $murid->swimmingCourse->name ?? 'Belum Ditugaskan' }}</td>
                                    <td>{{ $murid->course_assigned_at ? \Carbon\Carbon::parse($murid->course_assigned_at)->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Aksi Murid">
                                            @can('manage active students')
                                            <a href="{{ route('admin.murids.edit', $murid->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.murids.destroy', $murid->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus murid ini? Ini akan menghapus semua data terkait murid.')" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $murids->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
