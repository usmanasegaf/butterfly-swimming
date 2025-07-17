@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('title', 'Buat Akun Murid Bimbingan Baru')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Buat Akun Murid Bimbingan Baru</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Pembuatan Akun Murid</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('guru.murid.store_account') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Nama Murid:</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('guru.murid.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Buat Akun Murid</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
