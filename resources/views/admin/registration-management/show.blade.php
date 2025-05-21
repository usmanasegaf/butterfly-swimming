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

                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4>Informasi Pendaftaran #{{ $registration->id }}</h4>
                        <div class="badge-group mt-2">
                            @if($registration->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($registration->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($registration->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($registration->status == 'completed')
                                <span class="badge bg-primary">Completed</span>
                            @endif

                            @if($registration->payment_status == 'unpaid')
                                <span class="badge bg-danger">Unpaid</span>
                            @elseif($registration->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($registration->payment_status == 'refunded')
                                <span class="badge bg-info">Refunded</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Detail Peserta</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama</th>
                                <td>{{ $registration->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $registration->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Terdaftar pada</th>
                                <td>{{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Detail Kursus</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Kursus</th>
                                <td>{{ $registration->swimmingCourse->name }}</td>
                            </tr>
                            <tr>
                                <th>Level</th>
                                <td>{{ $registration->swimmingCourse->level }}</td>
                            </tr>
                            <tr>
                                <th>Instruktur</th>
                                <td>{{ $registration->swimmingCourse->instructor ?? 'Belum ditentukan' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Jadwal & Pembayaran</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal Mulai</th>
                                <td>{{ \Carbon\Carbon::parse($registration->start_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td>{{ \Carbon\Carbon::parse($registration->end_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Durasi Kursus</th>
                                <td>{{ $registration->swimmingCourse->duration }} minggu</td>
                            </tr>
                            <tr>
                                <th>Sesi per Minggu</th>
                                <td>{{ $registration->swimmingCourse->sessions_per_week }} sesi</td>
                            </tr>
                            <tr>
                                <th>Harga Kursus</th>
                                <td>Rp {{ number_format($registration->swimmingCourse->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    @if($registration->payment_status == 'unpaid')
                                        <span class="badge bg-danger">Unpaid</span>
                                    @elseif($registration->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($registration->payment_status == 'refunded')
                                        <span class="badge bg-info">Refunded</span>
                                    @endif
                                </td>
                            </tr>
                            @if($registration->payment_date)
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <td>{{ \Carbon\Carbon::parse($registration->payment_date)->format('d M Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($registration->notes || $registration->admin_notes)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Catatan</h5>
                        <table class="table table-bordered">
                            @if($registration->notes)
                            <tr>
                                <th>Catatan Peserta</th>
                                <td>{{ $registration->notes }}</td>
                            </tr>
                            @endif
                            @if($registration->admin_notes)
                            <tr>
                                <th>Catatan Admin</th>
                                <td>{{ $registration->admin_notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @endif

                <!-- Form untuk mengubah status pendaftaran -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Update Status Pendaftaran</h5>
                        <form method="POST" action="{{ route('registration-management.update', $registration->id) }}" class="mt-2">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="Pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        @can('approve registration')
                                        <option value="Approved" {{ $registration->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        @endcan
                                        @can('reject registration')
                                        <option value="Rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        @endcan
                                        <option value="Completed" {{ $registration->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan Admin</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $registration->admin_notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                @can('delete registration')
                                <form action="{{ route('registration-management.destroy', $registration->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus Pendaftaran</button>
                                </form>
                                @endcan
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>