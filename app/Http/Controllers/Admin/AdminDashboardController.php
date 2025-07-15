<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration; // Tetap diperlukan untuk total pemasukan
use App\Models\SwimmingCourse; // Tetap diperlukan untuk total kursus
use App\Models\User; // Tetap diperlukan untuk total guru, murid, dan grafik pendaftaran bulanan
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik Ringkasan
        $totalGuru      = User::where('role', 'guru')->count();
        $totalMurid     = User::where('role', 'murid')->count();
        $totalKursus    = SwimmingCourse::count();
        $totalPemasukan = Registration::where('status', 'approved')->sum('biaya');

        // Data untuk Grafik Pendaftaran Murid Baru Per Bulan (Area Chart)
        $muridPerBulanRaw = User::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('role', 'murid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $muridPerBulan = array_fill(1, 12, 0);
        foreach ($muridPerBulanRaw as $bulan => $total) {
            $muridPerBulan[$bulan] = $total;
        }
        $bulanLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];
        $muridPerBulanData = array_values($muridPerBulan);
        $muridPerBulanLabels = array_values($bulanLabels);

        // Data untuk Grafik Distribusi Pendaftaran Kursus (Pie Chart), Pendaftaran Terbaru, dan Kursus Populer
        // Dihapus sesuai permintaan.

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalMurid',
            'totalKursus',
            'totalPemasukan',
            'muridPerBulanData',
            'muridPerBulanLabels'
            // Variabel untuk grafik Pie, pendaftaran terbaru, dan kursus populer dihapus
        ));
    }
}
