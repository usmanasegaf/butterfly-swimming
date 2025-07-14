<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SwimmingCourse;
use App\Models\Location;
use App\Models\User; // Untuk memilih guru
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua jadwal dengan eager loading relasi yang dibutuhkan
        $schedules = Schedule::with(['swimmingCourse', 'guru', 'location'])
                             ->orderBy('day_of_week')
                             ->orderBy('start_time_of_day')
                             ->paginate(10); // Menampilkan 10 jadwal per halaman

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $swimmingCourses = SwimmingCourse::all();
        $locations = Location::all();
        $gurus = User::where('role', 'guru')->where('status', 'active')->get(); // Ambil semua guru aktif

        return view('admin.schedules.create', compact('swimmingCourses', 'locations', 'gurus'));
    }

    /**
     * Store a newly created schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'guru_id'            => 'required|exists:users,id', // Validasi guru_id
            'location_id'        => 'required|exists:locations,id',
            'day_of_week'        => 'required|integer|min:1|max:7', // 1=Senin, 7=Minggu
            'start_time_of_day'  => 'required|date_format:H:i',
            'end_time_of_day'    => 'required|date_format:H:i|after:start_time_of_day',
            'max_students'       => 'required|integer|min:1',
            'status'             => 'required|string|in:active,cancelled', // Admin bisa set status
        ]);

        Schedule::create([
            'swimming_course_id' => $request->swimming_course_id,
            'guru_id'            => $request->guru_id,
            'location_id'        => $request->location_id,
            'day_of_week'        => $request->day_of_week,
            'start_time_of_day'  => $request->start_time_of_day,
            'end_time_of_day'    => $request->end_time_of_day,
            'max_students'       => $request->max_students,
            'status'             => $request->status,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified schedule.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\View\View
     */
    public function edit(Schedule $schedule)
    {
        $swimmingCourses = SwimmingCourse::all();
        $locations = Location::all();
        $gurus = User::where('role', 'guru')->where('status', 'active')->get(); // Ambil semua guru aktif

        return view('admin.schedules.edit', compact('schedule', 'swimmingCourses', 'locations', 'gurus'));
    }

    /**
     * Update the specified schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'guru_id'            => 'required|exists:users,id',
            'location_id'        => 'required|exists:locations,id',
            'day_of_week'        => 'required|integer|min:1|max:7',
            'start_time_of_day'  => 'required|date_format:H:i',
            'end_time_of_day'    => 'required|date_format:H:i|after:start_time_of_day',
            'max_students'       => 'required|integer|min:1',
            'status'             => 'required|string|in:active,cancelled',
        ]);

        $schedule->update([
            'swimming_course_id' => $request->swimming_course_id,
            'guru_id'            => $request->guru_id,
            'location_id'        => $request->location_id,
            'day_of_week'        => $request->day_of_week,
            'start_time_of_day'  => $request->start_time_of_day,
            'end_time_of_day'    => $request->end_time_of_day,
            'max_students'       => $request->max_students,
            'status'             => $request->status,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified schedule from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
