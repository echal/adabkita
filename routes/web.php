<?php

/**
 * =====================================================
 * FILE ROUTING APLIKASI (web.php)
 * =====================================================
 * File ini berisi semua route/alamat URL aplikasi
 * Struktur route dikelompokkan berdasarkan role:
 * - Route Umum (login, logout)
 * - Route Admin (kelola pengguna, materi, dll)
 * - Route Guru (kelola materi, tugas, nilai)
 * - Route Siswa (lihat materi, kerjakan tugas)
 * =====================================================
 */

use Illuminate\Support\Facades\Route;

// Import Controllers yang akan digunakan
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\SertifikatController; // [NEW FEATURE] Sertifikat Controller
use App\Http\Controllers\Siswa\LessonInteraktifController; // [NEW FEATURE] Lesson Interaktif Controller
use App\Http\Controllers\Siswa\KategoriController; // [NEW FEATURE] Kategori Pembelajaran Controller
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\LessonFlowController;
use App\Http\Controllers\LessonItemController;
use App\Http\Controllers\LessonAnalyticsController;

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

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (Login & Logout)
|--------------------------------------------------------------------------
| Route untuk proses masuk dan keluar sistem
|
| Cara kerja:
| 1. User akses /login → Tampil form login
| 2. User submit form → POST ke /login → Cek kredensial
| 3. Jika benar → Redirect ke dashboard sesuai role
| 4. Jika salah → Kembali ke login dengan pesan error
*/

// Menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest'); // Hanya bisa diakses jika belum login

// Memproses login (submit form)
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

// Proses logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth'); // Harus sudah login untuk logout

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK ADMIN
|--------------------------------------------------------------------------
| Grup route khusus untuk Administrator
|
| Middleware yang digunakan:
| - auth: User harus sudah login
| - role:admin: User harus memiliki role 'admin'
|
| Struktur URL: /admin/*
| Contoh: /admin/dashboard, /admin/pengguna, dll
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD Pengguna (Create, Read, Update, Delete)
        // Route resource otomatis membuat route untuk:
        // - GET /admin/pengguna → index (daftar pengguna)
        // - GET /admin/pengguna/create → create (form tambah)
        // - POST /admin/pengguna → store (simpan data)
        // - GET /admin/pengguna/{id} → show (detail pengguna)
        // - GET /admin/pengguna/{id}/edit → edit (form edit)
        // - PUT/PATCH /admin/pengguna/{id} → update (update data)
        // - DELETE /admin/pengguna/{id} → destroy (hapus data)
        Route::resource('pengguna', PenggunaController::class);

        // Route admin lainnya bisa ditambahkan di sini
        // Contoh:
        // Route::resource('nilai', NilaiController::class);
    });

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK MATERI PEMBELAJARAN
|--------------------------------------------------------------------------
| Route khusus untuk mengelola materi pembelajaran (Adab Islami, dll)
|
| Akses berdasarkan role:
| - Admin & Guru: Bisa Create, Read, Update, Delete (CRUD penuh)
| - Siswa: Hanya bisa Read (index dan show)
|
| Struktur URL: /materi/*
| Contoh: /materi, /materi/create, /materi/1, dll
*/

