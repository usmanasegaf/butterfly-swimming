{{-- filepath: resources/views/guru/murid/index.blade.php --}}
@extends('layouts.app')
@section('content')
<h3>Daftar Murid Bimbingan</h3>
<a href="{{ route('guru.murid.create') }}">Tambah Murid</a>
<ul>
    @foreach($murids as $murid)
        <li>
            {{ $murid->name }} ({{ $murid->email }})
            <form action="{{ route('guru.murid.destroy', $murid->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </li>
    @endforeach
</ul>
@endsection