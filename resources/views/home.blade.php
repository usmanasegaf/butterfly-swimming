@extends('layouts.main')

@section('title', 'Beranda - Butterfly Swimming Course')

@section('content')
    <!-- Hero Section with Carousel -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/pool1.jpg') }}" class="d-block w-100" alt="Kolam Renang">
                <div class="carousel-caption">
                    <h1>Selamat Datang di Butterfly Swimming Course</h1>
                    <p>Kursus renang terbaik untuk semua usia di TKI Sport Center</p>
                    <a href="/daftar" class="btn btn-primary btn-lg">Daftar Sekarang</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/pool2.jpg') }}" class="d-block w-100" alt="Pelatihan Renang">
                <div class="carousel-caption">
                    <h1>Pelatih Profesional & Bersertifikat</h1>
                    <p>Dibimbing oleh pelatih renang berpengalaman dan tersertifikasi</p>
                    <a href="/pelatih" class="btn btn-primary btn-lg">Lihat Pelatih</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/pool3.jpg') }}" class="d-block w-100" alt="Program Renang">
                <div class="carousel-caption">
                    <h1>Program untuk Semua Tingkatan</h1>
                    <p>Dari pemula hingga atlet profesional</p>
                    <a href="/program" class="btn btn-primary btn-lg">Lihat Program</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Program Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Program Kursus Renang</h2>
                <p class="lead">Pilih program yang sesuai dengan kebutuhan Anda</p>
            </div>
            <div class="row g-4">
                @foreach($programs as $program)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('images/' . $program['image']) }}" class="card-img-top"
                                alt="{{ $program['name'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $program['name'] }}</h5>
                                <p class="card-text">{{ $program['description'] }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">{{ $program['level'] }}</span>
                                    <span class="text-primary fw-bold">Rp
                                        {{ number_format($program['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="/program/{{ $program['id'] }}" class="btn btn-outline-primary w-100">Detail Program</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="/program" class="btn btn-primary">Lihat Semua Program</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
                <p class="lead">Keunggulan Butterfly Swimming Course di TKI Sport Center</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <h5>Pelatih Bersertifikat</h5>
                        <p>Dibimbing oleh pelatih profesional dengan sertifikasi nasional dan internasional</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-water fa-2x"></i>
                        </div>
                        <h5>Fasilitas Modern</h5>
                        <p>Kolam renang standar olimpiade dengan teknologi pemurnian air terkini</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h5>Kelas Kecil</h5>
                        <p>Maksimal 5 siswa per kelas untuk memastikan perhatian individual</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <h5>Keamanan Prioritas</h5>
                        <p>Pengawas keselamatan terlatih dan protokol keamanan ketat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Testimoni</h2>
                <p class="lead">Apa kata mereka tentang kursus renang kami</p>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials as $index => $testimonial)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-5 text-center">
                                            <div class="mb-4">
                                                <i class="fas fa-quote-left fa-3x text-primary opacity-25"></i>
                                            </div>
                                            <p class="lead mb-4">{{ $testimonial['message'] }}</p>
                                            <div class="d-flex justify-content-center mb-3">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i < $testimonial['rating'] ? 'text-warning' : 'text-muted' }} me-1"></i>
                                                @endfor
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img src="{{ asset('images/' . $testimonial['image']) }}"
                                                    class="rounded-circle me-3" width="60" height="60"
                                                    alt="{{ $testimonial['name'] }}">
                                                <div class="text-start">
                                                    <h5 class="mb-1">{{ $testimonial['name'] }}</h5>
                                                    <p class="mb-0 text-muted">{{ $testimonial['role'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-primary rounded-circle"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-primary rounded-circle"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="fw-bold">Siap untuk Mulai Berenang?</h2>
                    <p class="lead mb-0">Daftar sekarang dan dapatkan diskon 15% untuk pendaftaran pertama Anda!</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/daftar" class="btn btn-light btn-lg">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection