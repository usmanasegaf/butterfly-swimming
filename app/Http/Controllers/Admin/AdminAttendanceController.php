<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User; // For Guru and Murid filters
use App\Models\SwimmingCourse; // For Course filter
use App\Models\Location; // For Location filter
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Ensure this is imported for PDF generation
use Illuminate\Support\Str; // For Str::slug()

class AdminAttendanceController extends Controller
{
    /**
     * Display a listing of all attendances with comprehensive filters.
     * Menampilkan daftar semua absensi dengan filter komprehensif.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start with all attendances and eager load necessary relationships
        // Mulai dengan semua absensi dan eager load relasi yang diperlukan
        $query = Attendance::with(['schedule.swimmingCourse', 'schedule.guru', 'schedule.location', 'student']);

        // Filter by Guru
        // Filter berdasarkan Guru
        if ($request->filled('guru_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('guru_id', $request->guru_id);
            });
        }

        // Filter by Murid (Student)
        // Filter berdasarkan Murid
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by Course
        // Filter berdasarkan Kursus
        if ($request->filled('course_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('swimming_course_id', $request->course_id);
            });
        }

        // Filter by Location
        // Filter berdasarkan Lokasi
        if ($request->filled('location_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
        }

        // Filter by Date Range
        // Filter berdasarkan Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('attendance_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('attendance_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('attendance_date', '<=', $request->end_date);
        }

        // Order and paginate the results
        // Urutkan dan paginasi hasilnya
        $attendances = $query->orderBy('attendance_date', 'desc')
                             ->orderBy('schedule_id')
                             ->paginate(20); // Display 20 attendances per page

        // Data for filter dropdowns
        // Data untuk dropdown filter
        $gurus = User::where('role', 'guru')->get();
        $murids = User::where('role', 'murid')->get();
        $courses = SwimmingCourse::all();
        $locations = Location::all();

        return view('admin.attendances.index', compact('attendances', 'gurus', 'murids', 'courses', 'locations'));
    }

    /**
     * Generate PDF report for attendances with comprehensive filters.
     * Menghasilkan laporan PDF untuk absensi dengan filter komprehensif.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        // Start with all attendances and eager load necessary relationships
        // Mulai dengan semua absensi dan eager load relasi yang diperlukan
        $query = Attendance::with(['schedule.swimmingCourse', 'schedule.guru', 'schedule.location', 'student']);

        $reportTitle = "Laporan Absensi Global";
        $periodText = "Semua Periode";
        $filtersApplied = []; // To list filters in the PDF

        // Apply filters for PDF generation, similar to index method
        // Terapkan filter untuk pembuatan PDF, mirip dengan metode index
        if ($request->filled('guru_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('guru_id', $request->guru_id);
            });
            $guruName = User::find($request->guru_id)->name ?? 'N/A';
            $filtersApplied[] = "Guru: " . $guruName;
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
            $studentName = User::find($request->student_id)->name ?? 'N/A';
            $filtersApplied[] = "Murid: " . $studentName;
        }

        if ($request->filled('course_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('swimming_course_id', $request->course_id);
            });
            $courseName = SwimmingCourse::find($request->course_id)->name ?? 'N/A';
            $filtersApplied[] = "Kursus: " . $courseName;
        }

        if ($request->filled('location_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            });
            $locationName = Location::find($request->location_id)->name ?? 'N/A';
            $filtersApplied[] = "Lokasi: " . $locationName;
        }

        // Period filtering for PDF (weekly, monthly, yearly, or custom date range)
        // Pemfilteran periode untuk PDF (mingguan, bulanan, tahunan, atau rentang tanggal kustom)
        $reportType = $request->input('report_type');
        $startDate = null;
        $endDate = null;

        switch ($reportType) {
            case 'weekly':
                $request->validate(['weekly_date' => 'required|date']);
                $selectedDate = Carbon::parse($request->input('weekly_date'));
                $startDate = $selectedDate->startOfWeek(Carbon::MONDAY)->toDateString();
                $endDate = $selectedDate->endOfWeek(Carbon::SUNDAY)->toDateString();
                $periodText = "Minggu: " . Carbon::parse($startDate)->format('d M Y') . " - " . Carbon::parse($endDate)->format('d M Y');
                break;
            case 'monthly':
                $request->validate(['monthly_month' => 'required|integer|min:1|max:12', 'monthly_year' => 'required|integer|min:2000|max:' . date('Y')]);
                $year = $request->input('monthly_year');
                $month = $request->input('monthly_month');
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
                $periodText = "Bulan: " . Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
                break;
            case 'yearly':
                $request->validate(['yearly_year' => 'required|integer|min:2000|max:' . date('Y')]);
                $year = $request->input('yearly_year');
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString();
                $periodText = "Tahun: " . $year;
                break;
            case 'custom_range':
                // Use 'start_date_custom' and 'end_date_custom' from the form for custom range
                // Gunakan 'start_date_custom' dan 'end_date_custom' dari form untuk rentang kustom
                $request->validate(['start_date_custom' => 'required|date', 'end_date_custom' => 'required|date|after_or_equal:start_date_custom']);
                $startDate = $request->input('start_date_custom');
                $endDate = $request->input('end_date_custom');
                $periodText = "Periode Kustom: " . Carbon::parse($startDate)->format('d M Y') . " - " . Carbon::parse($endDate)->format('d M Y');
                break;
            default:
                // If no specific report type, use the general date range filters if present
                // Jika tidak ada tipe laporan spesifik, gunakan filter rentang tanggal umum jika ada
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = $request->input('start_date');
                    $endDate = $request->input('end_date');
                    $periodText = "Periode: " . Carbon::parse($startDate)->format('d M Y') . " - " . Carbon::parse($endDate)->format('d M Y');
                }
                break;
        }

        if ($startDate && $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }

        // Get all filtered attendances (no pagination for PDF)
        // Ambil semua absensi yang difilter (tanpa paginasi untuk PDF)
        $attendances = $query->orderBy('attendance_date')
                             ->orderBy('schedule_id')
                             ->orderBy('student_id')
                             ->get();

        // Calculate summary statistics
        // Hitung statistik ringkasan
        $summary = [
            'total_hadir' => $attendances->where('status', 'hadir')->count(),
            'total_alpha' => $attendances->where('status', 'alpha')->count(),
            'total_absensi' => $attendances->count(),
        ];

        // Load PDF view with data
        // Muat tampilan PDF dengan data
        $pdf = Pdf::loadView('pdfs.admin_attendance_report', compact(
            'attendances',
            'reportTitle',
            'periodText',
            'summary',
            'filtersApplied' // Pass applied filters to PDF
        ));

        // Stream the PDF to the browser
        // Alirkan PDF ke browser
        return $pdf->stream('laporan_absensi_global_' . Str::slug($periodText) . '.pdf');
    }
}
