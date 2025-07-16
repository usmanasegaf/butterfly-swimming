<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use App\Models\SwimmingCourse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total Murid
        $totalMurid = User::where('role', 'murid')->count();

        // Total Guru
        $totalGuru = User::where('role', 'guru')->count();

        // Total Kursus
        $totalKursus = SwimmingCourse::count();

        // Pendaftaran Murid Baru Per Bulan (Area Chart)
        $muridPerBulanRaw = User::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('role', 'murid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Inisialisasi array untuk 12 bulan dengan nilai 0
        $muridPerBulan = array_fill(1, 12, 0);
        foreach ($muridPerBulanRaw as $bulan => $total) {
            $muridPerBulan[$bulan] = $total;
        }
        // Ubah keys menjadi nama bulan untuk label grafik
        $bulanLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];
        // Pastikan nama variabel ini sesuai dengan yang digunakan di Blade (JavaScript)
        $muridPerBulanData = array_values($muridPerBulan);
        $muridPerBulanLabels = array_values($bulanLabels);

        // Pemasukan Total
        $totalPemasukan = Registration::where('status', 'approved')->sum('biaya');

        // Pemasukan Per Guru
        $pemasukanPerGuru = Registration::select(
                'registrations.guru_id',
                'users.name as guru_name',
                DB::raw('SUM(registrations.biaya) as total_biaya')
            )
            ->join('users', 'registrations.guru_id', '=', 'users.id')
            ->where('registrations.status', 'approved')
            ->groupBy('registrations.guru_id', 'users.name')
            ->orderBy('total_biaya', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalMurid',
            'totalGuru',
            'totalKursus',
            'totalPemasukan',
            'pemasukanPerGuru',
            'muridPerBulanData',    // <<< Pastikan ini dikirim
            'muridPerBulanLabels'   // <<< Pastikan ini dikirim
        ));
    }
}
