<?php
namespace App\Http\Controllers\Guru;

use App\Models\Attendance;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
        $schedule  = Schedule::findOrFail($schedule_id);
        $murids    = $schedule->murids ?? []; // Pastikan ada relasi murids di Schedule
        $locations = Location::all();
        return view('guru.attendance.create', compact('schedule', 'murids', 'locations'));
    }

    // Simpan absensi
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'murid_id'    => 'required|exists:murids,id',
            'location_id' => 'required|exists:locations,id',
            'date'        => 'required|date',
            'is_present'  => 'required|boolean',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);
        $location = \App\Models\Location::findOrFail($request->location_id);

        // Haversine formula (jarak dalam meter)
        $earthRadius = 6371000;
        $dLat        = deg2rad($request->latitude - $location->latitude);
        $dLon        = deg2rad($request->longitude - $location->longitude);
        $lat1        = deg2rad($location->latitude);
        $lat2        = deg2rad($request->latitude);

        $a = sin($dLat / 2) * sin($dLat / 2) +
        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c        = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        if ($distance > 400) {
            return back()->with('error', 'Anda berada di luar radius lokasi cabang (maksimal 450 meter)');
        }

        Attendance::create([
            'schedule_id' => $request->schedule_id,
            'murid_id'    => $request->murid_id,
            'guru_id'     => Auth::id(),
            'location_id' => $request->location_id,
            'date'        => $request->date,
            'is_present'  => $request->is_present,
        ]);

        return redirect()->route('guru.attendance.index')->with('success', 'Absensi berhasil disimpan');
    }
}
