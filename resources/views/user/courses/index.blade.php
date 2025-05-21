@extends('layouts.app')

@section('title', 'Kursus Renang Tersedia')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kursus Renang Tersedia</h1>
        <a href="{{ route('user.registrations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            Daftar Kursus Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($courses->isEmpty())
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <p>Tidak ada kursus renang yang tersedia saat ini.</p>
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline mt-2 inline-block">
                Kembali ke Dashboard
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-xl font-bold text-gray-900">{{ $course->name }}</h2>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Level {{ $course->level }}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3 h-18">{{ $course->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                            <div>
                                <span class="text-gray-500">Durasi:</span>
                                <span class="font-medium">{{ $course->duration }} minggu</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Sesi:</span>
                                <span class="font-medium">{{ $course->sessions_per_week }}x / minggu</span>
                            </div>
                            @if($course->instructor)
                            <div>
                                <span class="text-gray-500">Instruktur:</span>
                                <span class="font-medium">{{ $course->instructor }}</span>
                            </div>
                            @endif
                            @if($course->max_participants)
                            <div>
                                <span class="text-gray-500">Kapasitas:</span>
                                <span class="font-medium">{{ $course->max_participants }} peserta</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-lg font-bold text-gray-900">
                                Rp{{ number_format($course->price, 0, ',', '.') }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('user.courses.show', $course->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">
                                    Detail
                                </a>
                                <a href="{{ route('user.registrations.create', ['course_id' => $course->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-3 rounded text-sm">
                                    Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection