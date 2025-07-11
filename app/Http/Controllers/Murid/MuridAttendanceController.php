<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User; // Untuk model murid (yang juga user)
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str; // Diperlukan untuk Str::slug()

class MuridAttendanceController extends Controller
{
    /**
     * Menampilkan riwayat absensi untuk murid yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $muridId = Auth::id();

        // Mengambil semua absensi untuk murid yang sedang login
        // Menggunakan with() untuk memuat relasi yang dibutuhkan
        $attendances = Attendance::where('student_id', $muridId)
                                ->with(['schedule.swimmingCourse', 'schedule.location', 'schedule.guru'])
                                ->orderBy('attendance_date', 'desc')
                                ->orderBy('attended_at', 'desc')
                                ->get();

        return view('murid.attendance.index', compact('attendances'));
    }

    /**
     * Menghasilkan laporan absensi murid dalam format PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        $muridId = Auth::id();
        $muridName = Auth::user()->name;
        $reportType = $request->input('report_type');
        $startDate = null;
        $endDate = null;
        $reportTitle = "Laporan Absensi Murid: " . $muridName;
        $periodText = "";

        // 1. Validasi Input dan Tentukan Periode Waktu
        switch ($reportType) {
            case 'weekly':
                $request->validate([
                    'weekly_date' => 'required|date',
                ]);
                $selectedDate = Carbon::parse($request->input('weekly_date'));
                $startDate = $selectedDate->startOfWeek(Carbon::MONDAY)->toDateString();
                $endDate = $selectedDate->endOfWeek(Carbon::SUNDAY)->toDateString();
                $periodText = "Minggu: " . Carbon::parse($startDate)->format('d M Y') . " - " . Carbon::parse($endDate)->format('d M Y');
                break;

            case 'monthly':
                $request->validate([
                    'monthly_month' => 'required|integer|min:1|max:12',
                    'monthly_year' => 'required|integer|min:2000|max:' . date('Y'),
                ]);
                $year = $request->input('monthly_year');
                $month = $request->input('monthly_month');
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
                $periodText = "Bulan: " . Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
                break;

            case 'yearly':
                $request->validate([
                    'yearly_year' => 'required|integer|min:2000|max:' . date('Y'),
                ]);
                $year = $request->input('yearly_year');
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
                $periodText = "Tahun: " . $year;
                break;

            default:
                return redirect()->back()->withErrors(['report_type' => 'Tipe laporan tidak valid.']);
        }

        // 2. Query Data Absensi untuk Murid yang Sedang Login
        $attendances = Attendance::where('student_id', $muridId) // Filter berdasarkan student_id (murid yang login)
                                ->whereBetween('attendance_date', [$startDate, $endDate])
                                ->with(['schedule.swimmingCourse', 'schedule.location', 'schedule.guru']) // Load relasi yang dibutuhkan
                                ->orderBy('attendance_date')
                                ->orderBy('schedule_id')
                                ->get();

        // 3. Persiapkan Data untuk PDF
        $summary = [
            'total_hadir' => $attendances->where('status', 'hadir')->count(),
            'total_alpha' => $attendances->where('status', 'alpha')->count(),
            'total_absensi' => $attendances->count(),
        ];

        // 4. Generasi PDF
        $pdf = Pdf::loadView('pdfs.murid_attendance_report', compact(
            'attendances',
            'reportTitle',
            'periodText',
            'summary',
            'muridName'
        ));

        // Opsional: Atur ukuran kertas dan orientasi jika perlu
        // $pdf->setPaper('A4', 'landscape');

        // Untuk menampilkan di browser
        return $pdf->stream('laporan_absensi_murid_' . Str::slug($periodText) . '.pdf');

        // Untuk langsung download
        // return $pdf->download('laporan_absensi_murid_' . Str::slug($periodText) . '.pdf');
    }
}
