@extends('layouts.app')
@section('title', 'Dashboard Murid')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            Selamat datang di Dashboard Murid!
        </div>
    </div>
    @if ($expired_at)
        <div>
            <strong>Waktu Expired:</strong> {{ $expired_at->format('d-m-Y') }}<br>
            <strong>Sisa Hari Les:</strong> {{ $sisa_hari > 0 ? $sisa_hari . ' hari' : 'Sudah expired' }}
        </div>
    @endif

    @if ($jadwal_selanjutnya)
        <div>
            <strong>Jadwal Les Selanjutnya:</strong><br>
            Tanggal: {{ $jadwal_selanjutnya->tanggal }}<br>
            Jam: {{ $jadwal_selanjutnya->jam }}<br>
            Lokasi: {{ $jadwal_selanjutnya->location->name ?? '-' }}
        </div>
    @else
        <div>Tidak ada jadwal les selanjutnya.</div>
    @endif
    @if ($jadwal_selanjutnya && $jadwal_selanjutnya->tanggal == now()->toDateString())
        <script>
            alert('Jangan lupa, Anda ada jadwal les hari ini!');
        </script>
    @endif
@endsection
