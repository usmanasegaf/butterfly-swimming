@extends('layouts.app')

@section('title', 'Manajemen Absensi Global')

@section('content')
    <div class="container-fluid">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Absensi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.attendances.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="guru_id">Guru:</label>
                            <select name="guru_id" id="guru_id" class="form-control">
                                <option value="">Semua Guru</option>
                                @foreach ($gurus as $guru)
                                    <option value="{{ $guru->id }}"
                                        {{ request('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="student_id">Murid:</label>
                            <select name="student_id" id="student_id" class="form-control">
                                <option value="">Semua Murid</option>
                                @foreach ($murids as $murid)
                                    <option value="{{ $murid->id }}"
                                        {{ request('student_id') == $murid->id ? 'selected' : '' }}>{{ $murid->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="course_id">Kursus:</label>
                            <select name="course_id" id="course_id" class="form-control">
                                <option value="">Semua Kursus</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="location_id">Lokasi:</label>
                            <select name="location_id" id="location_id" class="form-control">
                                <option value="">Semua Lokasi</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="end_date">Tanggal Selesai:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Terapkan Filter</button>
                            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">Reset Filter</a>
                        </div>
                    </div>
                </form>

                <hr>

                <h6 class="m-0 font-weight-bold text-primary mb-3">Buat Laporan Absensi PDF</h6>
                <form action="{{ route('admin.attendances.report.generate') }}" method="GET">
                    {{-- Hidden inputs to pass current filters to PDF generation --}}
                    @foreach (request()->query() as $key => $value)
                        {{-- Exclude report type specific inputs from being passed as general filters --}}
                        @if (
                            !in_array($key, [
                                'report_type',
                                'weekly_date',
                                'monthly_month',
                                'monthly_year',
                                'yearly_year',
                                'start_date_custom',
                                'end_date_custom',
                            ]))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

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
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="report_type" id="reportTypeCustom"
                                value="custom_range">
                            <label class="form-check-label" for="reportTypeCustom">Rentang Kustom</label>
                        </div>
                    </div>

                    {{-- Dynamic period inputs based on report_type selection --}}
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
                                        {{-- Display last 5 years --}}
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
                                {{-- Display last 5 years --}}
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div id="customRangePeriod" class="form-group" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="custom_start_date">Tanggal Mulai Kustom:</label>
                                <input type="date" class="form-control" id="custom_start_date"
                                    name="start_date_custom" value="{{ request('start_date_custom') ?? date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="custom_end_date">Tanggal Selesai Kustom:</label>
                                <input type="date" class="form-control" id="custom_end_date" name="end_date_custom"
                                    value="{{ request('end_date_custom') ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">
                        <i class="fas fa-file-pdf"></i> Generate Laporan PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Absensi</h6>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($attendances->isEmpty())
                    <div class="alert alert-info">
                        Tidak ada catatan absensi yang ditemukan dengan filter yang dipilih.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered datatable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Kursus</th>
                                    <th>Lokasi</th>
                                    <th>Guru</th>
                                    <th>Murid</th>
                                    <th>Status</th>
                                    <th>Pertemuan Ke</th>
                                    <th>Jarak (m)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i') }}</td>
                                        <td>{{ $attendance->schedule->swimmingCourse->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->schedule->location->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->schedule->guru->name ?? 'N/A' }}</td>
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
                                                {{ $attendance->student->pertemuan_ke ?? 'N/A' }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>{{ number_format($attendance->distance_from_course, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $attendances->links() }}
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
            const customRangePeriod = document.getElementById('customRangePeriod');

            function togglePeriodInputs() {
                const selectedType = document.querySelector('input[name="report_type"]:checked').value;

                weeklyPeriod.style.display = 'none';
                monthlyPeriod.style.display = 'none';
                yearlyPeriod.style.display = 'none';
                customRangePeriod.style.display = 'none';

                if (selectedType === 'weekly') {
                    weeklyPeriod.style.display = 'block';
                } else if (selectedType === 'monthly') {
                    monthlyPeriod.style.display = 'block';
                } else if (selectedType === 'yearly') {
                    yearlyPeriod.style.display = 'block';
                } else if (selectedType === 'custom_range') {
                    customRangePeriod.style.display = 'block';
                }
            }

            reportTypeRadios.forEach(radio => {
                radio.addEventListener('change', togglePeriodInputs);
            });

            // Set initial state based on default checked radio or existing request params
            togglePeriodInputs();
        });
    </script>
@endpush
