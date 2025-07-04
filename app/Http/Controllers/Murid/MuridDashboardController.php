<?php
namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;

class MuridDashboardController extends Controller
{
    public function index()
    {
        $murid      = auth()->user();
        $expired_at = $murid->expired_at;
        $sisa_hari  = $expired_at ? now()->diffInDays($expired_at, false) : null;

// Ambil jadwal terdekat (Logic untuk jadwal berulang mingguan)
        $currentDayOfWeek = now()->dayOfWeekIso;
        $currentTime = now()->format('H:i:s');

        $jadwal_selanjutnya = $murid->schedules()
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
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time_of_day', 'asc')
            ->first();

        return view('murid.dashboard', compact('expired_at', 'sisa_hari', 'jadwal_selanjutnya'));
    }
}
