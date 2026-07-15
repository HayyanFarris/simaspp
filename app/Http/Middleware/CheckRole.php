<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Memeriksa apakah user memiliki role yang diizinkan untuk mengakses route.
     * Jika tidak, return 403 Forbidden.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user termasuk dalam daftar role yang diizinkan
        $user = Auth::user();
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
