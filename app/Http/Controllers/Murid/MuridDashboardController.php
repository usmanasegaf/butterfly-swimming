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

        // Ambil jadwal terdekat
        $jadwal_selanjutnya = $murid->schedules()
            ->where('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->first();

        return view('murid.dashboard', compact('expired_at', 'sisa_hari', 'jadwal_selanjutnya'));
    }
}
