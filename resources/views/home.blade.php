@extends('layouts.main')

@section('title', 'Butterfly Swimming Course - Kursus Renang Terbaik')

@push('styles')
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/pool1.1.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            margin-top: 56px;
            /* Untuk menggeser konten di bawah navbar fixed-top */
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

        /* CSS tambahan untuk padding section agar tidak tertutup navbar */
        .section-padding {
            padding-top: 100px;
            /* Sesuaikan dengan tinggi navbar Anda */
            padding-bottom: 60px;
        }

        /* Styling khusus untuk bagian Tentang Kami */
        .about-us-content {
            background-color: #ffffff;
            /* Latar belakang putih */
            padding: 40px;
            /* Padding di dalam kotak */
            border-radius: 15px;
            /* Sudut membulat */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            /* Bayangan lembut */
            text-align: justify;
            /* Teks rata kiri-kanan */
        }

        .about-us-content p {
            font-size: 1.15rem;
            /* Ukuran font sedikit lebih besar dari sebelumnya */
            line-height: 1.8;
            /* Jarak antar baris untuk keterbacaan lebih baik */
            margin-bottom: 1.5rem;
            /* Jarak antar paragraf sedikit lebih besar */
        }

        .about-us-content p:last-child {
            margin-bottom: 0;
            /* Hapus margin bawah pada paragraf terakhir */
        }
    </style>
@endpush

@section('content')

    <!-- Bagian Beranda -->
    <section id="beranda" class="hero-section">
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
    <section class="py-5 section-padding">
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
                        <h4>Kelas Privat</h4>
                        <p class="text-muted">Kami menerapkan sistem privat satu guru satu murid per jadwal, agar setiap peserta mendapatkan bimbingan yang optimal dan sesuai dengan kebutuhan masing-masing.</p>
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
    <section class="stats-section py-5 section-padding">
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
                        <div class="stat-number">25+</div>
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
                        <div class="stat-number">5+</div>
                        <div class="stat-text">Lokasi Kolam</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-5 section-padding">
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

    <!-- Bagian Tentang Kami -->
    <section id="tentang" class="section-padding bg-white">
        <div class="container">
            <div class="row justify-content-center text-center mb-1">
                <div class="col-lg-8 col-md-2">
                    <h2 class="fw-bold text-primary border-bottom pb-2">
                        Tentang Butterfly Swimming Course
                    </h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="about-us-content p-4 p-md-5 bg-light border rounded-4 shadow-sm">
                        <p class="lead text-dark">
                            <strong>Butterfly Swimming Course</strong> didirikan dengan visi untuk menjadi pusat pelatihan
                            renang
                            terkemuka yang memberikan pengalaman belajar yang menyenangkan dan efektif bagi semua tingkatan
                            usia.
                        </p>
                        <p class="text-dark">
                            Kami percaya bahwa renang adalah keterampilan hidup yang penting, dan kami berkomitmen untuk
                            membantu setiap individu mencapai potensi maksimal mereka di dalam air. Dengan <strong>fasilitas
                                modern</strong> dan
                            <strong>tim pelatih bersertifikat</strong>, kami memastikan lingkungan belajar yang aman,
                            suportif, dan inspiratif.
                        </p>
                        <p class="fw-semibold text-dark mb-0">
                            🌊 Bergabunglah dengan kami dan rasakan perbedaan dalam perjalanan renang Anda!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section id="testimoni" class="py-5 bg-light section-padding">
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
    <section class="cta-section py-5 section-padding">
        <div class="container text-center py-4">
            <h2 class="fw-bold mb-4" style="color: #F8F8FF;">Siap Untuk Mulai Berenang?</h2>
            <p class="lead mb-4">Daftar sekarang dan mulai perjalanan berenang Anda bersama kami!</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Daftar Sekarang</a>
        </div>
    </section>

    <!-- Bagian Kontak -->
    <section id="kontak" class="section-padding bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Hubungi Kami</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="text-center lead mb-4">
                        Kami siap menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami melalui informasi di bawah
                        ini:
                    </p>
                    <ul class="list-unstyled text-center fs-5">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                            Jl. Margacinta No.98, Cijaura, Kec. Buahbatu, Kota Bandung, Jawa Barat 40287
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-3 text-primary"></i>
                            081320111868
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            info@butterflyswimming.com
                        </li>
                        <li class="mb-3">
                            <i class="fab fa-instagram me-3 text-primary"></i>
                            @butterflyswimmingcourse
                        </li>
                    </ul>
                    <div class="text-center mt-5">
                        <a href="{{ route('register-course') }}" class="btn btn-primary btn-lg">Daftar Kursus
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1); // Get the ID without '#'
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    // Calculate offset for fixed navbar
                    const navbarHeight = document.querySelector('.navbar.fixed-top').offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - navbarHeight;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endpush