Route::middleware(['auth'])->group(function () {
    // Route untuk melihat daftar materi (semua role)
    Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');

    // Route untuk Create, Update, Delete (hanya Admin & Guru)
    // PENTING: Route create harus sebelum route show agar 'create' tidak dianggap sebagai {materi}
    Route::middleware('role:admin,guru')->group(function () {
        Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
        Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
        Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
        Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
        Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
    });

    // Route untuk melihat detail materi (semua role)
    // Route ini di bawah agar tidak conflict dengan /materi/create
    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('materi.show');
});

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK LESSON FLOW INTERAKTIF
|--------------------------------------------------------------------------
| Route khusus untuk mengelola Lesson Flow Interaktif
|
| Akses berdasarkan role:
| - Admin & Guru: Bisa mengelola lesson flow (CRUD penuh)
| - Siswa: Bisa mengakses lesson flow yang sudah dipublikasi
|
| Struktur URL: /lesson-flow/*
| Contoh: /lesson-flow, /lesson-flow/create, /lesson-flow/1/edit, dll
*/

Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    // Daftar lesson flow
    Route::get('/lesson-flow', [LessonFlowController::class, 'index'])->name('lesson-flow.index');

    // Buat lesson flow baru
    Route::get('/lesson-flow/create', [LessonFlowController::class, 'create'])->name('lesson-flow.create');
    Route::post('/lesson-flow', [LessonFlowController::class, 'store'])->name('lesson-flow.store');

    // Edit lesson flow (kelola urutan item)
    Route::get('/lesson-flow/{id}/edit', [LessonFlowController::class, 'edit'])->name('lesson-flow.edit');
    Route::put('/lesson-flow/{id}', [LessonFlowController::class, 'update'])->name('lesson-flow.update');

    // Lihat detail lesson flow (preview)
    Route::get('/lesson-flow/{id}', [LessonFlowController::class, 'show'])->name('lesson-flow.show');

    // Hapus lesson flow
    Route::delete('/lesson-flow/{id}', [LessonFlowController::class, 'destroy'])->name('lesson-flow.destroy');

    // Update urutan item (AJAX)
    Route::post('/lesson-flow/{id}/update-order', [LessonFlowController::class, 'updateOrder'])->name('lesson-flow.update-order');

    // Publikasikan dan arsipkan lesson flow
    Route::post('/lesson-flow/{id}/publish', [LessonFlowController::class, 'publish'])->name('lesson-flow.publish');
    Route::post('/lesson-flow/{id}/archive', [LessonFlowController::class, 'archive'])->name('lesson-flow.archive');

    // CRUD Lesson Item (item dalam lesson flow)
    // Tambah item baru ke lesson flow
    Route::post('/lesson-flow/{lessonFlowId}/items', [LessonItemController::class, 'store'])->name('lesson-items.store');

    // Edit item (tampilkan form edit)
    Route::get('/lesson-items/{id}/edit', [LessonItemController::class, 'edit'])->name('lesson-items.edit');

    // Update item
    Route::put('/lesson-items/{id}', [LessonItemController::class, 'update'])->name('lesson-items.update');

    // Hapus item
    Route::delete('/lesson-items/{id}', [LessonItemController::class, 'destroy'])->name('lesson-items.destroy');

    // Rekap Nilai Siswa
    // Halaman rekap nilai siswa untuk semua lesson
    Route::get('/rekap-nilai-siswa', [LessonFlowController::class, 'rekapNilaiSiswa'])->name('rekap-nilai-siswa.index');

    // Halaman detail jawaban siswa per lesson
    Route::get('/rekap-nilai-siswa/{idSiswa}/{idLessonFlow}', [LessonFlowController::class, 'detailJawabanSiswa'])->name('rekap-nilai-siswa.detail');
});

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK LESSON FLOW - SISWA
|--------------------------------------------------------------------------
| Route untuk siswa mengakses dan mengikuti lesson flow interaktif
|
| Akses: Hanya siswa yang sudah login
| Fitur: Lihat daftar, ikuti lesson flow, jawab soal
*/

Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Daftar lesson flow yang tersedia untuk siswa (yang sudah published)
    Route::get('/siswa/lesson-interaktif', [LessonFlowController::class, 'indexSiswa'])->name('siswa.lesson-interaktif.index');

    // Halaman mengikuti lesson flow interaktif
    Route::get('/siswa/lesson-interaktif/{id}/mulai', [LessonFlowController::class, 'mulaiLesson'])->name('siswa.lesson-interaktif.mulai');

    // API untuk simpan jawaban siswa (AJAX)
    Route::post('/siswa/lesson-interaktif/simpan-jawaban', [LessonFlowController::class, 'simpanJawaban'])->name('siswa.lesson-interaktif.simpan-jawaban');

    // Halaman hasil/skor setelah selesai
    Route::get('/siswa/lesson-interaktif/{id}/hasil', [LessonFlowController::class, 'hasilLesson'])->name('siswa.lesson-interaktif.hasil');
});

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK GURU
|--------------------------------------------------------------------------
| Grup route khusus untuk Guru/Pengajar
|
| Middleware yang digunakan:
| - auth: User harus sudah login
| - role:guru: User harus memiliki role 'guru'
|
| Struktur URL: /guru/*
| Contoh: /guru/dashboard, /guru/materi, dll
*/

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        // Dashboard Guru
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])
            ->name('dashboard');

        // =====================================================
        // PHASE 5: ANALYTICS & REKAP NILAI GURU
        // =====================================================
        // Halaman analytics untuk melihat performa siswa
        // dalam lesson flow interaktif
        //
        // Route untuk halaman utama analytics (semua lesson)
        Route::get('/lesson-analytics', [LessonAnalyticsController::class, 'index'])
            ->name('lesson-analytics.index');

        // Route untuk detail analytics per lesson
        Route::get('/lesson-analytics/{id}', [LessonAnalyticsController::class, 'detail'])
            ->name('lesson-analytics.detail');

        // Route guru lainnya bisa ditambahkan di sini
        // Contoh:
        // Route::resource('materi', GuruMateriController::class);
        // Route::resource('tugas', GuruTugasController::class);
        // Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('siswa');
    });

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK SISWA
|--------------------------------------------------------------------------
| Grup route khusus untuk Siswa/Pelajar
|
| Middleware yang digunakan:
| - auth: User harus sudah login
| - role:siswa: User harus memiliki role 'siswa'
|
| Struktur URL: /siswa/*
| Contoh: /siswa/dashboard, /siswa/materi, dll
*/

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'role:siswa'])
    ->group(function () {

        // Dashboard Siswa (Tailwind v3 - IEEE Inspired)
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');

        // =====================================================
        // [NEW FEATURE] SEARCH PELAJARAN (AJAX)
        // =====================================================
        // Endpoint untuk search pelajaran secara real-time
        Route::get('/search-pelajaran', [SiswaDashboardController::class, 'searchPelajaran'])
            ->name('search-pelajaran');

        // =====================================================
        // [NEW FEATURE] SERTIFIKAT PDF
        // =====================================================

        // Daftar semua sertifikat
        Route::get('/sertifikat', [SertifikatController::class, 'index'])
            ->name('sertifikat.index');

        // Download sertifikat PDF
        Route::get('/sertifikat/download/{idProgress}', [SertifikatController::class, 'download'])
            ->name('sertifikat.download');

        // Preview sertifikat (tanpa download)
        Route::get('/sertifikat/preview/{idProgress}', [SertifikatController::class, 'preview'])
            ->name('sertifikat.preview');

        // =====================================================
        // [NEW FEATURE] Integrasi Materi Interaktif
        // Route untuk halaman daftar dan pembelajaran interaktif
        // =====================================================

        // Daftar semua materi interaktif (grid card)
        Route::get('/lesson-interaktif', [LessonInteraktifController::class, 'index'])
            ->name('lesson-interaktif.index');

        // Detail materi interaktif (fullscreen dengan navigasi)
        Route::get('/lesson-interaktif/{id}', [LessonInteraktifController::class, 'show'])
            ->name('lesson-interaktif.show');

        // Submit jawaban kuis materi interaktif
        Route::post('/lesson-interaktif/{id}/submit', [LessonInteraktifController::class, 'submitJawaban'])
            ->name('lesson-interaktif.submit');

        // Simpan progress materi interaktif (AJAX)
        Route::post('/lesson-interaktif/{id}/save-progress', [LessonInteraktifController::class, 'saveProgress'])
            ->name('lesson-interaktif.save-progress');

        // =====================================================
        // [NEW FEATURE] Kategori Pembelajaran (3-Level Navigation)
        // Route untuk navigasi Kategori → Kelas → Materi
        // =====================================================

        // Level 1: Halaman Kategori Pembelajaran (Grid Cards)
        Route::get('/kategori-pembelajaran', [KategoriController::class, 'index'])
            ->name('kategori.index');

        // Level 2: Halaman Kelas - Daftar Materi per Kategori
        Route::get('/kategori-pembelajaran/{slug}', [KategoriController::class, 'show'])
            ->name('kategori.show');

        // Level 3: Halaman Materi Interaktif (Fullscreen Viewer)
        Route::get('/materi/{id}/view', [KategoriController::class, 'viewMateri'])
            ->name('materi.view');

        // Tandai Materi Selesai
        Route::post('/materi/{id}/complete', [KategoriController::class, 'completeMateri'])
            ->name('materi.complete');

        // Route siswa lainnya bisa ditambahkan di sini
        // Contoh:
        // Route::get('/tugas', [SiswaTugasController::class, 'index'])->name('tugas');
        // Route::get('/nilai', [SiswaNilaiController::class, 'index'])->name('nilai');
    });

/*
|--------------------------------------------------------------------------
| PENJELASAN ROUTE RESOURCE
|--------------------------------------------------------------------------
| Route::resource() adalah shortcut untuk membuat 7 route standar CRUD:
|
| Verb      | URI                    | Action  | Route Name
| ----------|------------------------|---------|------------------
| GET       | /pengguna              | index   | pengguna.index
| GET       | /pengguna/create       | create  | pengguna.create
| POST      | /pengguna              | store   | pengguna.store
| GET       | /pengguna/{id}         | show    | pengguna.show
| GET       | /pengguna/{id}/edit    | edit    | pengguna.edit
| PUT/PATCH | /pengguna/{id}         | update  | pengguna.update
| DELETE    | /pengguna/{id}         | destroy | pengguna.destroy
|
| Gunakan 'php artisan route:list' untuk melihat semua route
*/
