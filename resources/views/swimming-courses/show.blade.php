@extends('layouts.app')

@section('title', 'Detail Kursus Renang')

@section('page-actions')
<a href="{{ route('swimming-courses.edit', $course->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
    <i class="fas fa-edit fa-sm text-white-50"></i> Edit Kursus
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kursus</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="font-weight-bold">{{ $course->name }}</h4>
                        <p class="mb-2">
                            <span class="badge badge-primary">{{ $course->level }}</span>
                            <span class="badge badge-{{ $course->is_active ? 'success' : 'secondary' }}">{{ $course->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                        </p>
                        <p class="text-primary font-weight-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($course->image)
                            <img src="{{ asset('images/' . $course->image) }}" alt="{{ $course->name }}" class="img-fluid rounded">
                        @else
                            <div class="bg-light rounded text-center py-5">
                                <i class="fas fa-image fa-3x text-secondary"></i>
                                <p class="mt-2 text-muted">Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <h5 class="font-weight-bold">Deskripsi</h5>
                <p>{{ $course->description }}</p>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Detail Kursus</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Durasi</td>
                                <td>: {{ $course->duration }} minggu</td>
                            </tr>
                            <tr>
                                <td>Sesi per Minggu</td>
                                <td>: {{ $course->sessions_per_week }} sesi</td>
                            </tr>
                            <tr>
                                <td>Total Sesi</td>
                                <td>: {{ $course->duration * $course->sessions_per_week }} sesi</td>
                            </tr>
                            <tr>
                                <td>Maksimal Peserta</td>
                                <td>: {{ $course->max_participants ?? 'Tidak dibatasi' }}</td>
                            </tr>
                            <tr>
                                <td>Instruktur</td>
                                <td>: {{ $course->instructor ?? 'Belum ditentukan' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Informasi Tambahan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Dibuat pada</td>
                                <td>: {{ $course->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Diperbarui pada</td>
                                <td>: {{ $course->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Total Pendaftar</td>
                                <td>: {{ $course->registrations()->count() }} orang</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Terbaru</h6>
            </div>
            <div class="card-body">
                @if($course->registrations()->count() > 0)
                    <div class="list-group">
                        @foreach($course->registrations()->with('user')->latest()->take(5)->get() as $registration)
                            <a href="{{ route('registrations.show', $registration->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $registration->user->name }}</h6>
                                    <small>{{ $registration->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">Mulai: {{ date('d M Y', strtotime($registration->start_date)) }}</p>
                                <small class="text-muted">
                                    Status: 
                                    @if($registration->status == 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($registration->status == 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($registration->status == 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">Selesai</span>
                                    @endif
                                </small>
                            </a>
                        @endforeach
                    </div>
                    
                    @if($course->registrations()->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('registrations.index') }}" class="btn btn-sm btn-primary">Lihat Semua Pendaftaran</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">Belum ada pendaftaran untuk kursus ini</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('swimming-courses.edit', $course->id) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-edit fa-sm"></i> Edit Kursus
                </a>
                <form action="{{ route('swimming-courses.destroy', $course->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                        <i class="fas fa-trash fa-sm"></i> Hapus Kursus
                    </button>
                </form>
                <a href="{{ route('swimming-courses.index') }}" class="btn btn-secondary btn-block mt-2">
                    <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
