<?php

namespace App\Http\Controllers\Murid;

use App\Models\Murid;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MuridController extends Controller
{
    // List semua murid
    public function index()
    {
        $murids = Murid::with('schedules')->get();
        return view('murid.index', compact('murids'));
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
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'expired_at' => 'required|date'
        ]);
        Murid::create($request->all());
        return redirect()->route('murid.index')->with('success', 'Murid berhasil ditambahkan');
    }
}