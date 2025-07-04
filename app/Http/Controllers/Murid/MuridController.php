<?php
namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MuridController extends Controller
{
    public function index()
    {
        $murid     = auth()->user();
        $schedules = $murid->schedules()->with('swimmingCourse', 'location')->get();

        $expired_at = $murid->expired_at ?? null;
        $sisa_hari  = $expired_at ? now()->diffInDays($expired_at, false) : null;

// Ambil jadwal les selanjutnya (Logic untuk jadwal berulang mingguan)
        $currentDayOfWeek = now()->dayOfWeekIso;
        $currentTime      = now()->format('H:i:s');

        $jadwal_selanjutnya = $murid->schedules()
        // Cari jadwal yang berlangsung hari ini, yang belum lewat waktunya
            ->where(function ($query) use ($currentDayOfWeek, $currentTime) {
                $query->where('day_of_week', $currentDayOfWeek)
                    ->where('start_time_of_day', '>=', $currentTime);
            })
        // Jika tidak ada jadwal yang akan datang hari ini, cari jadwal di hari-hari berikutnya dalam minggu ini
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '>', $currentDayOfWeek);
            })
        // Jika tidak ada jadwal dari hari ini sampai akhir minggu, cari jadwal dari awal minggu depan (putaran minggu)
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '<', $currentDayOfWeek);
            })
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time_of_day', 'asc')
            ->with('location')
            ->first();

        return view('murid.index', compact('schedules', 'expired_at', 'sisa_hari', 'jadwal_selanjutnya'));
    }

    // Detail murid & jadwal
    public function show($id)
    {
        $murid = Murid::with('schedules')->findOrFail($id);

        // Reminder expired
        $expired_in = $murid->expired_at ? Carbon::now()->diffInDays($murid->expired_at, false) : null;

// Jadwal berikutnya (Logic untuk jadwal berulang mingguan)
        $currentDayOfWeek = Carbon::now()->dayOfWeekIso;
        $currentTime      = Carbon::now()->format('H:i:s');

        $next_schedule = $murid->schedules()
        // Cari jadwal yang berlangsung hari ini, yang belum lewat waktunya
            ->where(function ($query) use ($currentDayOfWeek, $currentTime) {
                $query->where('day_of_week', $currentDayOfWeek)
                    ->where('start_time_of_day', '>=', $currentTime);
            })
        // Jika tidak ada jadwal yang akan datang hari ini, cari jadwal di hari-hari berikutnya dalam minggu ini
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '>', $currentDayOfWeek);
            })
        // Jika tidak ada jadwal dari hari ini sampai akhir minggu, cari jadwal dari awal minggu depan (putaran minggu)
            ->orWhere(function ($query) use ($currentDayOfWeek) {
                $query->where('day_of_week', '<', $currentDayOfWeek);
            })
            ->orderBy('day_of_week', 'asc')
            ->orderBy('start_time_of_day', 'asc')
            ->first();

        return view('murid.show', compact('murid', 'expired_in', 'next_schedule'));
    }

    // Form tambah murid
    public function create()
    {
        return view('murid.create');
    }

    // Simpan murid baru
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'email'      => 'nullable|email',
            'phone'      => 'nullable',
            'expired_at' => 'required|date',
        ]);
        Murid::create($request->all());
        return redirect()->route('murid.index')->with('success', 'Murid berhasil ditambahkan');
    }
}
