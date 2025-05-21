@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('user.courses.index') }}" class="text-blue-500 hover:text-blue-700">
            &larr; Kembali ke daftar kursus
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $course->name }}</h1>
                    <div class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Level {{ $course->level }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="text-2xl font-bold text-gray-900">
                        Rp{{ number_format($course->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Deskripsi Kursus</h2>
                <p class="text-gray-700">{{ $course->description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Detail Kursus</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Durasi Kursus</div>
                                <div class="mt-1">{{ $course->duration }} minggu</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Sesi per Minggu</div>
                                <div class="mt-1">{{ $course->sessions_per_week }}x</div>
                            </div>
                            @if($course->instructor)
                            <div>
                                <div class="text-sm font-medium text-gray-500">Instruktur</div>
                                <div class="mt-1">{{ $course->instructor }}</div>
                            </div>
                            @endif
                            @if($course->max_participants)
                            <div>
                                <div class="text-sm font-medium text-gray-500">Kapasitas Maksimal</div>
                                <div class="mt-1">{{ $course->max_participants }} peserta</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-2">Jadwal</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if(isset($course->schedule) && !empty($course->schedule))
                            <ul class="space-y-2">
                                @foreach($course->schedule as $day => $time)
                                    <li>
                                        <span class="font-medium">{{ $day }}:</span> {{ $time }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">
                                Jadwal akan ditentukan setelah pendaftaran. Silakan hubungi admin untuk informasi lebih lanjut.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            @if(isset($course->requirements) && !empty($course->requirements))
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Persyaratan</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($course->requirements as $requirement)
                            <li>{{ $requirement }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="border-t border-gray-200 pt-6 mt-6">
                <div class="flex justify-end">
                    <a href="{{ route('user.registrations.create', ['course_id' => $course->id]) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded">
                        Daftar Kursus Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection