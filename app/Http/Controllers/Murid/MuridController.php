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

        // Ambil jadwal les selanjutnya (jadwal terdekat >= hari ini)
        $jadwal_selanjutnya = $murid->schedules()
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
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

        // Jadwal berikutnya
        $next_schedule = $murid->schedules()
            ->where('date', '>=', Carbon::now())
            ->orderBy('date')
            ->orderBy('start_time')
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
