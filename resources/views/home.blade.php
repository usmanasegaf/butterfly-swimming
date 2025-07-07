@extends('layouts.main')

@section('title', 'Butterfly Swimming Course - Kursus Renang Terbaik')

@push('styles')
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/pool1.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            margin-top: 56px;
        }

        .program-card {
            transition: transform 0.3s;
            height: 100%;
        }

        .program-card:hover {
            transform: translateY(-10px);
        }

        .testimonial-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
        }

        .testimonial-card {
            height: 100%;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }

        .cta-section {
            background-color: #0d6efd;
            color: white;
        }

        .stats-section {
            background-color: #f8f9fa;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0d6efd;
        }

        .stat-text {
            font-size: 1.1rem;
            color: rgb(0, 0, 0);
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4" style="color: #F8F8FF;">Belajar Berenang dengan Metode Terbaik</h1>
            <p class="lead mb-5">Butterfly Swimming Course menawarkan program kursus renang untuk semua usia dan tingkat
                kemampuan dengan pelatih profesional dan bersertifikat.</p>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">Daftar Sekarang</a>
                <a href="#program" class="btn btn-outline-light btn-lg px-5 py-3">Lihat Program</a>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
                    <p class="lead text-muted">Kami menawarkan pengalaman belajar renang terbaik dengan berbagai keunggulan
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="fas fa-medal feature-icon"></i>
                        <h4>Pelatih Profesional</h4>
                        <p class="text-muted">Semua pelatih kami adalah profesional bersertifikat dengan pengalaman
                            bertahun-tahun.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="fas fa-users feature-icon"></i>
                        <h4>Kelas Kecil</h4>
                        <p class="text-muted">Kami membatasi jumlah peserta per kelas untuk memastikan setiap peserta
                            mendapat perhatian yang cukup.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <i class="fas fa-water feature-icon"></i>
                        <h4>Fasilitas Modern</h4>
                        <p class="text-muted">Kolam renang dengan standar internasional dan fasilitas pendukung yang
                            lengkap.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-item p-3">
                        <div class="stat-number">500+</div>
                        <div class="stat-text">Siswa Aktif</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item p-3">
                        <div class="stat-number">15+</div>
                        <div class="stat-text">Pelatih Profesional</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item p-3">
                        <div class="stat-number">10+</div>
                        <div class="stat-text">Program Kursus</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item p-3">
                        <div class="stat-number">5</div>
                        <div class="stat-text">Lokasi Kolam</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Program Kursus Kami</h2>
                    <p class="lead text-muted">Pilih program yang sesuai dengan kebutuhan dan tingkat kemampuan Anda</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($programs as $program)
                    <div class="col-lg-4 col-md-6">
                        <div class="card program-card shadow h-100">
                            <img src="{{ asset('images/' . $program['image']) }}" class="card-img-top"
                                alt="{{ $program['name'] }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $program['name'] }}</h5>
                                    <span class="badge bg-primary">{{ $program['level'] }}</span>
                                </div>
                                <p class="card-text">{{ $program['description'] }}</p>
                                <p class="text-primary fw-bold">Rp {{ number_format($program['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('register') }}" class="btn btn-primary w-100">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Apa Kata Mereka?</h2>
                    <p class="lead text-muted">Testimoni dari para siswa dan orang tua yang telah mengikuti program kami</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6">
                        <div class="card testimonial-card shadow h-100">
                            <div class="card-body text-center p-4">
                                <img src="{{ asset('images/' . $testimonial['image']) }}" class="testimonial-img"
                                    alt="{{ $testimonial['name'] }}">
                                <h5>{{ $testimonial['name'] }}</h5>
                                <p class="text-muted mb-3">{{ $testimonial['role'] }}</p>
                                <div class="mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="card-text">"{{ $testimonial['message'] }}"</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container text-center py-4">
            <h2 class="fw-bold mb-4" style="color: #F8F8FF;">Siap Untuk Mulai Berenang?</h2>
            <p class="lead mb-4">Daftar sekarang dan mulai perjalanan berenang Anda bersama kami!</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Daftar Sekarang</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Memastikan gambar dimuat dengan benar
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.onerror = function() {
                    console.error('Error loading image:', this.src);
                    // Optional: set fallback image
                    this.src = '{{ asset('images/placeholder.jpg') }}';
                };
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
