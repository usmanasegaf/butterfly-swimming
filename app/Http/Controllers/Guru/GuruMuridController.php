<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $murids = User::where('role', 'murid')->whereDoesntHave('gurus', function($q){
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
}