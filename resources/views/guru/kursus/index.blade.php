{{-- filepath: resources/views/guru/kursus/index.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Kursus yang Anda Pegang</h3>
<ul>
    @foreach($courses as $course)
        <li>
            <a href="{{ route('guru.kursus.show', $course->id) }}">{{ $course->name }}</a>
        </li>
    @endforeach
</ul>
@endsection