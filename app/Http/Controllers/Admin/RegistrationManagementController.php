<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User; // Digunakan untuk memilih guru jika diperlukan
use App\Models\SwimmingCourse; // Digunakan untuk mengambil biaya kursus
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID admin yang login

class RegistrationManagementController extends Controller
{
    /**
     * Display a listing of the registrations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $registrations = Registration::with(['user', 'swimmingCourse'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);
        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Show the form for editing the specified registration.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\View\View
     */
    public function edit(Registration $registration)
    {
        $swimmingCourses = SwimmingCourse::all();
        // Ambil semua guru aktif untuk opsi penugasan
        $gurus = User::where('role', 'guru')->where('status', 'active')->get();
        return view('admin.registrations.edit', compact('registration', 'swimmingCourses', 'gurus'));
    }

    /**
     * Update the specified registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Registration $registration)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'swimming_course_id' => 'required|exists:swimming_courses,id',
            'guru_id' => 'nullable|exists:users,id', // Guru bisa null jika belum ditugaskan
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|in:pending,approved,rejected,completed',
            // 'payment_status' => 'required|string|in:paid,unpaid', // Tidak digunakan sesuai permintaan
            // 'notes' => 'nullable|string', // Tidak digunakan sesuai permintaan
            // 'admin_notes' => 'nullable|string', // Tidak digunakan sesuai permintaan
        ]);

        // Ambil biaya dari kursus yang dipilih
        $selectedCourse = SwimmingCourse::find($request->swimming_course_id);
        $biaya = $selectedCourse ? $selectedCourse->biaya : 0; // Pastikan kolom 'biaya' ada di model SwimmingCourse

        $registration->update([
            'user_id' => $request->user_id,
            'swimming_course_id' => $request->swimming_course_id,
            'guru_id' => $request->guru_id, // Gunakan guru_id dari form
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            // 'payment_status' => $request->payment_status, // Tidak digunakan
            // 'notes' => $request->notes, // Tidak digunakan
            // 'admin_notes' => $request->admin_notes, // Tidak digunakan
            'biaya' => $biaya, // Simpan biaya kursus
        ]);

        return redirect()->route('admin.registration-management.index')->with('success', 'Pendaftaran berhasil diperbarui.');
    }


    /**
     * Approve the specified registration.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Registration $registration)
    {
        // Pastikan pendaftaran dalam status pending sebelum disetujui
        if ($registration->status === 'pending') {
            // Ambil biaya dari kursus terkait
            $course = $registration->swimmingCourse;
            $biaya = $course ? $course->biaya : 0;

            $registration->status = 'approved';
            $registration->biaya = $biaya; // Simpan biaya saat disetujui
            // Jika guru_id belum ada, tetapkan ID admin yang menyetujui sebagai guru yang bertanggung jawab
            // Atau, jika ada field di form approve untuk memilih guru, gunakan itu.
            // Untuk saat ini, kita asumsikan admin yang menyetujui adalah guru_id yang bertanggung jawab atas pemasukan ini.
            // Anda bisa mengubah ini jika ada mekanisme penugasan guru yang lebih spesifik.
            if (is_null($registration->guru_id)) {
                $registration->guru_id = Auth::id(); // ID admin yang login
            }
            $registration->save();
            return redirect()->route('admin.registration-management.index')->with('success', 'Pendaftaran berhasil disetujui.');
        }

        return redirect()->route('admin.registration-management.index')->with('error', 'Pendaftaran tidak dapat disetujui.');
    }

    /**
     * Reject the specified registration.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Registration $registration)
    {
        if ($registration->status === 'pending') {
            $registration->status = 'rejected';
            $registration->save();
            return redirect()->route('admin.registration-management.index')->with('success', 'Pendaftaran berhasil ditolak.');
        }

        return redirect()->route('admin.registration-management.index')->with('error', 'Pendaftaran tidak dapat ditolak.');
    }

    /**
     * Remove the specified registration from storage.
     *
     * @param  \App\Models\Registration  $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->route('admin.registration-management.index')->with('success', 'Pendaftaran berhasil dihapus.');
    }
}
