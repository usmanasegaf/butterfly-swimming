@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Kursus Renang</h5>
                        <div>
                            @can('edit swimming course')
                                <a href="{{ route('swimming-course-management.edit', $course->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            <a href="{{ route('swimming-course-management.index') }}"
                                class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h3>{{ $course->name }}</h3>
                                <span class="badge bg-info">{{ $course->level }}</span>
                                @if ($course->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-aktif</span>
                                @endif
                                <h4 class="mt-3">Rp {{ number_format($course->price, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Deskripsi</h5>
                                <p>{{ $course->description }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Durasi:</strong> {{ $course->duration }} minggu
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Jumlah Pertemuan:</strong> {{ $course->jumlah_pertemuan }}x
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Info Tambahan</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Dibuat pada</th>
                                            <td>{{ $course->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir diupdate</th>
                                            <td>{{ $course->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @can('delete swimming course')
                            <div class="mt-4">
                                <form action="{{ route('swimming-course-management.destroy', $course->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus Kursus</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
