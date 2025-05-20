<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration; 
use App\Models\SwimmingCourse; 
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi enum

class RegistrationManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:view registrations');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data pendaftaran dari database
        // Dengan eager loading relasi user dan swimmingCourse untuk menghindari N+1 query problem
        $registrations = Registration::with(['user', 'swimmingCourse'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10); // Menampilkan 10 pendaftaran per halaman

        // Kirim data pendaftaran ke view
        return view('admin.registration-management.index', compact('registrations'));
    }

    /**
     * Display the specified resource.
     * Ini digunakan untuk melihat detail satu pendaftaran.
     */
    public function show(Registration $registration)
    {
        // Pastikan relasi user dan swimmingCourse sudah dimuat
        $registration->load('user', 'swimmingCourse');

        // Menampilkan detail pendaftaran
        return view('admin.registration-management.show', compact('registration'));
    }

    /**
     * Update the specified resource in storage.
     * Metode ini akan digunakan terutama untuk memperbarui status pendaftaran.
     */
    public function update(Request $request, Registration $registration)
    {
        // Validasi permisi approve/reject
        if ($request->status === 'Approved' && !auth()->user()->can('approve registration')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui pendaftaran.');
        }
        
        if ($request->status === 'Rejected' && !auth()->user()->can('reject registration')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak pendaftaran.');
        }
        
        $request->validate([
            // Validasi status. Pastikan status adalah salah satu dari enum yang diizinkan di model Registration
            'status' => ['required', 'string', Rule::in(['Pending', 'Approved', 'Rejected'])],
            'notes' => 'nullable|string|max:1000', // Admin bisa menambahkan catatan
        ]);

        $registration->update([
            'status' => $request->status,
            'admin_notes' => $request->notes, // Simpan catatan admin jika ada kolomnya
        ]);

        return redirect()->route('registration-management.index')->with('success', 'Status pendaftaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        // Check if user has permission to delete
        if (!auth()->user()->can('delete registration')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus pendaftaran.');
        }
        
        $registration->delete();

        return redirect()->route('registration-management.index')->with('success', 'Pendaftaran berhasil dihapus!');
    }
}