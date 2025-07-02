<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        // Jumlah murid bimbingan (relasi sesuai struktur kamu)
        $murid_count = $guru->murids()->count() ?? 0;

        // Jumlah jadwal kursus yang diampu
        $jadwal_count = $guru->jadwalGuru()->count() ?? 0;

        // Jumlah absensi hari ini (misal, absensi yang dibuat oleh guru hari ini)
        $absensi_hari_ini = \App\Models\Attendance::where('guru_id', $guru->id)
            ->whereDate('date', now()->toDateString())
            ->count();

        // Jumlah kursus yang dipegang (misal, swimming_course yang diampu guru)
        $kursus_count = \App\Models\SwimmingCourse::where('guru_id', $guru->id)->count();

        // Jadwal terdekat
        $jadwal_terdekat = $guru->jadwalGuru()
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->with(['swimmingCourse', 'location'])
            ->first();

        return view('guru.dashboard', compact(
            'murid_count',
            'jadwal_count',
            'absensi_hari_ini',
            'kursus_count',
            'jadwal_terdekat'
        ));
    }
}
