@extends('layouts.app')

@section('title', 'Manajemen Pendaftaran')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftaran Kursus</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Kursus Renang</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $registration)
                    <tr>
                        <td>{{ $registration->id }}</td>
                        <td>{{ $registration->user->name ?? 'N/A' }}</td> {{-- Tampilkan nama user --}}
                        <td>{{ $registration->swimmingCourse->name ?? 'N/A' }}</td> {{-- Tampilkan nama kursus --}}
                        <td>{{ $registration->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @php
                                $badgeClass = '';
                                if ($registration->status === 'Approved') {
                                    $badgeClass = 'badge-success';
                                } elseif ($registration->status === 'Rejected') {
                                    $badgeClass = 'badge-danger';
                                } else { // Pending
                                    $badgeClass = 'badge-warning';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $registration->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('registration-management.show', $registration->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <form action="{{ route('registration-management.destroy', $registration->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pendaftaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('admin_assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/demo/datatables-demo.js') }}"></script>
@endpush

@push('styles')

<link href="{{ asset('admin_assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush