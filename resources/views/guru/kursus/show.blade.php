{{-- filepath: resources/views/guru/kursus/show.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Detail Kursus: {{ $course->name }}</h3>
<p>Level: {{ $course->level }}</p>
<p>Deskripsi: {{ $course->description }}</p>
<p>Harga: {{ $course->price }}</p>
<p>Durasi: {{ $course->duration }}</p>
<p>Jumlah Sesi/Minggu: {{ $course->sessions_per_week }}</p>
<p>Peserta Maksimal: {{ $course->max_participants }}</p>
<p>Instruktur: {{ $course->instructor }}</p>
@endsection