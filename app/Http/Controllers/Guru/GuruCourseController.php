<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Schedule;       // Import model SwimmingCourse
use App\Models\SwimmingCourse; // Import model Schedule
use App\Models\User;           // Import model Location
use Carbon\Carbon;             // Untuk mendapatkan ID guru yang sedang login
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruCourseController extends Controller
{
    /**
     * Menampilkan daftar kursus renang yang tersedia (dibuat oleh Admin)
     * dan jadwal yang sudah dibuat oleh guru yang sedang login.
     */
    public function index()
    {
        // Ambil semua kursus renang yang tersedia (dibuat Admin)
        $swimmingCourses = SwimmingCourse::all();

        // Ambil jadwal yang sudah dibuat oleh guru yang sedang login
        $guruSchedules = Auth::user()->jadwalGuru()->with('swimmingCourse', 'location', 'murid')->get();

        // Mengembalikan view yang benar: guru.courses.index
        return view('guru.courses.index', compact('swimmingCourses', 'guruSchedules'));
    }

    /**
     * Menampilkan formulir untuk membuat jadwal baru berdasarkan kursus yang dipilih.
     */
    public function createScheduleForm(SwimmingCourse $swimmingCourse)
    {
        $locations = Location::all();

        // 1. Dapatkan ID semua murid yang SUDAH punya jadwal APAPUN.
        $scheduledMuridIds = Schedule::whereNotNull('murid_id')->pluck('murid_id');

        // 2. Ambil murid bimbingan guru yang login, KECUALI yang ID-nya sudah ada di daftar di atas.
        $murids = Auth::user()->murids()
            ->whereNotIn('users.id', $scheduledMuridIds)
            ->get();

        return view('guru.courses.create_schedule', compact('swimmingCourse', 'locations', 'murids'));
    }

    /**
     * Menyimpan jadwal baru yang dibuat oleh guru.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id'        => 'required|exists:locations,id',
            'murid_id'           => 'required|exists:users,id',
            'day_of_week'        => 'required|integer|min:1|max:7',                     // 1=Senin, 7=Minggu
            'start_time_of_day'  => 'required|date_format:H:i',                         // Format jam:menit (misal: 14:30)
            'end_time_of_day'    => 'required|date_format:H:i|after:start_time_of_day', // Harus setelah jam mulai
        ]);

        DB::beginTransaction();
        try {
            // Langkah 1: Buat jadwal seperti biasa
            Schedule::create([
                'swimming_course_id' => $request->swimming_course_id,
                'guru_id'            => Auth::id(),
                'location_id'        => $request->location_id,
                'murid_id'           => $request->murid_id,
                'day_of_week'        => $request->day_of_week,
                'start_time_of_day'  => $request->start_time_of_day,
                'end_time_of_day'    => $request->end_time_of_day,
                'max_students'       => 1,
                'status'             => 'active',
            ]);

            // Langkah 2: Ambil data murid dan kursus yang relevan
            $murid  = User::find($request->murid_id);
            $course = SwimmingCourse::find($request->swimming_course_id);

            // Langkah 3: Tugaskan kursus ke murid (mirip seperti di GuruMuridController)
            $murid->swimming_course_id     = $course->id;
            $murid->course_assigned_at     = Carbon::now();
            $murid->jumlah_pertemuan_paket = $course->jumlah_pertemuan;
            $murid->pertemuan_ke           = 0;
            $murid->save();

            DB::commit();
            return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dibuat dan kursus telah ditugaskan ke murid!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat jadwal: ' . $e->getMessage());
        }

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Menampilkan formulir untuk mengedit jadwal yang sudah ada.
     */
    public function editSchedule(Schedule $schedule)
    {

        $swimmingCourses = SwimmingCourse::all(); // Untuk dropdown pilihan kursus jika ingin diubah
        $locations       = Location::all();       // Untuk dropdown pilihan lokasi
        $murids          = Auth::user()->murids;

        return view('guru.courses.edit_schedule', compact('schedule', 'swimmingCourses', 'locations', 'murids'));
    }

    /**
     * Memperbarui jadwal di database.
     */
    public function updateSchedule(Request $request, Schedule $schedule)
    {

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id'        => 'required|exists:locations,id',
            'murid_id'           => 'required|exists:users,id',
            'day_of_week'        => 'required|integer|min:1|max:7',
            'start_time_of_day'  => 'required|date_format:H:i',
            'end_time_of_day'    => 'required|date_format:H:i|after:start_time_of_day',
        ]);

        $schedule->update([
            'swimming_course_id' => $request->swimming_course_id,
            'location_id'        => $request->location_id,
            'murid_id'           => $request->murid_id,
            'day_of_week'        => $request->day_of_week,
            'start_time_of_day'  => $request->start_time_of_day,
            'end_time_of_day'    => $request->end_time_of_day,
            'max_students'       => 1,
            // 'status'             => $request->status, // Jika status bisa diubah dari form
        ]);

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Menghapus jadwal dari database.
     */
    public function destroySchedule(Schedule $schedule)
    {

        $schedule->delete();

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
