@extends('layouts.app')

@section('title', 'Detail Pendaftaran Kursus')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Detail Pendaftaran Kursus</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $registration->swimmingCourse->name }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        Level: {{ $registration->swimmingCourse->level }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full 
                        @if($registration->status == 'Approved') bg-green-100 text-green-800 
                        @elseif($registration->status == 'Rejected') bg-red-100 text-red-800 
                        @elseif($registration->status == 'Cancelled') bg-gray-100 text-gray-800 
                        @else bg-yellow-100 text-yellow-800 @endif">
                        Status: {{ $registration->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Informasi Kursus</h3>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">{{ $registration->swimmingCourse->description }}</p>
                    </div>

                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Durasi:</span>
                        <span class="text-sm text-gray-600">{{ $registration->swimmingCourse->duration }} minggu</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Sesi per Minggu:</span>
                        <span class="text-sm text-gray-600">{{ $registration->swimmingCourse->sessions_per_week }}x</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Instruktur:</span>
                        <span class="text-sm text-gray-600">{{ $registration->swimmingCourse->instructor ?? 'Belum ditentukan' }}</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Maksimal Peserta:</span>
                        <span class="text-sm text-gray-600">{{ $registration->swimmingCourse->max_participants ?? 'Tidak ada batasan' }}</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Detail Pendaftaran</h3>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Tanggal Pendaftaran:</span>
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Tanggal Mulai:</span>
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($registration->start_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Tanggal Selesai:</span>
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($registration->end_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Status Pembayaran:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($registration->payment_status == 'Paid') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $registration->payment_status }}
                        </span>
                    </div>
                    
                    <div class="mb-1">
                        <span class="text-sm font-medium text-gray-700">Biaya:</span>
                        <span class="text-sm text-gray-600">Rp{{ number_format($registration->swimmingCourse->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($registration->notes)
                <div class="mt-4 p-4 bg-gray-50 rounded">
                    <h3 class="text-md font-medium text-gray-900 mb-1">Catatan Tambahan</h3>
                    <p class="text-sm text-gray-600">{{ $registration->notes }}</p>
                </div>
            @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
            <a href="{{ route('my-registrations') }}" class="text-blue-600 hover:text-blue-900">
                &larr; Kembali ke Daftar Pendaftaran
            </a>
            
            @if($registration->status == 'Pending')
                <form action="{{ route('registration.cancel', $registration->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded" 
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                        Batalkan Pendaftaran
                    </button>
                </form>
            @elseif($registration->status == 'Approved' && $registration->payment_status == 'Unpaid')
                <!-- Jika fitur pembayaran sudah ada -->
                <a href="#" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    Lakukan Pembayaran
                </a>
            @endif
        </div>
    </div>
</div>
@endsection