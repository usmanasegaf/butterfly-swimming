<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruVerificationController extends Controller
{
    public function index()
    {
        $gurus = User::where('role', 'guru')->where('status', 'pending')->get();
        return view('admin.verification.guru_pending', compact('gurus'));
    }

    public function verify(User $user)
    {
        if ($user->role === 'guru' && $user->status === 'pending') {
            $user->status = 'active';
            $user->save();
        }
        return redirect()->route('admin.guru.pending')->with('success', 'Guru berhasil diverifikasi.');
    }

    // Metode reject untuk MENOLAK dan memblokir email (dengan mengubah status menjadi 'rejected')
    public function reject(User $user)
    {
        if ($user->role === 'guru' && $user->status === 'pending') {
            $user->status = 'rejected'; // Tandai sebagai ditolak/diblokir
            $user->save();
        }
        return redirect()->route('admin.guru.pending')->with('success', 'Guru berhasil ditolak dan email diblokir.');
    }

    // Metode baru untuk MENGHAPUS data pengguna sepenuhnya (memungkinkan pendaftaran ulang)
    public function delete(User $user)
    {
        if ($user->role === 'guru' && $user->status === 'pending') {
            DB::beginTransaction();
            try {
                // Hapus semua relasi terkait guru ini jika ada
                // Contoh: Jika guru ini pernah membuat jadwal, jadwal tersebut mungkin perlu dihapus atau ditugaskan ulang.
                // Output.txt menunjukkan ada relasi jadwalGuru() dan murids()
                $user->jadwalGuru()->delete(); // Hapus jadwal yang dibuat guru ini
                $user->murids()->detach(); // Lepaskan semua murid bimbingan dari guru ini

                $user->delete(); // Hapus user dari tabel users
                DB::commit();
                return redirect()->route('admin.guru.pending')->with('success', 'Data guru berhasil dihapus.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menghapus data guru: ' . $e->getMessage());
            }
        }
        return redirect()->route('admin.guru.pending')->with('error', 'Guru tidak ditemukan atau tidak dalam status pending.');
    }
}
