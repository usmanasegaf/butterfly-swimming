@extends('layouts.app')

@section('title', 'Riwayat Absensi Saya')

@section('content')
<div class="container-fluid">

    @if ($attendances->isEmpty())
        <div class="alert alert-info">
            Anda belum memiliki riwayat absensi yang tercatat.
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Absensi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kursus</th>
                                <th>Lokasi</th>
                                <th>Guru</th>
                                <th>Waktu Les</th>
                                <th>Status Absensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->schedule->swimmingCourse->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->schedule->location->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->schedule->guru->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->schedule->start_time_of_day)->format('H:i') }} - {{ \Carbon\Carbon::parse($attendance->schedule->end_time_of_day)->format('H:i') }}</td>
                                    <td>
                                        @if ($attendance->status == 'hadir')
                                            <span class="badge badge-success">Hadir</span>
                                        @else
                                            <span class="badge badge-danger">Alpha</span>
                                        @endif
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
            <h6 class="m-0 font-weight-bold text-primary">Buat Laporan Absensi PDF</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('murid.attendances.report.generate') }}" method="GET">
                <div class="form-group">
                    <label>Tipe Laporan:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="report_type" id="muridReportTypeWeekly" value="weekly" checked>
                        <label class="form-check-label" for="muridReportTypeWeekly">Mingguan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="report_type" id="muridReportTypeMonthly" value="monthly">
                        <label class="form-check-label" for="muridReportTypeMonthly">Bulanan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="report_type" id="muridReportTypeYearly" value="yearly">
                        <label class="form-check-label" for="muridReportTypeYearly">Tahunan</label>
                    </div>
                </div>

                <div id="muridWeeklyPeriod" class="form-group">
                    <label for="murid_weekly_date">Pilih Tanggal (untuk menentukan minggu):</label>
                    <input type="date" class="form-control" id="murid_weekly_date" name="weekly_date" value="{{ date('Y-m-d') }}">
                </div>

                <div id="muridMonthlyPeriod" class="form-group" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="murid_monthly_month">Bulan:</label>
                            <select class="form-control" id="murid_monthly_month" name="monthly_month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="murid_monthly_year">Tahun:</label>
                            <select class="form-control" id="murid_monthly_year" name="monthly_year">
                                @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div id="muridYearlyPeriod" class="form-group" style="display: none;">
                    <label for="murid_yearly_year">Tahun:</label>
                    <select class="form-control" id="murid_yearly_year" name="yearly_year">
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3">
                    <i class="fas fa-file-pdf"></i> Generate Laporan PDF
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reportTypeRadios = document.querySelectorAll('input[name="report_type"]');
        const weeklyPeriod = document.getElementById('muridWeeklyPeriod');
        const monthlyPeriod = document.getElementById('muridMonthlyPeriod');
        const yearlyPeriod = document.getElementById('muridYearlyPeriod');

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

        reportTypeRadios.forEach(radio => {
            radio.addEventListener('change', togglePeriodInputs);
        });

        togglePeriodInputs(); // Set initial visibility
    });
</script>
@endpush
