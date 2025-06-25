{{-- filepath: resources/views/admin/verification/guru_pending.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Guru Pending</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gurus as $guru)
            <tr>
                <td>{{ $guru->name }}</td>
                <td>{{ $guru->email }}</td>
                <td>
                    <form action="{{ route('admin.guru.verify', $guru) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Verifikasi guru ini?')">Verifikasi</button>
                    </form>
                    <form action="{{ route('admin.guru.reject', $guru) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak guru ini?')">Tolak</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection