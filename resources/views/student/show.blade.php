@extends('layouts.app')

@section('title', 'Detail Murid')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(auth()->user() && auth()->user()->student)
    @foreach(auth()->user()->student->notifications as $notification)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-2">
            {{ $notification->data['message'] }}
        </div>
    @endforeach
    @endif
    <h1 class="text-2xl font-bold mb-4">Detail Murid: {{ $student->name }}</h1>
    <div class="mb-4">
        <strong>Email:</strong> {{ $student->email ?? '-' }}<br>
        <strong>Telepon:</strong> {{ $student->phone ?? '-' }}<br>
        <strong>Masa Aktif:</strong>
        @if($student->expired_at)
            {{ \Carbon\Carbon::parse($student->expired_at)->format('d M Y') }}
            @if($expired_in !== null)
                @if($expired_in < 0)
                    <span class="text-red-600">(Expired {{ abs($expired_in) }} hari lalu)</span>
                @elseif($expired_in <= 3)
                    <span class="text-yellow-600">(Akan expired {{ $expired_in }} hari lagi)</span>
                @else
                    <span class="text-green-600">(Aktif)</span>
                @endif
            @endif
        @else
            <span class="text-gray-500">Belum diatur</span>
        @endif
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-2">Jadwal Les Berikutnya</h2>
        @if($next_schedule)
            <div>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($next_schedule->date)->format('d M Y') }}<br>
                <strong>Jam:</strong> {{ \Carbon\Carbon::parse($next_schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($next_schedule->end_time)->format('H:i') }}<br>
                <strong>Lokasi:</strong> {{ $next_schedule->location }}
            </div>
        @else
            <span class="text-gray-500">Belum ada jadwal berikutnya</span>
        @endif
    </div>

    <a href="{{ route('student.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke daftar murid</a>
</div>
@endsection