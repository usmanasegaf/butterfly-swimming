<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance; // Import model Attendance
use App\Models\Schedule;   // Import model Schedule
use App\Models\SwimmingCourse; // Import model SwimmingCourse
use Carbon\Carbon; // Pastikan Carbon diimport

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        // Jumlah murid bimbingan (relasi sesuai struktur kamu)
        $murid_count = $guru->murids()->count() ?? 0;

        // Jumlah jadwal kursus yang diampu
        $jadwal_count = $guru->jadwalGuru()->count() ?? 0;

        // --- PERBAIKAN UNTUK JUMLAH ABSENSI HARI INI ---
        // 1. Dapatkan semua ID jadwal yang diampu oleh guru ini
        $guruScheduleIds = Schedule::where('guru_id', $guru->id)->pluck('id');

        // 2. Hitung absensi hari ini yang terkait dengan jadwal-jadwal tersebut
        $absensi_hari_ini = Attendance::whereIn('schedule_id', $guruScheduleIds)
            ->whereDate('attendance_date', now()->toDateString()) // Menggunakan 'attendance_date'
            ->count();
        // --- AKHIR PERBAIKAN ---

        // Jadwal terdekat (Logic untuk jadwal berulang mingguan)
        // Dapatkan hari ini dalam format ISO (1=Senin, 7=Minggu)
        $currentDayOfWeek = now()->dayOfWeekIso;
        // Dapatkan waktu sekarang dalam format HH:MM:SS
        $currentTime = now()->format('H:i:s');

        $jadwal_terdekat = $guru->jadwalGuru()
        // Cari jadwal yang berlangsung hari ini, yang belum lewat waktunya
            ->where(function ($query) use ($currentDayOfWeek, $currentTime) {
                $query->where('day_of_week', $currentDayOfWeek)
                    ->where('start_time_of_day', '>=', $currentTime);
            })
        // Jika tidak ada jadwal yang akan datang hari ini, cari jadwal di hari-hari berikutnya dalam minggu ini
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '>', $currentDayOfWeek);
            })
        // Jika tidak ada jadwal dari hari ini sampai akhir minggu, cari jadwal dari awal minggu depan (putaran minggu)
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '<', $currentDayOfWeek);
            })
            ->orderBy('day_of_week', 'asc')      // Urutkan berdasarkan hari
            ->orderBy('start_time_of_day', 'asc') // Lalu urutkan berdasarkan jam mulai
            ->with(['swimmingCourse', 'location'])
            ->first();

        return view('guru.dashboard', compact(
            'murid_count',
            'jadwal_count',
            'absensi_hari_ini',
            'jadwal_terdekat',
        ));
    }
}