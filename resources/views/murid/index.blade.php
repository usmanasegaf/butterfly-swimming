{{-- filepath: resources/views/murid/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Murid')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Dashboard Murid</h1>

    <div class="mb-6">
        <h2 class="text-lg font-semibold">Sisa Waktu Les</h2>
        @if($expired_at)
            <p>
                Expired pada: <strong>{{ \Carbon\Carbon::parse($expired_at)->format('d-m-Y') }}</strong><br>
                Sisa hari: <strong>{{ $sisa_hari > 0 ? $sisa_hari . ' hari' : 'Sudah expired' }}</strong>
            </p>
        @else
            <p>Belum ada data expired.</p>
        @endif
    </div>

    <div class="mb-6">
        <h2 class="text-lg font-semibold">Jadwal Les Selanjutnya</h2>
        @if($jadwal_selanjutnya)
            <p>
                Tanggal: <strong>{{ $jadwal_selanjutnya->tanggal }}</strong><br>
                Jam: <strong>{{ $jadwal_selanjutnya->jam }}</strong><br>
                Lokasi: <strong>{{ $jadwal_selanjutnya->location->name ?? '-' }}</strong>
            </p>
        @else
            <p>Tidak ada jadwal les selanjutnya.</p>
        @endif
    </div>
</div>
@endsection