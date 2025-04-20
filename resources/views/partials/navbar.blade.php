<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <i class="fas fa-swimming-pool me-2"></i>
            <span>Butterfly Swimming</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('program*') ? 'active' : '' }}" href="/program">Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('jadwal*') ? 'active' : '' }}" href="/jadwal">Jadwal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pelatih*') ? 'active' : '' }}" href="/pelatih">Pelatih</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tentang*') ? 'active' : '' }}" href="/tentang">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}" href="/kontak">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-success text-white ms-2 px-3" href="/daftar">Daftar Sekarang</a>
                </li>
            </ul>
        </div>
    </div>
</nav>