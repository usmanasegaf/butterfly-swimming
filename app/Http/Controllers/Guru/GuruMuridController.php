<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\SwimmingCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuruMuridController extends Controller
{
    // Daftar murid bimbingan guru
    public function index()
    {
        $murids = Auth::user()->murids()->get();
        return view('guru.murid.index', compact('murids'));
    }

    // Form tambah murid ke bimbingan
    public function create()
    {
        $murids = User::where('role', 'murid')->whereDoesntHave('gurus', function ($q) {
            $q->where('guru_id', Auth::id());
        })->get();
        return view('guru.murid.create', compact('murids'));
    }

    // Simpan murid ke bimbingan
    public function store(Request $request)
    {
        $request->validate(['murid_id' => 'required|exists:users,id']);
        Auth::user()->murids()->attach($request->murid_id);
        return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil ditambahkan');
    }

    // Hapus murid dari bimbingan
    public function destroy($id)
    {
        Auth::user()->murids()->detach($id);
        return redirect()->route('guru.murid.index')->with('success', 'Murid berhasil dihapus');
    }

    public function assignCourseForm(User $murid)
    {
        // Pastikan murid tersebut dibimbing oleh guru yang sedang login
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke murid ini.');
        }

        $availableCourses = SwimmingCourse::all(); // Mengambil semua kursus yang tersedia

        return view('guru.murid.assign-course', compact('murid', 'availableCourses'));
    }

    /**
     * Memproses penugasan kursus kepada murid.
     */
    public function assignCourse(Request $request, User $murid)
    {
        // Pastikan murid tersebut dibimbing oleh guru yang sedang login
        if (Auth::user()->role === 'guru' && ! $murid->gurus->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah murid ini.');
        }

        $request->validate([
            'swimming_course_id' => 'required|exists:swimming_courses,id', // Cukup pastikan ID kursus ada di tabel 'swimming_courses'
        ]);

        $murid->swimming_course_id = $request->swimming_course_id;
        $murid->course_assigned_at = now(); // Set tanggal penugasan ke waktu saat ini
        $murid->save();

        return redirect()->route('guru.murid.index')->with('success', 'Kursus berhasil ditetapkan kepada ' . $murid->name . '.');
    }
}
