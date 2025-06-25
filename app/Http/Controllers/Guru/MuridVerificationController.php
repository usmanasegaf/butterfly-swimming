<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MuridVerificationController extends Controller
{
    // Tampilkan daftar murid yang statusnya pending
    public function index()
    {
        $murids = User::where('role', 'murid')->where('status', 'pending')->get();
        return view('guru.verification.murid_pending', compact('murids'));
    }

    // Verifikasi murid (set status active)
    public function verify(User $user)
    {
        if ($user->role === 'murid' && $user->status === 'pending') {
            $user->status = 'active';
            $user->save();
        }
        return redirect()->route('guru.murid.pending')->with('success', 'Murid berhasil diverifikasi.');
    }

    // Tolak murid (set status rejected)
    public function reject(User $user)
    {
        if ($user->role === 'murid' && $user->status === 'pending') {
            $user->status = 'rejected';
            $user->save();
        }
        return redirect()->route('guru.murid.pending')->with('success', 'Murid berhasil ditolak.');
    }
}