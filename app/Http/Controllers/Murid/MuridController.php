<?php
namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\User; // Gunakan App\Models\User, bukan App\Models\Murid
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada

class MuridController extends Controller
{
    public function index() // Metode ini sekarang akan berfungsi sebagai halaman "Kursus Saya"
    {
        $user = Auth::user(); // Ambil instance user yang sedang login
        $user->load('swimmingCourse'); // Muat relasi kursus renang murid

        $assignedCourse = $user->swimmingCourse; // Ini adalah data kursus yang ditugaskan

        // Hapus logic lama untuk $expired_at, $sisa_hari, dan $jadwal_selanjutnya
        // karena kita akan menggunakan accessor dari model User dan jadwal selanjutnya ada di dashboard
        $expired_at = null; // Setel ke null atau hapus baris ini
        $sisa_hari = null; // Setel ke null atau hapus baris ini
        $jadwal_selanjutnya = null; // Setel ke null atau hapus baris ini


        // Kirimkan $user dan $assignedCourse ke view untuk menampilkan detail kursus
        return view('murid.index', compact('user', 'assignedCourse', 'expired_at', 'sisa_hari', 'jadwal_selanjutnya'));
    }

    // Detail murid & jadwal
    public function show($id)
    {
        // Pastikan Anda memuat relasi yang diperlukan di sini jika digunakan di view show
        // Contoh: $murid = User::with('schedules', 'swimmingCourse')->findOrFail($id);
        $murid = User::with('schedules')->findOrFail($id);

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
            ->with('location')
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
        // Gunakan model User, bukan Murid
        User::create($request->all()); 
        return redirect()->route('murid.index')->with('success', 'Murid berhasil ditambahkan');
    }
}