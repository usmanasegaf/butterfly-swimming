<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if (!Auth::check()) {
            // Jika user belum login, arahkan ke halaman login atau tampilkan error
            return redirect('/login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        // Pastikan request->user() tidak null sebelum mengakses properti 'role'
        // Ini akan sangat jarang terjadi jika middleware 'auth' sudah berjalan sebelumnya,
        // tapi ini adalah pemeriksaan keamanan tambahan.
        if ($request->user() && $request->user()->role !== $role) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}