{{-- filepath: resources/views/murid/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Profil Saya</h1>
    <div class="mb-4">
        <strong>Email:</strong> {{ auth()->user()->email ?? '-' }}<br>
        <strong>Telepon:</strong> {{ auth()->user()->phone ?? '-' }}<br>
        <strong>Masa Aktif:</strong>
        @if(auth()->user()->expired_at)
            {{ \Carbon\Carbon::parse(auth()->user()->expired_at)->format('d M Y') }}
        @else
            <span class="text-gray-500">Belum diatur</span>
        @endif
    </div>
    <a href="{{ route('murid.dashboard') }}" class="text-blue-600 hover:underline">&larr; Kembali ke dashboard</a>
</div>
@endsection