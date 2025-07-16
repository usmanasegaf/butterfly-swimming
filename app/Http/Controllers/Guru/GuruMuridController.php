<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SwimmingCourse;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruMuridController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();
        // Load murid dengan relasi swimmingCourse dan registrations yang aktif
        $murids = User::whereHas('gurus', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })
        ->where('role', 'murid')
        ->with(['swimmingCourse', 'registrations' => function($query) {
            // Muat pendaftaran yang disetujui, diurutkan berdasarkan tanggal mulai terbaru
            $query->where('status', 'approved')->latest('start_date');
        }])
        ->paginate(10);

        // Ambil semua kursus renang yang aktif untuk dropdown di modal
        $availableCourses = SwimmingCourse::where('is_active', true)->get();

        return view('guru.murid.index', compact('murids', 'availableCourses'));
    }

    public function create()
    {
        $murids = User::where('role', 'murid')->whereDoesntHave('gurus', function ($q) {
            $q->where('guru_id', Auth::id());
        })->get();
        return view('guru.murid.create', compact('murids'));
    }

    public function store(Request $request)
    {
        $request->validate(['murid_id' => 'required|exists:users,id']);
        Auth::user()->murids()->attach($request->murid_id);
        return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Auth::user()->murids()->detach($id);
        return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil dihapus');
    }

    public function assignCourse(Request $request, User $murid)
    {
        // Pastikan guru yang login adalah pembimbing murid ini
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk mengubah murid ini.'], 403);
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
        ]);

        $selectedCourse = SwimmingCourse::find($request->swimming_course_id);
        $biaya = $selectedCourse ? $selectedCourse->price : 0;

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addWeeks($selectedCourse->duration);

        // Cek apakah ada pendaftaran yang disetujui yang dimulai HARI INI
        // Ini untuk skenario "ubah kursus di hari yang sama"
        $existingTodayRegistration = $murid->registrations()
                                            ->where('status', 'approved')
                                            ->whereDate('start_date', $startDate->toDateString())
                                            ->first();

        if ($existingTodayRegistration) {
            // Jika ada pendaftaran yang disetujui yang dimulai hari ini, perbarui saja
            $existingTodayRegistration->update([
                'swimming_course_id' => $request->swimming_course_id,
                'end_date'           => $endDate, // Perbarui end_date sesuai durasi kursus baru
                'biaya'              => $biaya,   // Perbarui biaya
                'guru_id'            => Auth::id(), // Pastikan guru_id tetap guru yang login
            ]);
            $message = 'Kursus murid berhasil diubah!';
        } else {
            // Jika tidak ada pendaftaran yang disetujui yang dimulai hari ini,
            // cek apakah ada pendaftaran aktif lainnya yang belum selesai
            $activeRegistration = $murid->registrations()
                                        ->where('status', 'approved')
                                        ->where('end_date', '>=', Carbon::now()->toDateString())
                                        ->first();

            if ($activeRegistration) {
                // Jika ada pendaftaran aktif yang belum selesai,
                // kita tidak boleh membuat pendaftaran baru tanpa perpanjangan eksplisit.
                // Ini adalah skenario di mana guru mencoba menugaskan kursus baru
                // padahal murid masih punya kursus aktif yang belum selesai.
                // Kita bisa memberikan error atau mengarahkan ke fitur perpanjangan.
                return response()->json(['error' => 'Murid ini masih memiliki kursus aktif yang belum selesai. Gunakan fitur "Perpanjang Kursus" jika ingin menambah durasi, atau batalkan kursus sebelumnya.'], 400);
            } else {
                // Jika tidak ada pendaftaran aktif atau pendaftaran hari ini, buat yang baru
                Registration::create([
                    'user_id'            => $murid->id,
                    'swimming_course_id' => $request->swimming_course_id,
                    'start_date'         => $startDate,
                    'end_date'           => $endDate,
                    'status'             => 'approved', // Langsung disetujui oleh guru
                    'biaya'              => $biaya, // Simpan biaya
                    'guru_id'            => Auth::id(), // Guru yang login adalah guru_id
                ]);
                $message = 'Kursus berhasil ditugaskan kepada murid!';
            }
        }

        // Juga perbarui kolom di model User untuk konsistensi dan kemudahan akses di dashboard murid
        // Ini penting agar remaining_lesson_days di dashboard murid update
        $murid->swimming_course_id = $request->swimming_course_id;
        $murid->course_assigned_at = $startDate; // Gunakan start_date sebagai course_assigned_at
        $murid->save();

        return response()->json(['success' => $message]);
    }

    public function extendCourse(Request $request, User $murid)
    {
        // Pastikan guru yang login adalah pembimbing murid ini
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            return response()->json(['error' => 'Anda tidak memiliki akses untuk mengubah murid ini.'], 403);
        }

        $request->validate([
            'additional_weeks' => 'required|integer|min:1',
        ]);

        // Cari pendaftaran yang disetujui dan belum selesai untuk murid ini
        $activeRegistration = $murid->registrations()
                                    ->where('status', 'approved')
                                    ->where('end_date', '>=', Carbon::now()->toDateString())
                                    ->first();

        if (! $activeRegistration) {
            return response()->json(['error' => 'Murid ini tidak memiliki kursus aktif yang bisa diperpanjang.'], 400);
        }

        // Hitung tanggal selesai yang baru
        $newEndDate = Carbon::parse($activeRegistration->end_date)->addWeeks($request->additional_weeks);

        $activeRegistration->update([
            'end_date' => $newEndDate,
        ]);

        // Perbarui juga course_assigned_at di model User jika diperlukan
        // (opsional, tergantung bagaimana Anda ingin remaining_lesson_days dihitung)
        // Jika remaining_lesson_days selalu dihitung dari end_date, maka ini tidak wajib.
        // Tapi untuk konsistensi, bisa diperbarui juga.
        // $murid->course_assigned_at = Carbon::now(); // Atau biarkan saja seperti tanggal penugasan awal
        // $murid->save();

        return response()->json(['success' => 'Kursus berhasil diperpanjang hingga ' . $newEndDate->format('d M Y') . '!']);
    }
}
