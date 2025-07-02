<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-swimming-pool"></i>
        </div>
        <div class="sidebar-brand-text mx-3">BSC Dashboard</div>
    </a>

    <hr class="sidebar-divider my-0">

    @if (auth()->user()->role == 'admin')
        <div class="sidebar-heading">
            Dashboard & Statistik
        </div>
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard Admin</span>
            </a>
        </li>
        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Manajemen Data
        </div>
        <li class="nav-item {{ request()->routeIs('swimming-course-management.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('swimming-course-management.index') }}">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Manajemen Kursus Renang</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('registration-management.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('registration-management.index') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Manajemen Pendaftaran</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.guru.list') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.guru.list') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Daftar Guru & Murid per Guru</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Verifikasi
        </div>
        <li class="nav-item {{ request()->routeIs('admin.guru.pending') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.guru.pending') }}">
                <i class="fas fa-fw fa-user-check"></i>
                <span>Verifikasi Guru</span>
            </a>
        </li>
        {{-- untuk guru --}}
    @elseif (auth()->user()->role == 'guru')
        <div class="sidebar-heading">
            Guru
        </div>
        <li class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.dashboard') }}">
                <i class="fas fa-fw fa-chalkboard-teacher"></i>
                <span>Dashboard Guru</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.murid.pending') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.murid.pending') }}">
                <i class="fas fa-fw fa-user-check"></i>
                <span>Verifikasi Murid</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.murid.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.murid.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Daftar Murid Bimbingan</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.jadwal.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.jadwal.index') }}">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Jadwal Kursus</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.attendance.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.attendance.index') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Absensi</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('guru.kursus.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guru.kursus.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kursus yang Dipegang</span>
            </a>
        </li>
        {{-- untuk murid --}}
    @elseif (auth()->user()->role == 'murid')
        <div class="sidebar-heading">
            Murid
        </div>
        <li class="nav-item {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('murid.dashboard') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Dashboard Murid</span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('murid.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('murid.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kursus Saya</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
