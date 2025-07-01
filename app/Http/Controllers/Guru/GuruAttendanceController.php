<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Murid;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class GuruAttendanceController extends Controller
{
    // Tampilkan daftar absensi untuk guru yang login
    public function index()
    {
        $attendances = Attendance::where('guru_id', Auth::id())->with(['murid', 'schedule', 'location'])->orderBy('date', 'desc')->get();
        return view('guru.attendance.index', compact('attendances'));
    }

    // Form absensi untuk jadwal tertentu
    public function create($schedule_id)
    {
        $schedule = Schedule::findOrFail($schedule_id);
        $murids = $schedule->murids ?? []; // Pastikan ada relasi murids di Schedule
        $locations = Location::all();
        return view('guru.attendance.create', compact('schedule', 'murids', 'locations'));
    }

    // Simpan absensi
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'murid_id' => 'required|exists:murids,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'is_present' => 'required|boolean',
        ]);

        Attendance::create([
            'schedule_id' => $request->schedule_id,
            'murid_id' => $request->murid_id,
            'guru_id' => Auth::id(),
            'location_id' => $request->location_id,
            'date' => $request->date,
            'is_present' => $request->is_present,
        ]);

        return redirect()->route('guru.attendance.index')->with('success', 'Absensi berhasil disimpan');
    }
}