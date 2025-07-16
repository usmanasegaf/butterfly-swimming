@extends('layouts.app')

@section('title', 'Murid Bimbingan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Murid Bimbingan Saya</h1>

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
                                            {{-- Tombol Tugaskan Kursus yang memicu modal --}}
                                            <button type="button" class="btn btn-info btn-sm assign-course-btn"
                                                    data-toggle="modal" data-target="#assignCourseModal"
                                                    data-murid-id="{{ $murid->id }}"
                                                    data-murid-name="{{ $murid->name }}">
                                                Tugaskan Kursus
                                            </button>

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

{{-- Modal untuk Menugaskan Kursus --}}
<div class="modal fade" id="assignCourseModal" tabindex="-1" role="dialog" aria-labelledby="assignCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignCourseModalLabel">Tugaskan Kursus ke Murid</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignCourseForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Menugaskan kursus untuk: <strong id="muridNameInModal"></strong></p>
                    <input type="hidden" name="murid_id" id="muridIdInput">
                    <div class="form-group">
                        <label for="swimming_course_id">Pilih Kursus Renang:</label>
                        <select name="swimming_course_id" id="swimming_course_id" class="form-control" required>
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
                    <button type="submit" class="btn btn-primary">Tugaskan Kursus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Ketika tombol "Tugaskan Kursus" diklik
        $('.assign-course-btn').on('click', function() {
            const muridId = $(this).data('murid-id');
            const muridName = $(this).data('murid-name');

            // Isi data ke dalam modal
            $('#muridNameInModal').text(muridName);
            $('#muridIdInput').val(muridId);

            // Set action URL untuk form di dalam modal
            // Menggunakan route helper Laravel dengan ID murid
            const formAction = "{{ url('guru/murid') }}/" + muridId + "/assign-course";
            $('#assignCourseForm').attr('action', formAction);

            // Opsional: Reset pilihan dropdown jika perlu
            $('#swimming_course_id').val('');
        });

        // Handle form submission via AJAX (opsional, tapi lebih baik untuk UX modal)
        // Jika Anda ingin tetap menggunakan redirect Laravel, bagian ini tidak perlu
        // Namun, untuk pengalaman modal yang lebih baik, AJAX disarankan.
        // Jika tidak menggunakan AJAX, form akan submit dan halaman akan refresh.
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
                    // Tampilkan pesan sukses (gunakan NotificationSystem jika ada)
                    if (typeof notifications !== 'undefined') {
                        notifications.success(response.success);
                    } else {
                        alert(response.success); // Fallback jika NotificationSystem tidak ada
                    }
                    // Refresh halaman atau perbarui tabel murid
                    location.reload(); // Cara sederhana untuk refresh data
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
                        // Jika ada error validasi, tampilkan juga
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                notifications.error(value[0]);
                            });
                        }
                    } else {
                        alert(errorMessage); // Fallback
                    }
                }
            });
        });
    });
</script>
@endpush
