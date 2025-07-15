<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role'     => 'required|in:guru,murid',
        ]);

        // Cek apakah email sudah ada dengan status 'rejected' (diblokir)
        $existingRejectedUser = User::where('email', $request->email)->where('status', 'rejected')->first();
        if ($existingRejectedUser) {
            return redirect()->back()->withInput()->withErrors(['email' => 'Email ini telah diblokir dan tidak dapat digunakan untuk pendaftaran.']);
        }

        $validator->validate();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'pending',
        ]);

        $user->assignRole($request->role);

        // PERUBAHAN DI SINI: Pesan sukses yang lebih informatif
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda telah disubmit. Mohon tunggu verifikasi oleh guru/admin.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ])->validate();

        $user = User::where('email', $request->email)->first();

        // Cek jika user ada dan statusnya 'rejected' (diblokir)
        if ($user && $user->status === 'rejected') {
            return redirect()->back()->withErrors(['email' => 'Akun Anda telah diblokir. Silakan hubungi administrator.']);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return redirect()->back()->withErrors(['email' => 'Email atau password salah']);
        }

        $user = Auth::user();
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('belum.verifikasi');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function profile()
    {
        return view('profile', [
            'user' => Auth::user(),
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed',
        ])->validate();

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
