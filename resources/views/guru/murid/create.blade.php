{{-- filepath: resources/views/guru/murid/create.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Tambah Murid ke Bimbingan</h3>
<form method="POST" action="{{ route('guru.murid.store') }}">
    @csrf
    <select name="murid_id" required>
        @foreach($murids as $murid)
            <option value="{{ $murid->id }}">{{ $murid->name }} ({{ $murid->email }})</option>
        @endforeach
    </select>
    <button type="submit">Tambah</button>
</form>
@endsection