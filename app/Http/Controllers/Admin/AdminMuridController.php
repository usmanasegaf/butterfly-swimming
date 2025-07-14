<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Digunakan untuk model Murid dan Guru
use App\Models\SwimmingCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Carbon\Carbon;

class AdminMuridController extends Controller
{
    /**
     * Display a listing of active students with filters.
     * Menampilkan daftar murid aktif dengan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'murid')
                     ->where('status', 'active') // Hanya tampilkan murid aktif
                     ->with(['gurus', 'swimmingCourse']); // Eager load guru pembimbing dan kursus

        // Filter berdasarkan nama/email murid
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan guru pembimbing
        if ($request->filled('guru_id')) {
            $query->whereHas('gurus', function ($q) use ($request) {
                $q->where('users.id', $request->guru_id); // Mengacu pada ID guru di tabel users
            });
        }

        // Filter berdasarkan kursus yang ditugaskan
        if ($request->filled('course_id')) {
            $query->where('swimming_course_id', $request->course_id);
        }

        $murids = $query->orderBy('name')
                        ->paginate(15); // Paginasi 15 murid per halaman

        // Data untuk dropdown filter
        $gurus = User::where('role', 'guru')->where('status', 'active')->get();
        $courses = SwimmingCourse::all();

        return view('admin.murids.index', compact('murids', 'gurus', 'courses'));
    }

    /**
     * Show the form for editing the specified active student.
     * Menampilkan formulir untuk mengedit murid aktif yang ditentukan.
     *
     * @param  \App\Models\User  $murid
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $murid)
    {
        // Pastikan murid adalah role 'murid' dan status 'active'
        if ($murid->role !== 'murid' || $murid->status !== 'active') {
            return redirect()->route('admin.murids.index')->with('error', 'Murid tidak ditemukan atau belum diverifikasi.');
        }

        $gurus = User::where('role', 'guru')->where('status', 'active')->get();
        $swimmingCourses = SwimmingCourse::all();

        // Ambil guru pembimbing saat ini (jika ada)
        $currentGuru = $murid->gurus->first();

        return view('admin.murids.edit', compact('murid', 'gurus', 'swimmingCourses', 'currentGuru'));
    }

    /**
     * Update the specified active student in storage.
     * Memperbarui murid aktif yang ditentukan di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $murid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $murid)
    {
        // Pastikan murid adalah role 'murid' dan status 'active'
        if ($murid->role !== 'murid' || $murid->status !== 'active') {
            return redirect()->route('admin.murids.index')->with('error', 'Murid tidak ditemukan atau belum diverifikasi.');
        }

        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:users,email,' . $murid->id,
            'guru_id'            => 'nullable|exists:users,id,role,guru,status,active', // Guru harus ada dan aktif
            'swimming_course_id' => 'nullable|exists:swimming_courses,id',
        ]);

        DB::beginTransaction();
        try {
            // Update basic user details
            // Perbarui detail dasar pengguna
            $murid->name = $request->name;
            $murid->email = $request->email;
            // Status tidak diubah di sini, hanya melalui verifikasi
            $murid->save();

            // Sync guru relationship
            // Sinkronkan relasi guru
            if ($request->filled('guru_id')) {
                $murid->gurus()->sync([$request->guru_id]);
            } else {
                $murid->gurus()->detach(); // Lepaskan semua guru jika tidak ada guru yang dipilih
            }

            // Update swimming course assignment
            // Perbarui penugasan kursus renang
            if ($request->filled('swimming_course_id')) {
                $murid->swimming_course_id = $request->swimming_course_id;
                // Hanya set course_assigned_at jika belum pernah di-set atau jika kursus berubah
                if (is_null($murid->course_assigned_at) || $murid->isDirty('swimming_course_id')) {
                    $murid->course_assigned_at = Carbon::now();
                }
            } else {
                $murid->swimming_course_id = null;
                $murid->course_assigned_at = null;
            }
            $murid->save(); // Simpan lagi setelah update kursus

            DB::commit();
            return redirect()->route('admin.murids.index')->with('success', 'Data murid berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data murid: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified active student from storage.
     * Menghapus murid aktif yang ditentukan dari penyimpanan.
     *
     * @param  \App\Models\User  $murid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $murid)
    {
        // Pastikan murid adalah role 'murid' dan status 'active'
        if ($murid->role !== 'murid' || $murid->status !== 'active') {
            return redirect()->route('admin.murids.index')->with('error', 'Murid tidak ditemukan atau belum diverifikasi.');
        }

        DB::beginTransaction();
        try {
            // Detach guru relationship before deleting the user
            // Lepaskan relasi guru sebelum menghapus pengguna
            $murid->gurus()->detach();
            $murid->delete();

            DB::commit();
            return redirect()->route('admin.murids.index')->with('success', 'Murid berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus murid: ' . $e->getMessage());
        }
    }
}
