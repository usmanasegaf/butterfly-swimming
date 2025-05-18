@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img class="img-profile rounded-circle mb-3" src="{{ asset('admin_assets/img/undraw_profile.svg') }}" width="100">
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">{{ $user->role == 'admin' ? 'Administrator' : 'Pengguna' }}</span>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-6">
                        <p class="mb-0 text-muted">Tanggal Bergabung</p>
                        <p class="font-weight-bold">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0 text-muted">Terakhir Diperbarui</p>
                        <p class="font-weight-bold">{{ $user->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                @if ($user->role != 'admin')
                <hr>
                
                <div class="text-center">
                    <h6 class="font-weight-bold">Statistik Kursus</h6>
                    <div class="row mt-3">
                        <div class="col-4">
                            <p class="mb-0 text-muted">Total Kursus</p>
                            <p class="font-weight-bold">{{ $user->registrations()->count() }}</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-0 text-muted">Aktif</p>
                            <p class="font-weight-bold">{{ $user->registrations()->where('status', 'approved')->count() }}</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-0 text-muted">Selesai</p>
                            <p class="font-weight-bold">{{ $user->registrations()->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
