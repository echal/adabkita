<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login
     * Route: GET /login
     */
    public function showLoginForm()
    {
        // Menampilkan view halaman login
        return view('komponen_umum.halaman_login');
    }

    /**
     * Memproses login pengguna
     * Route: POST /login
     *
     * Alur kerja:
     * 1. Validasi input username dan password
     * 2. Coba login menggunakan username dan password
     * 3. Jika berhasil, redirect ke dashboard sesuai role
     * 4. Jika gagal, kembali ke halaman login dengan pesan error
     */
    public function login(Request $request)
    {
        // Validasi input dari form login
        $credentials = $request->validate([
            'username' => 'required|string', // Username wajib diisi
            'password' => 'required|string', // Password wajib diisi
        ]);

        // Coba login dengan username dan password
        // attempt() akan otomatis mengecek password yang sudah di-hash
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Ambil data user yang sedang login
            $user = Auth::user();

            // Redirect ke dashboard sesuai dengan role pengguna
            // Admin → /admin/dashboard
            // Guru → /guru/dashboard
            // Siswa → /siswa/dashboard
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'guru') {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->role === 'siswa') {
                return redirect()->intended('/siswa/dashboard');
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Logout pengguna
     * Route: POST /logout
     *
     * Alur kerja:
     * 1. Logout user dari sistem
     * 2. Hapus session
     * 3. Redirect ke frontpage (halaman landing)
     */
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Hapus session untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke frontpage (halaman landing)
        return redirect('/');
    }
}
