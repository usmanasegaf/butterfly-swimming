@extends('layouts.app')

@section('title', 'Verifikasi Guru')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Guru Menunggu Verifikasi</h6>
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

            @if ($gurus->isEmpty())
                <div class="alert alert-info">
                    Tidak ada guru yang menunggu verifikasi saat ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gurus as $guru)
                                <tr>
                                    <td>{{ $guru->name }}</td>
                                    <td>{{ $guru->email }}</td>
                                    <td>{{ $guru->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Aksi Verifikasi">
                                            <form action="{{ route('admin.guru.verify', $guru->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                            </form>
                                            <form action="{{ route('admin.guru.reject', $guru->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK guru ini dan MEMBLOKIR emailnya? Email ini tidak akan bisa digunakan lagi untuk mendaftar.')">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">Blokir Email (Tolak)</button>
                                            </form>
                                            <form action="{{ route('admin.guru.delete', $guru->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS data guru ini secara permanen? Guru ini bisa mendaftar lagi dengan email yang sama.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus Data</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
