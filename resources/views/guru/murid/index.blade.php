@extends('layouts.app')

@section('title', 'Murid Bimbingan')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Murid</h6>
            <a href="{{ route('guru.murid.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Murid Bimbingan
            </a>
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

            @if ($murids->isEmpty())
                <div class="alert alert-info">
                    Anda belum memiliki murid bimbingan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kursus Aktif</th>
                                <th>Sisa Waktu Les</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($murids as $murid)
                                <tr>
                                    <td>{{ $murid->name }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>
                                        @if ($murid->swimmingCourse)
                                            {{ $murid->swimmingCourse->name }} ({{ $murid->swimmingCourse->level }})
                                        @else
                                            Belum ada kursus
                                        @endif
                                    </td>
                                    <td>
                                        @if ($murid->swimmingCourse && $murid->course_assigned_at)
                                            {{ $murid->remaining_lesson_days }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Aksi Murid">
                                            {{-- Tombol Tugaskan/Ubah Kursus --}}
                                            <button type="button" class="btn btn-info btn-sm assign-course-btn"
                                                    data-toggle="modal" data-target="#assignCourseModal"
                                                    data-murid-id="{{ $murid->id }}"
                                                    data-murid-name="{{ $murid->name }}"
                                                    data-current-course-id="{{ $murid->swimming_course_id ?? '' }}">
                                                Tugaskan/Ubah Kursus
                                            </button>

                                            {{-- Tombol Perpanjang Kursus (hanya tampil jika ada kursus aktif) --}}
                                            @if ($murid->swimmingCourse && $murid->course_assigned_at)
                                                <button type="button" class="btn btn-success btn-sm extend-course-btn"
                                                        data-toggle="modal" data-target="#extendCourseModal"
                                                        data-murid-id="{{ $murid->id }}"
                                                        data-murid-name="{{ $murid->name }}"
                                                        data-current-end-date="{{ $murid->registrations->first()->end_date ?? '' }}">
                                                    Perpanjang Kursus
                                                </button>
                                            @endif

                                            <form action="{{ route('guru.murid.destroy', $murid->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepaskan bimbingan murid ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Lepaskan</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $murids->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal untuk Menugaskan/Mengubah Kursus --}}
<div class="modal fade" id="assignCourseModal" tabindex="-1" role="dialog" aria-labelledby="assignCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignCourseModalLabel">Tugaskan/Ubah Kursus untuk Murid</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignCourseForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Menugaskan/Mengubah kursus untuk: <strong id="muridNameAssignModal"></strong></p>
                    <input type="hidden" name="murid_id" id="muridIdAssignInput">
                    <div class="form-group">
                        <label for="swimming_course_id_assign">Pilih Kursus Renang:</label>
                        <select name="swimming_course_id" id="swimming_course_id_assign" class="form-control" required>
                            <option value="">-- Pilih Kursus --</option>
                            @foreach ($availableCourses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }} ({{ $course->level }}) - Rp{{ number_format($course->price, 0, ',', '.') }} (Durasi: {{ $course->duration }} minggu)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tugaskan/Ubah Kursus</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal untuk Memperpanjang Kursus --}}
<div class="modal fade" id="extendCourseModal" tabindex="-1" role="dialog" aria-labelledby="extendCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="extendCourseModalLabel">Perpanjang Kursus Murid</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="extendCourseForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Perpanjang kursus untuk: <strong id="muridNameExtendModal"></strong></p>
                    <p>Tanggal Selesai Saat Ini: <strong id="currentEndDateExtendModal"></strong></p>
                    <input type="hidden" name="murid_id" id="muridIdExtendInput">
                    <div class="form-group">
                        <label for="additional_weeks">Jumlah Minggu Tambahan:</label>
                        <input type="number" name="additional_weeks" id="additional_weeks" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Perpanjang Kursus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // --- Logika untuk Modal Tugaskan/Ubah Kursus ---
        $('.assign-course-btn').on('click', function() {
            const muridId = $(this).data('murid-id');
            const muridName = $(this).data('murid-name');
            const currentCourseId = $(this).data('current-course-id'); // ID kursus saat ini

            // Isi data ke dalam modal
            $('#muridNameAssignModal').text(muridName);
            $('#muridIdAssignInput').val(muridId);

            // Pre-select kursus saat ini jika ada
            $('#swimming_course_id_assign').val(currentCourseId);

            // Set action URL untuk form di dalam modal
            const formAction = "{{ url('guru/murid') }}/" + muridId + "/assign-course";
            $('#assignCourseForm').attr('action', formAction);
        });

        $('#assignCourseForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah submit form default

            const form = $(this);
            const url = form.attr('action');
            const formData = form.serialize(); // Ambil data form

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    // Tutup modal
                    $('#assignCourseModal').modal('hide');
                    // Tampilkan pesan sukses
                    if (typeof notifications !== 'undefined') {
                        notifications.success(response.success);
                    } else {
                        alert(response.success);
                    }
                    // Refresh halaman untuk memperbarui tabel
                    location.reload();
                },
                error: function(xhr) {
                    // Tutup modal
                    $('#assignCourseModal').modal('hide');
                    // Tampilkan pesan error
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (typeof notifications !== 'undefined') {
                        notifications.error(errorMessage);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                notifications.error(value[0]);
                            });
                        }
                    } else {
                        alert(errorMessage);
                    }
                }
            });
        });

        // --- Logika untuk Modal Perpanjang Kursus ---
        $('.extend-course-btn').on('click', function() {
            const muridId = $(this).data('murid-id');
            const muridName = $(this).data('murid-name');
            const currentEndDate = $(this).data('current-end-date'); // Tanggal selesai saat ini

            // Isi data ke dalam modal
            $('#muridNameExtendModal').text(muridName);
            $('#muridIdExtendInput').val(muridId);
            $('#currentEndDateExtendModal').text(currentEndDate ? new Date(currentEndDate).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) : 'N/A');

            // Set action URL untuk form di dalam modal
            const formAction = "{{ url('guru/murid') }}/" + muridId + "/extend-course";
            $('#extendCourseForm').attr('action', formAction);

            // Reset input minggu tambahan
            $('#additional_weeks').val(1);
        });

        $('#extendCourseForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah submit form default

            const form = $(this);
            const url = form.attr('action');
            const formData = form.serialize(); // Ambil data form

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    // Tutup modal
                    $('#extendCourseModal').modal('hide');
                    // Tampilkan pesan sukses
                    if (typeof notifications !== 'undefined') {
                        notifications.success(response.success);
                    } else {
                        alert(response.success);
                    }
                    // Refresh halaman untuk memperbarui tabel
                    location.reload();
                },
                error: function(xhr) {
                    // Tutup modal
                    $('#extendCourseModal').modal('hide');
                    // Tampilkan pesan error
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    if (typeof notifications !== 'undefined') {
                        notifications.error(errorMessage);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                notifications.error(value[0]);
                            });
                        }
                    } else {
                        alert(errorMessage);
                    }
                }
            });
        });
    });
</script>
@endpush
