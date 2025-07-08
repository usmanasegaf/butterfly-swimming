@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <h2>Selamat datang, {{ auth()->user()->name }}!</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Murid Bimbingan</h5>
                            <p class="card-text display-4">{{ $murid_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Jadwal Kursus</h5>
                            <p class="card-text display-4">{{ $jadwal_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Absensi Hari Ini</h5>
                            <p class="card-text display-4">{{ $absensi_hari_ini }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="mt-4">Jadwal Terdekat</h4>
            @if ($jadwal_terdekat)
                <ul>
                    <li>
                        {{ $jadwal_terdekat->tanggal }} - {{ $jadwal_terdekat->jam }}<br>
                        Kursus: {{ $jadwal_terdekat->swimmingCourse->name ?? '-' }}<br>
                        Lokasi: {{ $jadwal_terdekat->location->name ?? '-' }}
                    </li>
                </ul>
            @else
                <p>Tidak ada jadwal terdekat.</p>
            @endif
        </div>
    </div>
@endsection
