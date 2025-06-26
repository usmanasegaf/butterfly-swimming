@extends('layouts.app')
@section('title', 'Daftar Guru & Murid per Guru')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header">
        <h5 class="m-0 font-weight-bold text-primary">Daftar Guru & Murid Bimbingan</h5>
    </div>
    <div class="card-body">
        @foreach($gurus as $guru)
            <div class="mb-4">
                <h6>{{ $guru->name }} ({{ $guru->email }})</h6>
                <ul>
                    @forelse($guru->murids as $murid)
                        <li>{{ $murid->name }} ({{ $murid->email }})</li>
                    @empty
                        <li><em>Belum ada murid bimbingan</em></li>
                    @endforelse
                </ul>
            </div>
            <hr>
        @endforeach
    </div>
</div>
@endsection