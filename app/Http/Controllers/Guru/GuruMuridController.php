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
        $murids = User::whereHas('gurus', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->where('role', 'murid')->paginate(10);

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

    // Metode assignCourseForm dihapus karena kita akan menggunakan modal
    // public function assignCourseForm(User $murid)
    // {
    //     // ... (kode sebelumnya)
    // }

    public function assignCourse(Request $request, User $murid)
    {
        // Pastikan guru yang login adalah pembimbing murid ini
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah murid ini.');
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id',
        ]);

        $selectedCourse = SwimmingCourse::find($request->swimming_course_id);
        $biaya = $selectedCourse ? $selectedCourse->price : 0;

        // Tanggal mulai otomatis adalah hari ini
        $startDate = Carbon::now();
        // Tanggal selesai dihitung dari durasi kursus (dalam minggu)
        $endDate = $startDate->copy()->addWeeks($selectedCourse->duration);

        // Cari pendaftaran yang sudah ada untuk murid dan kursus ini (jika ada dan belum selesai)
        $existingRegistration = $murid->registrations()
                                            ->where('swimming_course_id', $request->swimming_course_id)
                                            ->whereIn('status', ['pending', 'approved'])
                                            ->first();

        if ($existingRegistration) {
            // Jika ada pendaftaran yang masih aktif/pending untuk kursus yang sama, perbarui saja
            $existingRegistration->update([
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => 'approved', // Langsung disetujui oleh guru
                'biaya'      => $biaya, // Perbarui biaya
                'guru_id'    => Auth::id(), // Guru yang login adalah guru_id
            ]);
            $message = 'Penugasan kursus murid berhasil diperbarui!';
        } else {
            // Jika belum ada pendaftaran aktif/pending untuk kursus ini, buat yang baru
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

        // Juga perbarui kolom di model User untuk konsistensi dan kemudahan akses di dashboard murid
        $murid->swimming_course_id = $request->swimming_course_id;
        $murid->course_assigned_at = $startDate; // Gunakan start_date sebagai course_assigned_at
        $murid->save();

        return redirect()->route('guru.murid.index')->with('success', $message);
    }
}
