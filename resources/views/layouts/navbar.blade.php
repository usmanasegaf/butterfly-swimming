<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts (Notifications) -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw fa-1-4x"></i>
                <!-- Counter - Alerts -->
                @if (Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                    <span class="badge badge-danger badge-counter">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifikasi
                </h6>
                @if (Auth::check() && Auth::user()->unreadNotifications->isEmpty())
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <span class="font-weight-bold">Tidak ada notifikasi baru.</span>
                        </div>
                    </a>
                @else
                    @foreach (Auth::user()->unreadNotifications as $notification)
                        <a class="dropdown-item d-flex align-items-center" href="{{ $notification->data['action_url'] ?? '#' }}"
                           onclick="event.preventDefault(); document.getElementById('mark-notification-{{ $notification->id }}').submit();">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                            </div>
                        </a>
                        <form id="mark-notification-{{ $notification->id }}" action="{{ route('notifications.markAsRead') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                        </form>
                    @endforeach
                    <a class="dropdown-item text-center small text-gray-500" href="#"
                       onclick="event.preventDefault(); document.getElementById('mark-all-notifications-form').submit();">
                        Tandai Semua Sudah Dibaca
                    </a>
                    <form id="mark-all-notifications-form" action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endif
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                {{-- Logika untuk menampilkan gambar profil atau placeholder ikon --}}
                @php
                    $profileImage = Auth::user()->profile_image;
                @endphp
                @if ($profileImage)
                    <img class="img-profile rounded-circle" src="{{ asset('storage/' . $profileImage) }}">
                @else
                    {{-- Placeholder ikon orang abu-abu --}}
                    <div class="img-profile rounded-circle bg-gray-300 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-gray-600 fa-lg"></i>
                    </div>
                @endif
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Siap untuk Keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
