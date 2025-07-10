<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan ini ada di bagian atas file

class GuruListController extends Controller
{
    public function index()
    {
        $gurus = User::role('guru') // Mengambil user dengan role 'guru'
            ->where('status', 'active')
            ->with('murids')          // Meload relasi 'murids' (murid yang dipegang guru)
            ->withCount('jadwalGuru') // Menghitung jumlah jadwal yang dibuat guru menggunakan relasi 'jadwalGuru()'
            ->get();

        return view('admin.guru_list', compact('gurus'));
    }
}
