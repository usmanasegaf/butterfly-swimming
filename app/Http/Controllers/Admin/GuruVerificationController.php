<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GuruVerificationController extends Controller
{
    // Tampilkan daftar guru yang statusnya pending
    public function index()
    {
        $gurus = User::where('role', 'guru')->where('status', 'pending')->get();
        return view('admin.verification.guru_pending', compact('gurus'));
    }

    // Verifikasi guru (set status active)
    public function verify(User $user)
    {
        if ($user->role === 'guru' && $user->status === 'pending') {
            $user->status = 'active';
            $user->save();
        }
        return redirect()->route('admin.guru.pending')->with('success', 'Guru berhasil diverifikasi.');
    }

    // Tolak guru (set status rejected)
    public function reject(User $user)
    {
        if ($user->role === 'guru' && $user->status === 'pending') {
            $user->status = 'rejected';
            $user->save();
        }
        return redirect()->route('admin.guru.pending')->with('success', 'Guru berhasil ditolak.');
    }
}