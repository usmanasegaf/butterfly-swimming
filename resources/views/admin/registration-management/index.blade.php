@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Manajemen Pendaftaran</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Peserta</th>
                                    <th>Kursus</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->id }}</td>
                                    <td>{{ $registration->user->name }}</td>
                                    <td>{{ $registration->swimmingCourse->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registration->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registration->end_date)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registration->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($registration->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($registration->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($registration->status == 'completed')
                                            <span class="badge bg-primary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($registration->payment_status == 'unpaid')
                                            <span class="badge bg-danger">Unpaid</span>
                                        @elseif($registration->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($registration->payment_status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('registration-management.show', $registration->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            
                                            @can('delete registration')
                                            <form action="{{ route('registration-management.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data pendaftaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection