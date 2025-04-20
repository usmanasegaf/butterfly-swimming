@extends('layouts.main')

@section('title', 'Beranda - Butterfly Swimming Course')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                {{-- Pastikan path gambar benar dan gambar ada --}}
                <img src="{{ asset('images/pool1.jpg') }}" class="d-block w-100" alt="Kolam Renang TKI Sport Center"
                    onerror="this.onerror=null; this.src='https://placehold.co/1200x600/0066cc/FFF?text=Kolam+Renang';">
                <div class="carousel-caption d-none d-md-block"> {{-- Sembunyikan caption di layar kecil jika terlalu ramai
                    --}}
                    <h1>Selamat Datang di Butterfly Swimming Course</h1>
                    <p>Kursus renang terbaik untuk semua usia di TKI Sport Center</p>
                    <a href="/daftar" class="btn btn-primary btn-lg">Daftar Sekarang</a>
                </div>
            </div>
            <div class="carousel-item">
                {{-- Pastikan path gambar benar dan gambar ada --}}
                <img src="{{ asset('images/pool2.jpg') }}" class="d-block w-100" alt="Pelatihan Renang Profesional"
                    onerror="this.onerror=null; this.src='https://placehold.co/1200x600/00aaff/FFF?text=Pelatih+Profesional';">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Pelatih Profesional & Bersertifikat</h1>
                    <p>Dibimbing oleh pelatih renang berpengalaman dan tersertifikasi</p>
                    <a href="/pelatih" class="btn btn-primary btn-lg">Lihat Pelatih</a>
                </div>
            </div>
            <div class="carousel-item">
                {{-- Pastikan path gambar benar dan gambar ada --}}
                <img src="{{ asset('images/pool3.jpg') }}" class="d-block w-100" alt="Program Renang Semua Tingkatan"
                    onerror="this.onerror=null; this.src='https://placehold.co/1200x600/ff9900/FFF?text=Program+Lengkap';">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Program untuk Semua Tingkatan</h1>
                    <p>Dari pemula hingga atlet profesional</p>
                    <a href="/program" class="btn btn-primary btn-lg">Lihat Program</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <section id="program" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Program Kursus Renang</h2>
                <p class="lead text-muted">Pilih program yang sesuai dengan kebutuhan Anda</p>
            </div>
            <div class="row g-4 justify-content-center">
                {{-- Loop melalui data program --}}
                @forelse($programs as $program)
                    <div class="col-md-6 col-lg-4 d-flex align-items-stretch"> {{-- Gunakan d-flex untuk tinggi card yang sama
                        --}}
                        <div class="card h-100 shadow-sm">
                            {{-- Pastikan path gambar benar dan gambar ada --}}
                            <img src="{{ asset('images/' . $program['image']) }}" class="card-img-top program-card-img"
                                alt="{{ $program['name'] }}"
                                onerror="this.onerror=null; this.src='https://placehold.co/400x250/eee/333?text={{ urlencode($program['name']) }}';">
                            <div class="card-body d-flex flex-column"> {{-- Flex column agar footer ke bawah --}}
                                <h5 class="card-title fw-bold">{{ $program['name'] }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ Str::limit($program['description'], 100) }}</p>
                                {{-- Batasi deskripsi & grow --}}
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3"> {{-- mt-auto push
                                    ke bawah --}}
                                    <span class="badge bg-primary">{{ $program['level'] }}</span>
                                    <span class="text-primary fw-bold">Rp
                                        {{ number_format($program['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-3"> {{-- Hapus background & border --}}
                                <a href="/program/{{ $program['id'] }}" class="btn btn-outline-primary w-100">Detail Program</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada program yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="/program" class="btn btn-primary">Lihat Semua Program</a>
            </div>
        </div>
    </section>

    <section id="keunggulan" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
                <p class="lead text-muted">Keunggulan Butterfly Swimming Course di TKI Sport Center</p>
            </div>
            <div class="row g-4 text-center">
                {{-- Item Keunggulan 1 --}}
                <div class="col-md-3">
                    <div
                        class="mb-3 mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center icon-circle">
                        <i class="fas fa-medal fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold">Pelatih Bersertifikat</h5>
                    <p class="text-muted">Dibimbing oleh pelatih profesional dengan sertifikasi nasional.</p>
                </div>
                {{-- Item Keunggulan 2 --}}
                <div class="col-md-3">
                    <div
                        class="mb-3 mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center icon-circle">
                        <i class="fas fa-water fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold">Fasilitas Modern</h5>
                    <p class="text-muted">Kolam renang standar dengan air yang terjaga kebersihannya.</p>
                </div>
                {{-- Item Keunggulan 3 --}}
                <div class="col-md-3">
                    <div
                        class="mb-3 mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center icon-circle">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold">Kelas Terbatas</h5>
                    <p class="text-muted">Jumlah siswa per kelas dibatasi untuk perhatian maksimal.</p>
                </div>
                {{-- Item Keunggulan 4 --}}
                <div class="col-md-3">
                    <div
                        class="mb-3 mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center icon-circle">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-semibold">Keamanan Prioritas</h5>
                    <p class="text-muted">Pengawasan ketat dan protokol keamanan yang jelas.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Testimoni</h2>
                <p class="lead text-muted">Apa kata mereka tentang kursus renang kami</p>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    {{-- Pastikan ada data testimoni --}}
                    @if(!empty($testimonials) && count($testimonials) > 0)
                        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($testimonials as $index => $testimonial)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body p-4 p-md-5 text-center">
                                                <div class="mb-3">
                                                    <i class="fas fa-quote-left fa-3x text-primary opacity-25"></i>
                                                </div>
                                                <blockquote class="blockquote mb-4">
                                                    <p class="lead fst-italic">"{{ $testimonial['message'] }}"</p>
                                                </blockquote>
                                                <div class="d-flex justify-content-center mb-3">
                                                    {{-- Bintang Rating --}}
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star {{ $i <= $testimonial['rating'] ? 'text-warning' : 'text-muted' }} me-1"></i>
                                                    @endfor
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    {{-- Pastikan path gambar benar dan gambar ada --}}
                                                    <img src="{{ asset('images/' . $testimonial['image']) }}"
                                                        class="testimonial-img me-3" alt="{{ $testimonial['name'] }}"
                                                        onerror="this.onerror=null; this.src='https://placehold.co/80x80/eee/333?text={{ substr($testimonial['name'], 0, 1) }}';">
                                                    <div>
                                                        <h5 class="mb-0 fw-semibold">{{ $testimonial['name'] }}</h5>
                                                        <p class="mb-0 text-muted small">{{ $testimonial['role'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Hanya tampilkan tombol jika item lebih dari 1 --}}
                            @if(count($testimonials) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon testimonial-control-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon testimonial-control-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada testimoni.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Tambahkan mb-5 (margin-bottom) di sini untuk memberi jarak ke footer --}}
    <section id="cta" class="py-5 bg-primary text-white mb-5">
        <div class="container">
            <div class="row align-items-center text-center text-lg-start">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="fw-bold">Siap untuk Mulai Berenang?</h2>
                    <p class="lead mb-0">Daftar sekarang dan dapatkan penawaran spesial untuk pendaftaran pertama Anda!</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/daftar" class="btn btn-dark btn-lg fw-bold shadow-sm">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    {{-- Jika Anda punya section 'styles' di layout, Anda bisa push CSS spesifik halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/home-style.css') }}"> {{-- Contoh jika CSS dipisah --}}
@endpush

@push('scripts')
    {{-- Jika Anda punya section 'scripts' di layout --}}
    <script>
        // Script spesifik untuk halaman home jika diperlukan
        // Contoh: Mengatur interval carousel
        // const heroCarouselElement = document.querySelector('#heroCarousel')
        // const heroCarousel = new bootstrap.Carousel(heroCarouselElement, {
        //   interval: 5000, // Ganti interval ke 5 detik
        //   touch: true
        // })
    </script>
@endpush