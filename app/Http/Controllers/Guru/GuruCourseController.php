<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwimmingCourse; // Import model SwimmingCourse
use App\Models\Schedule;       // Import model Schedule
use App\Models\Location;       // Import model Location
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID guru yang sedang login

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
            'start_time'         => 'required|date',
            'end_time'           => 'required|date|after:start_time',
            'max_students'       => 'required|integer|min:1',
        ]);

        Schedule::create([
            'swimming_course_id' => $request->swimming_course_id,
            'guru_id'            => Auth::id(), // ID guru yang sedang login
            'location_id'        => $request->location_id,
            'start_time'         => $request->start_time,
            'end_time'           => $request->end_time,
            'max_students'       => $request->max_students,
            'status'             => 'active', // Set status default
        ]);

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Menampilkan formulir untuk mengedit jadwal yang sudah ada.
     */
    public function editSchedule(Schedule $schedule)
    {
        // Pastikan guru yang sedang login memiliki hak edit jadwal ini
        if ($schedule->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); // Atau redirect dengan pesan error
        }

        $swimmingCourses = SwimmingCourse::all(); // Untuk dropdown pilihan kursus jika ingin diubah
        $locations = Location::all(); // Untuk dropdown pilihan lokasi

        return view('guru.courses.edit_schedule', compact('schedule', 'swimmingCourses', 'locations'));
    }

    /**
     * Memperbarui jadwal di database.
     */
    public function updateSchedule(Request $request, Schedule $schedule)
    {
        // Pastikan guru yang sedang login memiliki hak update jadwal ini
        if ($schedule->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id'        => 'required|exists:locations,id',
            'start_time'         => 'required|date',
            'end_time'           => 'required|date|after:start_time',
            'max_students'       => 'required|integer|min:1',
        ]);

        $schedule->update([
            'swimming_course_id' => $request->swimming_course_id,
            'location_id'        => $request->location_id,
            'start_time'         => $request->start_time,
            'end_time'           => $request->end_time,
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
        // Pastikan guru yang sedang login memiliki hak hapus jadwal ini
        if ($schedule->guru_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();

        return redirect()->route('guru.courses.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}