@extends('layouts.app')

@section('title', 'Verifikasi Murid')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Murid Menunggu Verifikasi</h6>
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
                    Tidak ada murid yang menunggu verifikasi saat ini.
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
                            @foreach ($murids as $murid)
                                <tr>
                                    <td>{{ $murid->name }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>{{ $murid->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Aksi Verifikasi">
                                            <form action="{{ route('guru.murid.verify', $murid->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                            </form>
                                            <form action="{{ route('guru.murid.reject', $murid->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK murid ini dan MEMBLOKIR emailnya? Email ini tidak akan bisa digunakan lagi untuk mendaftar.')">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">Blokir Email (Tolak)</button>
                                            </form>
                                            <form action="{{ route('guru.murid.delete', $murid->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS data murid ini secara permanen? Murid ini bisa mendaftar lagi dengan email yang sama.')">
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
