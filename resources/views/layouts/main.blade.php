<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Butterfly Swimming Course')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-swimming-pool me-2"></i>
                Butterfly Swimming
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#program">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/jadwal">Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pelatih">Pelatih</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kontak">Kontak</a>
                    </li>
                
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3" href="{{ route('login') }}">Login</a>
                        </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
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
                    <p><i class="fas fa-map-marker-alt me-2"></i> TKI Sport Center, Belakang Griya TKI 2, Bandung</p>
                    <p><i class="fas fa-phone me-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@butterflyswimming.com</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-4" style="color: #F8F8FF;">Jam Operasional</h5>
                    <p>Senin - Minggu: 07.00 - 18.00</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Butterfly Swimming Course. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/script.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
