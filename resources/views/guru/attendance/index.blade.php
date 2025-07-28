@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}
@section('title', 'Pilih Jadwal Untuk Absensi')
@section('content')
    <div class="container-fluid">

        @if ($schedules->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki jadwal kursus yang aktif untuk diambil absensinya.
            </div>
        @else
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jadwal Kursus Anda</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kursus</th>
                                    <th>Lokasi</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->swimmingCourse->name }}</td>
                                        <td>{{ $schedule->location->name }}</td>
                                        <td>
                                            @php
                                                $days = [
                                                    1 => 'Senin',
                                                    2 => 'Selasa',
                                                    3 => 'Rabu',
                                                    4 => 'Kamis',
                                                    5 => 'Jumat',
                                                    6 => 'Sabtu',
                                                    7 => 'Minggu',
                                                ];
                                            @endphp
                                            {{ $days[$schedule->day_of_week] ?? 'Tidak Ditemukan' }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i') }}</td>
                                        <td>
                                            <a href="{{ route('guru.schedules.show_attendance_form', $schedule->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Ambil Absensi
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Kartu baru untuk Generate Laporan Absensi --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Laporan Absensi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.attendances.report.generate') }}" method="GET">
                    <div class="form-group">
                        <label>Tipe Laporan:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="report_type" id="reportTypeWeekly"
                                value="weekly" checked>
                            <label class="form-check-label" for="reportTypeWeekly">Mingguan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="report_type" id="reportTypeMonthly"
                                value="monthly">
                            <label class="form-check-label" for="reportTypeMonthly">Bulanan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="report_type" id="reportTypeYearly"
                                value="yearly">
                            <label class="form-check-label" for="reportTypeYearly">Tahunan</label>
                        </div>
                    </div>

                    <div id="weeklyPeriod" class="form-group">
                        <label for="weekly_date">Pilih Tanggal (untuk menentukan minggu):</label>
                        <input type="date" class="form-control" id="weekly_date" name="weekly_date"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div id="monthlyPeriod" class="form-group" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="monthly_month">Bulan:</label>
                                <select class="form-control" id="monthly_month" name="monthly_month">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="monthly_year">Tahun:</label>
                                <select class="form-control" id="monthly_year" name="monthly_year">
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        {{-- Menampilkan 5 tahun terakhir --}}
                                        <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="yearlyPeriod" class="form-group" style="display: none;">
                        <label for="yearly_year">Tahun:</label>
                        <select class="form-control" id="yearly_year" name="yearly_year">
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                {{-- Menampilkan 5 tahun terakhir --}}
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">
                        <i class="fas fa-file-pdf"></i> Generate Laporan PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">30 Riwayat Absensi Terakhir</h6>
            </div>
            <div class="card-body">
                @if ($attendances->isEmpty())
                    <div class="alert alert-info">
                        Belum ada riwayat absensi yang tercatat.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Waktu Absen</th>
                                    <th>Kursus</th>
                                    <th>Murid</th>
                                    <th>Status</th>
                                    <th>Pertemuan Ke</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->attended_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $attendance->schedule->swimmingCourse->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($attendance->status == 'hadir')
                                                <span class="badge badge-success">Hadir</span>
                                            @else
                                                <span class="badge badge-danger">Alpha</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attendance->status == 'hadir')
                                                <span class="badge badge-info">{{ $attendance->pertemuan_ke }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeRadios = document.querySelectorAll('input[name="report_type"]');
            const weeklyPeriod = document.getElementById('weeklyPeriod');
            const monthlyPeriod = document.getElementById('monthlyPeriod');
            const yearlyPeriod = document.getElementById('yearlyPeriod');

            function togglePeriodInputs() {
                const selectedType = document.querySelector('input[name="report_type"]:checked').value;

                weeklyPeriod.style.display = 'none';
                monthlyPeriod.style.display = 'none';
                yearlyPeriod.style.display = 'none';

                if (selectedType === 'weekly') {
                    weeklyPeriod.style.display = 'block';
                } else if (selectedType === 'monthly') {
                    monthlyPeriod.style.display = 'block';
                } else if (selectedType === 'yearly') {
                    yearlyPeriod.style.display = 'block';
                }
            }

            // Tambahkan event listener untuk setiap radio button
            reportTypeRadios.forEach(radio => {
                radio.addEventListener('change', togglePeriodInputs);
            });

            // Panggil fungsi ini sekali saat halaman dimuat untuk mengatur visibilitas awal
            togglePeriodInputs();
        });
    </script>
@endpush
