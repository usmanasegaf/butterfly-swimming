@extends('layouts.app')

@section('title', 'Daftar Murid')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Daftar Murid</h1>
    <a href="{{ route('student.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Murid</a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4">Nama</th>
                <th class="py-2 px-4">Expired</th>
                <th class="py-2 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td class="py-2 px-4">{{ $student->name }}</td>
                <td class="py-2 px-4">
                    @if($student->expired_at)
                        {{ \Carbon\Carbon::parse($student->expired_at)->diffForHumans() }}
                    @else
                        -
                    @endif
                </td>
                <td class="py-2 px-4">
                    <a href="{{ route('student.show', $student->id) }}" class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection