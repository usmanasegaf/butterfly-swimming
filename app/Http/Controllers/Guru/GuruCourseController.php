<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwimmingCourse; // Import model SwimmingCourse
use App\Models\Schedule;       // Import model Schedule
use App\Models\Location;       // Import model Location
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID guru yang sedang login
use Carbon\Carbon; // Tambahkan ini untuk parsing waktu

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
        $guruSchedules = Auth::user()->jadwalGuru()->with('swimmingCourse', 'location')->get();

        // Mengembalikan view yang benar: guru.courses.index
        return view('guru.courses.index', compact('swimmingCourses', 'guruSchedules'));
    }

    /**
     * Menampilkan formulir untuk membuat jadwal baru berdasarkan kursus yang dipilih.
     */
    public function createScheduleForm(SwimmingCourse $swimmingCourse)
    {
        // Ambil daftar lokasi untuk dropdown di form
        $locations = Location::all();

        // Mengembalikan view yang benar: guru.courses.create_schedule
        return view('guru.courses.create_schedule', compact('swimmingCourse', 'locations'));
    }

    /**
     * Menyimpan jadwal baru yang dibuat oleh guru.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id'        => 'required|exists:locations,id',
            'max_students'       => 'required|integer|min:1',
            'day_of_week'       => 'required|integer|min:1|max:7', // 1=Senin, 7=Minggu
            'start_time_of_day' => 'required|date_format:H:i', // Format jam:menit (misal: 14:30)
            'end_time_of_day'   => 'required|date_format:H:i|after:start_time_of_day', // Harus setelah jam mulai
        ]);

        Schedule::create([
            'swimming_course_id' => $request->swimming_course_id,
            'guru_id'            => Auth::id(),
            'location_id'        => $request->location_id,
            'day_of_week'       => $request->day_of_week,
            'start_time_of_day' => $request->start_time_of_day,
            'end_time_of_day'   => $request->end_time_of_day,
            'max_students'       => $request->max_students,
            'status'             => 'active', // Set status default
        ]);
        // dd($schedule);

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Menampilkan formulir untuk mengedit jadwal yang sudah ada.
     */
    public function editSchedule(Schedule $schedule)
    {

        $swimmingCourses = SwimmingCourse::all(); // Untuk dropdown pilihan kursus jika ingin diubah
        $locations = Location::all(); // Untuk dropdown pilihan lokasi

        return view('guru.courses.edit_schedule', compact('schedule', 'swimmingCourses', 'locations'));
    }

    /**
     * Memperbarui jadwal di database.
     */
    public function updateSchedule(Request $request, Schedule $schedule)
    {

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id'        => 'required|exists:locations,id',
            'max_students'       => 'required|integer|min:1',
            'day_of_week'       => 'required|integer|min:1|max:7',
            'start_time_of_day' => 'required|date_format:H:i',
            'end_time_of_day'   => 'required|date_format:H:i|after:start_time_of_day',
        ]);

        $schedule->update([
            'swimming_course_id' => $request->swimming_course_id,
            'location_id'        => $request->location_id,
            'day_of_week'       => $request->day_of_week,
            'start_time_of_day' => $request->start_time_of_day,
            'end_time_of_day'   => $request->end_time_of_day,
            'max_students'       => $request->max_students,
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