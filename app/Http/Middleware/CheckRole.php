<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  ...$roles (Ini akan menangkap semua parameter role, cth: 'admin', 'kasir')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil role user yang sedang login
        $userRole = $request->user()->role;

        // 3. Cek apakah role user ada di dalam array $roles yang diizinkan
        if (in_array($userRole, $roles)) {
            // Jika diizinkan, lanjutkan request
            return $next($request);
        }

        // 4. Jika tidak diizinkan, kembalikan ke dashboard dengan pesan error
        // (Anda bisa juga ganti ke halaman 403 (Forbidden) dengan abort(403);)
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}