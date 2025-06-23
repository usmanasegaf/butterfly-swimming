<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-swimming-pool"></i>
        </div>
        <div class="sidebar-brand-text mx-3">BSC Dashboard</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    @if (auth()->user()->role == 'admin')
        <div class="sidebar-heading">
            Manajemen
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
    @else {{-- Jika role adalah 'user' --}}
        <div class="sidebar-heading">
            Aktivitas
        </div>

        <li class="nav-item {{ request()->routeIs('my-registrations') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('my-registrations') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kursus Saya</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('register-course') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('register-course') }}">
                <i class="fas fa-fw fa-plus-circle"></i>
                <span>Daftar Kursus Baru</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>