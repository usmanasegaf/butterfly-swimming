@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('title', 'Ambil Absensi untuk Jadwal: ' . $schedule->swimmingCourse->name)

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Ambil Absensi Murid</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Jadwal</h6>
                <div class="card-body">
                    <p><strong>Kursus:</strong> {{ $schedule->swimmingCourse->name }}</p>
                    <p><strong>Lokasi Kursus:</strong> {{ $schedule->location->name }} (Lat: <span
                            id="locationLat">{{ $location->latitude }}</span>, Lon: <span
                            id="locationLon">{{ $location->longitude }}</span>)</p>
                    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($schedule->start_time_of_day)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($schedule->end_time_of_day)->format('H:i') }}</p>
                    <p><strong>Hari:</strong>
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
                    </p>
                    <p><strong>Status GPS Guru:</strong> <span id="gpsStatus" class="text-warning">Mencari lokasi...</span>
                    </p>
                    <p><strong>Jarak ke Lokasi Kursus:</strong> <span id="distanceToLocation">N/A</span></p>
                </div>
            </div>


            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Absensi Murid</h6>
                </div>
                <div class="card-body">
                    @if ($schedule->murid)
                        <form id="attendanceForm" action="{{ route('guru.schedules.store_attendance', $schedule->id) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="teacher_latitude" id="teacher_latitude">
                            <input type="hidden" name="teacher_longitude" id="teacher_longitude">
                            <input type="hidden" name="murid_id" value="{{ $schedule->murid->id }}">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Murid</th>
                                            <th>Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $schedule->murid->name }}</td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="attendance_status"
                                                        id="hadir_{{ $schedule->murid->id }}" value="hadir" checked>
                                                    <label class="form-check-label"
                                                        for="hadir_{{ $schedule->murid->id }}">Hadir</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="attendance_status"
                                                        id="alpha_{{ $schedule->murid->id }}" value="alpha">
                                                    <label class="form-check-label"
                                                        for="alpha_{{ $schedule->murid->id }}">Alpha</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitAttendanceBtn" disabled>Simpan
                                Absensi</button>
                        </form>
                    @else
                        <div class="alert alert-warning">Jadwal ini belum memiliki murid yang ditugaskan.</div>
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const submitBtn = document.getElementById('submitAttendanceBtn');
                const gpsStatusSpan = document.getElementById('gpsStatus');
                const distanceSpan = document.getElementById('distanceToLocation');
                const teacherLatInput = document.getElementById('teacherLatInput');
                const teacherLonInput = document.getElementById('teacherLonInput');

                const locationLat = parseFloat(document.getElementById('locationLat').innerText);
                const locationLon = parseFloat(document.getElementById('locationLon').innerText);
                const allowedRadius = 400; // Radius yang diizinkan dalam meter

                // Fungsi untuk menghitung jarak Haversine
                function getDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371e3; // Radius bumi dalam meter
                    const φ1 = lat1 * Math.PI / 180; // φ, λ dalam radian
                    const φ2 = lat2 * Math.PI / 180;
                    const Δφ = (lat2 - lat1) * Math.PI / 180;
                    const Δλ = (lon2 - lon1) * Math.PI / 180;

                    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                    const d = R * c; // Jarak dalam meter
                    return d;
                }

                // Callback sukses saat mendapatkan lokasi
                function geoSuccess(position) {
                    const teacherLat = position.coords.latitude;
                    const teacherLon = position.coords.longitude;

                    // Masukkan koordinat guru ke input hidden form
                    teacherLatInput.value = teacherLat;
                    teacherLonInput.value = teacherLon;

                    const distance = getDistance(locationLat, locationLon, teacherLat, teacherLon);
                    distanceSpan.innerText = `${distance.toFixed(2)} meter`;

                    // Update status GPS berdasarkan jarak (ini tetap informatif)
                    if (distance <= allowedRadius) {
                        gpsStatusSpan.innerText = "Lokasi ditemukan (dalam radius). Siap absen.";
                        gpsStatusSpan.classList.remove('text-warning', 'text-danger', 'text-info');
                        gpsStatusSpan.classList.add('text-success');
                        // submitBtn.disabled = false; // Baris ini dikomentari agar tombol tidak diatur di sini
                    } else {
                        gpsStatusSpan.innerText =
                            `Lokasi ditemukan (${distance.toFixed(2)} meter dari lokasi kursus - di luar radius).`;
                        gpsStatusSpan.classList.remove('text-warning', 'text-success', 'text-danger');
                        gpsStatusSpan.classList.add('text-info');
                        // submitBtn.disabled = true; // Baris ini dikomentari agar tombol tidak diatur di sini
                    }

                    // **PERUBAHAN BARU:** Selalu aktifkan tombol submit setelah lokasi berhasil ditemukan
                    submitBtn.disabled = false;
                }

                // Callback error saat gagal mendapatkan lokasi
                function geoError(error) {
                    let errorMessage = "Tidak dapat mengambil lokasi Anda. ";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += "Pastikan Anda memberikan izin lokasi.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += "Informasi lokasi tidak tersedia.";
                            break;
                        case error.TIMEOUT:
                            errorMessage += "Waktu habis saat mencoba mengambil lokasi.";
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage += "Terjadi kesalahan tidak dikenal.";
                            break;
                    }
                    gpsStatusSpan.innerText = errorMessage;
                    gpsStatusSpan.classList.remove('text-warning', 'text-success', 'text-info');
                    gpsStatusSpan.classList.add('text-danger');
                    submitBtn.disabled = true; // Nonaktifkan tombol submit jika ada error
                }

                // Cek apakah browser mendukung Geolocation API
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(geoSuccess, geoError, {
                        enableHighAccuracy: true, // Mencoba untuk mendapatkan lokasi seakurat mungkin
                        timeout: 10000, // Waktu maksimal untuk mendapatkan lokasi (10 detik)
                        maximumAge: 0 // Tidak menggunakan cache lokasi lama
                    });
                } else {
                    gpsStatusSpan.innerText = "Geolocation tidak didukung oleh browser ini.";
                    gpsStatusSpan.classList.remove('text-warning', 'text-success', 'text-info');
                    gpsStatusSpan.classList.add('text-danger');
                    submitBtn.disabled = true;
                }
            });
        </script>
    @endpush
