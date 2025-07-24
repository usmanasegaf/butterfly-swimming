{{-- filepath: resources/views/murid/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Murid')

@section('content')
    <div class="container">

        <p>Selamat datang, {{ $user->name }}!</p>


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Kursus Aktif</h6>
            </div>
            <div class="card-body">
                @if ($user->swimmingCourse)
                    <p><strong>Kursus Anda:</strong> {{ $user->swimmingCourse->name }} ({{ $user->swimmingCourse->level }})
                    </p>

                    {{-- <<< PENAMBAHAN BLOK INI >>> --}}
                    <p><strong>Progres Pertemuan:</strong> {{ $user->pertemuan_ke }} dari
                        {{ $user->jumlah_pertemuan_paket }} pertemuan telah diikuti.</p>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ ($user->pertemuan_ke / $user->jumlah_pertemuan_paket) * 100 }}%"
                            aria-valuenow="{{ $user->pertemuan_ke }}" aria-valuemin="0"
                            aria-valuemax="{{ $user->jumlah_pertemuan_paket }}"></div>
                    </div>
                    {{-- <<< AKHIR PENAMBAHAN >>> --}}

                    <p><strong>Sisa Waktu Les Berdasarkan Durasi:</strong> {{ $user->remaining_lesson_days ?? 'N/A' }}</p>
                    <small class="text-muted">*Kursus akan berakhir jika kuota pertemuan atau sisa waktu les telah habis,
                        mana saja yang tercapai lebih dulu.</small>
                @else
                    <p>Anda tidak sedang mengikuti kursus aktif saat ini.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
