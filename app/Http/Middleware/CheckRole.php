<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * =====================================================
 * MIDDLEWARE CHECK ROLE
 * =====================================================
 * Middleware ini digunakan untuk membatasi akses
 * berdasarkan role/peran pengguna
 *
 * Mendukung single role dan multiple roles:
 * - Single: middleware('role:admin')
 * - Multiple: middleware('role:admin,guru')
 *
 * @package App\Http\Middleware
 * @author System
 * @created 2025-10-15
 * @updated 2025-10-15
 * =====================================================
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Cara kerja:
     * 1. Cek apakah user sudah login
     * 2. Cek apakah role user ada dalam daftar role yang diizinkan
     * 3. Jika sesuai, lanjutkan request
     * 4. Jika tidak sesuai, redirect atau abort 403
     *
     * Contoh penggunaan di route:
     * - Route::middleware('role:admin') // hanya admin
     * - Route::middleware('role:admin,guru') // admin atau guru
     * - Route::middleware('role:admin,guru,siswa') // semua role
     *
     * @param  \Illuminate\Http\Request  $request  Request yang masuk
     * @param  \Closure  $next  Closure untuk melanjutkan request
     * @param  string  ...$roles  Daftar role yang diizinkan (bisa lebih dari satu)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cek apakah role user ada dalam daftar role yang diizinkan
        // in_array() akan mengecek apakah role user ada dalam array $roles
        if (!in_array($user->role, $roles)) {
            // Jika role user tidak sesuai, berikan response sesuai kondisi

            // Jika request adalah AJAX, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke halaman ini.',
                    'required_roles' => $roles,
                    'your_role' => $user->role
                ], 403);
            }

            // Jika bukan AJAX, redirect ke dashboard sesuai role user
            // dengan pesan error yang ramah
            $dashboardUrl = match($user->role) {
                'admin' => '/admin/dashboard',
                'guru' => '/guru/dashboard',
                'siswa' => '/siswa/dashboard',
                default => '/',
            };

            return redirect($dashboardUrl)->with('error',
                'Maaf, halaman ini hanya bisa diakses oleh ' .
                implode(' atau ', array_map('ucfirst', $roles)) .
                '. Anda login sebagai ' . ucfirst($user->role) . '.'
            );
        }

        // Jika role sesuai, lanjutkan request
        return $next($request);
    }
}
