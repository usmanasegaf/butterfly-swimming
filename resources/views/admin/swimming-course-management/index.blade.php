@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manajemen Kursus Renang</h5>
                    @can('create swimming course')
                    <a href="{{ route('swimming-course-management.create') }}" class="btn btn-primary btn-sm">Tambah Kursus Baru</a>
                    @endcan
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Level</th>
                                    <th>Harga</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                <tr>
                                    <td>{{ $course->id }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->level }}</td>
                                    <td>Rp {{ number_format($course->price, 0, ',', '.') }}</td>
                                    <td>{{ $course->duration }} minggu</td>
                                    <td>
                                        @if($course->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('swimming-course-management.show', $course->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            
                                            @can('edit swimming course')
                                            <a href="{{ route('swimming-course-management.edit', $course->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            @endcan
                                            
                                            @can('delete swimming course')
                                            <form action="{{ route('swimming-course-management.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data kursus</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection