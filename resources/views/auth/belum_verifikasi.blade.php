{{-- filepath: resources/views/auth/belum_verifikasi.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Status Akun Belum Terverifikasi</h2>
    <p>
        Akun Anda saat ini <b>belum terverifikasi</b>.<br>
        Silakan login kembali secara berkala untuk melihat apakah akun Anda sudah diverifikasi oleh pihak terkait.<br>
        Jika membutuhkan bantuan, silakan hubungi admin atau guru Anda.
    </p>
    <a href="{{ route('login') }}" class="btn btn-primary mt-3">Kembali ke Login</a>
</div>
@endsection