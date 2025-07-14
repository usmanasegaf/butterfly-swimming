{{-- filepath: resources/views/guru/verification/murid_pending.blade.php --}}
@extends('layouts.app')
@section('title', 'Verifikasi Murid')
@section('content')
<div class="container">
    <h4>Daftar Murid Pending</h4>
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
            @foreach($murids as $murid)
            <tr>
                <td>{{ $murid->name }}</td>
                <td>{{ $murid->email }}</td>
                <td>
                    <form action="{{ route('guru.murid.verify', $murid) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Verifikasi murid ini?')">Verifikasi</button>
                    </form>
                    <form action="{{ route('guru.murid.reject', $murid) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak murid ini?')">Tolak</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection