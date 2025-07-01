<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\SwimmingCourse;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class GuruScheduleController extends Controller
{
    // Daftar jadwal milik guru
    public function index()
    {
        $schedules = Schedule::where('guru_id', Auth::id())->with(['swimmingCourse', 'location'])->get();
        return view('guru.jadwal.index', compact('schedules'));
    }

    // Form tambah jadwal
    public function create()
    {
        $courses = SwimmingCourse::all();
        $locations = Location::all();
        return view('guru.jadwal.create', compact('courses', 'locations'));
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id' => 'required|exists:locations,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);

        Schedule::create([
            'guru_id' => Auth::id(),
            'swimming_course_id' => $request->swimming_course_id,
            'location_id' => $request->location_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
        ]);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Form edit jadwal
    public function edit($id)
    {
        $schedule = Schedule::where('guru_id', Auth::id())->findOrFail($id);
        $courses = SwimmingCourse::all();
        $locations = Location::all();
        return view('guru.jadwal.edit', compact('schedule', 'courses', 'locations'));
    }

    // Update jadwal
    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'location_id' => 'required|exists:locations,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ]);

        $schedule->update([
            'swimming_course_id' => $request->swimming_course_id,
            'location_id' => $request->location_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
        ]);

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil diupdate');
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $schedule = Schedule::where('guru_id', Auth::id())->findOrFail($id);
        $schedule->delete();
        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}