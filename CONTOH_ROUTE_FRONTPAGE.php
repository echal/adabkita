<?php

/**
 * =====================================================
 * CONTOH ROUTE UNTUK FRONTPAGE
 * =====================================================
 * File ini berisi contoh kode route yang sudah
 * ditambahkan ke routes/web.php
 *
 * TIDAK PERLU DIJALANKAN - Hanya untuk referensi
 * =====================================================
 */

/*
|--------------------------------------------------------------------------
| ROUTE HALAMAN UTAMA / FRONTPAGE
|--------------------------------------------------------------------------
| Route untuk halaman pertama yang dibuka pengguna
|
| Alur:
| 1. Pengunjung membuka URL utama (https://adabkita.gaspul.com)
| 2. Jika sudah login → Redirect ke dashboard sesuai role
| 3. Jika belum login → Tampilkan halaman frontpage/landing page
*/

Route::get('/', function () {
    // Jika user sudah login, redirect langsung ke dashboard sesuai role
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect("/{$role}/dashboard");
    }

    // Jika belum login, tampilkan halaman frontpage
    return view('frontpage');
})->name('home');

/**
 * PENJELASAN KODE:
 * ================
 *
 * 1. Route::get('/', ...)
 *    - Mendefinisikan route untuk URL utama (/)
 *    - Method GET (untuk menampilkan halaman)
 *
 * 2. auth()->check()
 *    - Mengecek apakah user sudah login atau belum
 *    - Return true jika sudah login
 *    - Return false jika belum login
 *
 * 3. auth()->user()->role
 *    - Mengambil role user yang sedang login
 *    - Bisa bernilai: 'admin', 'guru', atau 'siswa'
 *
 * 4. return redirect("/{$role}/dashboard")
 *    - Redirect ke dashboard sesuai role
 *    - Contoh: /admin/dashboard, /guru/dashboard, /siswa/dashboard
 *
 * 5. return view('frontpage')
 *    - Menampilkan view frontpage.blade.php
 *    - Laravel akan mencari file di: resources/views/frontpage.blade.php
 *
 * 6. ->name('home')
 *    - Memberikan nama route "home"
 *    - Bisa dipanggil dengan: route('home')
 */

/**
 * ALTERNATIF ROUTE (Menggunakan Controller)
 * ==========================================
 * Jika ingin menggunakan controller, bisa seperti ini:
 */

// Di routes/web.php
Route::get('/', [FrontpageController::class, 'index'])->name('home');

// Kemudian buat controller baru:
// php artisan make:controller FrontpageController

// Di app/Http/Controllers/FrontpageController.php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontpageController extends Controller
{
    public function index()
    {
        // Jika user sudah login
        if (auth()->check()) {
            $role = auth()->user()->role;
            return redirect("/{$role}/dashboard");
        }

        // Jika belum login, tampilkan frontpage
        return view('frontpage');
    }
}
*/

/**
 * ROUTE LAINNYA YANG BERHUBUNGAN DENGAN FRONTPAGE
 * ================================================
 */

// Route untuk halaman "Tentang Kami" (opsional)
Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

// Route untuk halaman "Kontak" (opsional)
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// Route untuk halaman "FAQ" (opsional)
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

/**
 * TESTING ROUTE
 * =============
 * Untuk melihat semua route yang terdaftar, jalankan:
 *
 * php artisan route:list
 *
 * Output akan menampilkan:
 * - Method (GET, POST, dll)
 * - URI (/, /login, dll)
 * - Name (home, login, dll)
 * - Action (Controller@method)
 */

/**
 * TROUBLESHOOTING
 * ===============
 *
 * 1. Route tidak berfungsi
 *    Solusi: php artisan route:clear
 *
 * 2. View not found
 *    Pastikan file frontpage.blade.php ada di:
 *    resources/views/frontpage.blade.php
 *
 * 3. Redirect loop
 *    Periksa kondisi auth()->check()
 *    Pastikan tidak ada konflik dengan middleware
 *
 * 4. 404 Not Found
 *    - Clear route cache: php artisan route:clear
 *    - Restart server: php artisan serve
 */
