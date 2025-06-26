<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalGuru      = \App\Models\User::where('role', 'guru')->count();
        $totalMurid     = \App\Models\User::where('role', 'murid')->count();
        $totalKursus    = \App\Models\SwimmingCourse::count();
        $totalPemasukan = \App\Models\Registration::where('status', 'approved')->sum('biaya'); // contoh field biaya

        $muridPerBulan = \App\Models\User::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('role', 'murid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalGuru', 'totalMurid', 'totalKursus', 'totalPemasukan', 'muridPerBulan'
        ));
    }
}
