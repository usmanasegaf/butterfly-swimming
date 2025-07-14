{{-- filepath: resources/views/guru/murid/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Murid Bimbingan  ')
@section('content')
    <h4>Daftar Murid Bimbingan</h4>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('guru.murid.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Murid ke Bimbingan
    </a>

    <ul>
        @forelse($murids as $murid)
            <li>
                {{ $murid->name }} ({{ $murid->email }})

                {{-- Tampilkan informasi kursus yang ditugaskan --}}
                @if ($murid->swimmingCourse)
                    - **Kursus:** {{ $murid->swimmingCourse->name }}
                    (Sisa Waktu: {{ $murid->remaining_lesson_days ?? 'N/A' }})
                @else
                    - Belum ada kursus ditugaskan.
                @endif

                {{-- Tombol untuk menugaskan kursus --}}
                <a href="{{ route('guru.murid.assign_course_form', $murid->id) }}">Tugaskan Kursus</a>

                {{-- Form Hapus --}}
                <form action="{{ route('guru.murid.destroy', $murid->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus murid ini dari bimbingan?');">Hapus</button>
                </form>
            </li>
        @empty
            <li>Tidak ada murid bimbingan yang ditemukan.</li>
        @endforelse
    </ul>
@endsection
