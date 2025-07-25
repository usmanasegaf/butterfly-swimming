<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuridVerificationController extends Controller
{
    public function index()
    {
        $murids = User::where('role', 'murid')->where('status', 'pending')->get();
        return view('guru.verification.murid_pending', compact('murids'));
    }

    public function verify(User $user)
    {
        if ($user->role === 'murid' && $user->status === 'pending') {
            $user->status = 'active';
            $user->save();
        }
        return redirect()->route('guru.murid.pending')->with('success', 'Murid berhasil diverifikasi.');
    }

    // Metode reject untuk MENOLAK dan memblokir email (dengan mengubah status menjadi 'rejected')
    public function reject(User $user)
    {
        if ($user->role === 'murid' && $user->status === 'pending') {
            $user->status = 'rejected'; // Tandai sebagai ditolak/diblokir
            $user->save();
        }
        return redirect()->route('guru.murid.pending')->with('success', 'Murid berhasil ditolak dan email diblokir.');
    }

    // Metode baru untuk MENGHAPUS data pengguna sepenuhnya (memungkinkan pendaftaran ulang)
    public function delete(User $user)
    {
        if ($user->role === 'murid' && $user->status === 'pending') {
            DB::beginTransaction();
            try {
                $user->gurus()->detach(); 
                $user->schedules()->delete(); 
                $user->attendances()->delete(); 

                $user->delete(); // Hapus user dari tabel users
                DB::commit();
                return redirect()->route('guru.murid.pending')->with('success', 'Data murid berhasil dihapus.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menghapus data murid: ' . $e->getMessage());
            }
        }
        return redirect()->route('guru.murid.pending')->with('error', 'Murid tidak ditemukan atau tidak dalam status pending.');
    }
}
