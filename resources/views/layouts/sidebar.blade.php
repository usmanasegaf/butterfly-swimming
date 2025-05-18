<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-swimming-pool"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Butterfly Swimming</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen
    </div>

    @if (auth()->user()->role == 'admin')
    <!-- Nav Item - Kursus Renang -->
    <li class="nav-item {{ request()->is('swimming-courses*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('swimming-courses.index') }}">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Kursus Renang</span>
        </a>
    </li>

    <!-- Nav Item - Pendaftaran -->
    <li class="nav-item {{ request()->is('registrations*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('registrations.index') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Pendaftaran</span>
        </a>
    </li>
    @else
    <!-- Nav Item - Kursus Saya -->
    <li class="nav-item {{ request()->is('my-registrations*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('my-registrations') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Kursus Saya</span>
        </a>
    </li>

    <!-- Nav Item - Daftar Kursus -->
    <li class="nav-item {{ request()->is('register-course*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('register-course') }}">
            <i class="fas fa-fw fa-plus-circle"></i>
            <span>Daftar Kursus</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Akun
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
