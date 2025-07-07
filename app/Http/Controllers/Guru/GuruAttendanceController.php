<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Murid; // Menggunakan model Murid sesuai struktur Anda
use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruAttendanceController extends Controller
{
    /**
     * Menampilkan daftar riwayat absensi murid untuk jadwal yang diampu guru ini.
     */
    public function index()
    {
        $guruId = Auth::id();

        // Dapatkan semua ID jadwal yang diampu oleh guru ini
        $scheduleIds = Schedule::where('guru_id', $guruId)->pluck('id');

        // Ambil semua record absensi yang terkait dengan jadwal-jadwal tersebut
        // Eager load relasi yang dibutuhkan: student (murid), schedule, dan schedule.location
        $attendances = Attendance::whereIn('schedule_id', $scheduleIds)
                                 ->with(['student', 'schedule.swimmingCourse', 'schedule.location'])
                                 ->orderBy('attendance_date', 'desc') // Urutkan berdasarkan tanggal terbaru
                                 ->orderBy('attended_at', 'desc') // Lalu berdasarkan waktu absensi
                                 ->get();

        // Mengembalikan view yang sudah ada dengan data absensi
        return view('guru.attendance.index', compact('attendances'));
    }

    /**
     * Menampilkan form absensi untuk jadwal tertentu.
     */
    public function showAttendanceForm(Schedule $schedule)
    {
        // Pastikan guru hanya bisa mengakses jadwal miliknya sendiri
        if ($schedule->guru_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        // Memuat murid yang terdaftar untuk jadwal ini melalui relasi murids()
        // Pastikan relasi murids() di model Schedule mengembalikan User/Murid yang benar
        $murids = $schedule->murids;

        // Dapatkan detail lokasi yang terkait dengan jadwal
        $location = $schedule->location;

        return view('guru.attendance.show', compact('schedule', 'murids', 'location'));
    }

    /**
     * Menyimpan data absensi yang disubmit.
     */
    public function storeAttendance(Request $request, Schedule $schedule)
    {
        // Pastikan guru hanya bisa mengakses jadwal miliknya sendiri
        if ($schedule->guru_id !== Auth::id()) {
            return redirect()->back()->withErrors(['auth_error' => 'Anda tidak memiliki akses untuk mengambil absensi jadwal ini.']);
        }

        // Validasi data absensi dan koordinat guru
        $request->validate([
            'attendance_status.*' => 'required|in:hadir,alpha', // array status absensi untuk setiap murid
            'teacher_latitude' => 'required|numeric',
            'teacher_longitude' => 'required|numeric',
        ]);

        // Dapatkan koordinat lokasi kursus
        $locationLat = $schedule->location->latitude;
        $locationLon = $schedule->location->longitude;

        // Dapatkan koordinat guru dari request (dari JavaScript frontend)
        $teacherLat = $request->input('teacher_latitude');
        $teacherLon = $request->input('teacher_longitude');

        // Hitung jarak antara lokasi guru dan lokasi kursus
        $distance = $this->calculateDistance($locationLat, $locationLon, $teacherLat, $teacherLon);

        $allowedRadius = 400; // Radius yang diizinkan dalam meter (sesuai permintaan Anda)

        // Periksa apakah guru berada dalam radius yang diizinkan
        if ($distance > $allowedRadius) {
            return redirect()->back()->withErrors(['location_error' => 'Anda terlalu jauh dari lokasi kursus (' . round($distance) . ' meter). Absensi hanya bisa diambil dalam radius ' . $allowedRadius . ' meter.']);
        }

        // Proses absensi setiap murid
        foreach ($request->input('attendance_status') as $muridId => $status) {
            // Pastikan murid ini memang terdaftar di jadwal ini jika perlu validasi lebih lanjut
            // $schedule->murids->contains('id', $muridId);

            Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $muridId, // Menggunakan muridId karena foreignId di Attendance adalah student_id yang mengarah ke tabel users/murids
                    'attendance_date' => Carbon::today(), // Absensi untuk hari ini
                ],
                [
                    'status' => $status,
                    'attended_at' => Carbon::now(), // Waktu absensi dicatat
                ]
            );
        }

        return redirect()->route('guru.attendance.index')->with('success', 'Absensi berhasil disimpan!');
    }

    /**
     * Fungsi helper untuk menghitung jarak antara dua titik lat/lon menggunakan Haversine formula.
     *
     * @param float $lat1 Latitude titik 1
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Jarak dalam meter

        return $distance;
    }
}