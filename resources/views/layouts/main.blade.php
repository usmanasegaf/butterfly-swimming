<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Butterfly Swimming Course')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

<body>
    {{-- Navbar utama dengan semua elemen sejajar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        {{-- Menggunakan d-flex dan justify-content-between pada container untuk penyejajaran horizontal --}}
        <div class="container d-flex align-items-center justify-content-between px-1">
            {{-- Grup Kiri: Brand dan Ikon Sosial --}}
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="/">
                    <i class="fas fa-swimming-pool"></i>
                    Butterfly Swimming Course
                </a>
                <div class="align-items-center">
                    {{-- Instagram Link --}}
                    <a href="https://www.instagram.com/butterflyswimmingcourse/" target="_blank" rel="noopener noreferrer"
                        class="text-white me-2 fs-4"> 
                        <i class="fab fa-instagram"></i>
                    </a>
                    {{-- WhatsApp Link --}}
                    <a href="https://wa.me/6281320111868" target="_blank" rel="noopener noreferrer"
                        class="text-white fs-4"> 
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- Grup Kanan: Toggler dan Konten Menu Navigasi --}}
            {{-- Toggler tidak perlu ms-auto lagi karena justify-content-between sudah mengaturnya --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- UL navigasi utama, menggunakan ms-auto untuk mendorong link ke kanan di desktop --}}
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#program">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#testimoni">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#kontak">Kontak</a>
                    </li>

                    {{-- Bagian dinamis berdasarkan status login --}}
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Selamat datang<br>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/profile">Profil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-warning btn-sm ms-2 px-3" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4" style="color: #F8F8FF;">Butterfly Swimming Course</h5>
                    <p>Kursus renang terbaik untuk semua usia dengan pelatih profesional dan bersertifikat.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4" style="color: #F8F8FF;">Kontak Kami</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Margacinta No.98, Cijaura, Kec. Buahbatu, Kota
                        Bandung, Jawa Barat 40287</p>
                    <p><i class="fas fa-phone me-2"></i> 081320111868</p>
                    <p><i class="fas fa-envelope me-2"></i> info@butterflyswimming.com</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-4" style="color: #F8F8FF;">Jam Operasional</h5>
                    <p>Senin - Minggu: 08.00 - 18.00</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Butterfly Swimming Course. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>
