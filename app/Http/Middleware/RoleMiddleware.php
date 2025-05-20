<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (! $request->user()) {
            return redirect('/login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! $request->user()->hasAnyRole($roles)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}