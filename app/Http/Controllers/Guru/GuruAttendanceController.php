<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User; // Pastikan ini diimpor karena murid juga User
use App\Models\Attendance;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruAttendanceController extends Controller
{
    /**
     * Menampilkan daftar jadwal milik guru untuk dipilih absensinya.
     * Ini adalah halaman pertama yang akan diakses dari sidebar "Absensi".
     */
    public function index()
    {
        $guruId = Auth::id();
        // Ambil semua jadwal yang diampu oleh guru yang sedang login
        $schedules = Schedule::where('guru_id', $guruId)
                             ->with(['swimmingCourse', 'location']) // Eager load relasi untuk efisiensi
                             ->orderBy('day_of_week')
                             ->orderBy('start_time_of_day')
                             ->get();

        // Mengirim data jadwal ke view untuk ditampilkan
        return view('guru.attendance.index', compact('schedules'));
    }

    /**
     * Menampilkan form absensi untuk jadwal tertentu.
     * Ini adalah halaman yang muncul setelah guru memilih jadwal dari daftar.
     */
    public function showAttendanceForm(Schedule $schedule)
    {
        // Ambil user yang sedang login (ini adalah guru).
        $guruUser = Auth::user();

        // **PERBAIKAN DI SINI:**
        // Panggil relasi `murids()` dan tambahkan `.get()` untuk mendapatkan koleksi murid.
        // Relasi `murids()` ada di model User dan mengambil murid bimbingan.
        $murids = $guruUser->murids()->get(); // Mengambil koleksi murid dari relasi `murids()`

        // Dapatkan detail lokasi kursus (tetap diperlukan untuk perhitungan jarak di frontend)
        $location = $schedule->location; // Asumsi Schedule model masih memiliki relasi ke Location

        return view('guru.attendance.show', compact('schedule', 'murids', 'location'));
    }

    /**
     * Menyimpan data absensi yang disubmit dari form.
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

        $allowedRadius = 400; // Radius yang diizinkan dalam meter

        // Periksa apakah guru berada dalam radius yang diizinkan (bagian ini sudah dikomentari sesuai permintaan)
        // if ($distance > $allowedRadius) {
        //     return redirect()->back()->withErrors(['location_error' => 'Anda terlalu jauh dari lokasi kursus (' . round($distance) . ' meter). Absensi hanya bisa diambil dalam radius ' . $allowedRadius . ' meter.']);
        // }

        // Proses absensi setiap murid
        foreach ($request->input('attendance_status') as $muridId => $status) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $muridId, // Pastikan student_id di tabel attendances merujuk ke ID murid yang benar
                    'attendance_date' => Carbon::today(), // Absensi untuk hari ini
                ],
                [
                    'status' => $status,
                    'attended_at' => Carbon::now(), // Waktu absensi dicatat
                    // Simpan koordinat guru saat absensi dicatat
                    'teacher_latitude' => $teacherLat,
                    'teacher_longitude' => $teacherLon,
                    'distance_from_course' => round($distance), // Simpan jarak juga sebagai informasi
                ]
            );
        }

        return redirect()->route('guru.attendance.index')->with('success', 'Absensi berhasil disimpan!');
    }

    /**
     * Fungsi helper untuk menghitung jarak antara dua titik lat/lon menggunakan Haversine formula.
     * Digunakan untuk validasi lokasi guru.
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