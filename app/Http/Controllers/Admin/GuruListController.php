<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class GuruListController extends Controller
{
    public function index()
    {
        // Ambil semua guru beserta murid bimbingannya (asumsi relasi: guru->murids)
        $gurus = User::where('role', 'guru')->with('murids')->get();

        return view('admin.guru_list', compact('gurus'));
    }
}