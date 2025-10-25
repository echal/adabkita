# >í DOKUMENTASI SISTEM NAVIGASI & ROUTING

## =Ë Daftar Isi
1. [Ringkasan Perbaikan](#ringkasan-perbaikan)
2. [Struktur Navigasi](#struktur-navigasi)
3. [Cara Kerja Menu Aktif](#cara-kerja-menu-aktif)
4. [Daftar Route Lengkap](#daftar-route-lengkap)
5. [Penjelasan Kode](#penjelasan-kode)
6. [Testing & Verifikasi](#testing--verifikasi)
7. [Troubleshooting](#troubleshooting)

---

##  Ringkasan Perbaikan

### Masalah yang Diperbaiki:
1. L **Link menu menggunakan `#`** ’  **Semua link menggunakan `{{ route('...') }}`**
2. L **URL tidak berpindah saat klik menu** ’  **URL berpindah sesuai route**
3. L **Menu aktif tidak ter-highlight** ’  **Menu aktif otomatis ter-highlight**
4. L **Menu harus didefinisikan di setiap halaman** ’  **Menu otomatis berdasarkan role**

### File yang Diperbaiki:
| No | File | Status | Deskripsi |
|----|------|--------|-----------|
| 1  | `resources/views/layouts/template_dashboard.blade.php` |  Diperbaiki | Menu navigasi otomatis per role |
| 2  | `routes/web.php` |  Sudah Baik | Semua route sudah diberi nama |
| 3  | `resources/views/admin/dashboard.blade.php` |   Opsional | Bisa dihapus navigasi manual |
| 4  | `resources/views/guru/dashboard.blade.php` |   Opsional | Bisa dihapus navigasi manual |

---

## =ú Struktur Navigasi

### Menu Berdasarkan Role

#### 1ã MENU ADMIN
| No | Menu | Route Name | URL | Fungsi |
|----|------|------------|-----|--------|
| 1  | Dashboard | `admin.dashboard` | `/admin/dashboard` | Halaman utama admin |
| 2  | Kelola Pengguna | `admin.pengguna.index` | `/admin/pengguna` | Daftar & kelola user |
| 3  | Kelola Materi | `materi.index` | `/materi` | Daftar & kelola materi |
| 4  | Pengaturan | `admin.dashboard` | `/admin/dashboard` | Pengaturan sistem (sementara) |

**Kode Menu Admin:**
```blade
@if(Auth::user()->role === 'admin')
    {{-- Menu Dashboard --}}
    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    {{-- Menu Kelola Pengguna --}}
    <a class="nav-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}"
       href="{{ route('admin.pengguna.index') }}">
        <i class="bi bi-people-fill"></i> Kelola Pengguna
    </a>

    {{-- Menu Kelola Materi --}}
    <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
       href="{{ route('materi.index') }}">
        <i class="bi bi-book-fill"></i> Kelola Materi
    </a>

    {{-- Menu Pengaturan --}}
    <a class="nav-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <i class="bi bi-gear-fill"></i> Pengaturan
    </a>
@endif
```

#### 2ã MENU GURU
| No | Menu | Route Name | URL | Fungsi |
|----|------|------------|-----|--------|
| 1  | Dashboard | `guru.dashboard` | `/guru/dashboard` | Halaman utama guru |
| 2  | Kelola Materi | `materi.index` | `/materi` | Daftar & kelola materi |
| 3  | Tugas & Quiz | `guru.dashboard` | `/guru/dashboard` | Kelola tugas (sementara) |
| 4  | Daftar Siswa | `guru.dashboard` | `/guru/dashboard` | Lihat siswa (sementara) |
| 5  | Pengaturan | `guru.dashboard` | `/guru/dashboard` | Pengaturan (sementara) |

**Kode Menu Guru:**
```blade
@elseif(Auth::user()->role === 'guru')
    {{-- Menu Dashboard --}}
    <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
       href="{{ route('guru.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    {{-- Menu Kelola Materi --}}
    <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
       href="{{ route('materi.index') }}">
        <i class="bi bi-book-fill"></i> Kelola Materi
    </a>

    {{-- Menu lainnya... --}}
@endif
```

#### 3ã MENU SISWA
| No | Menu | Route Name | URL | Fungsi |
|----|------|------------|-----|--------|
| 1  | Dashboard | `siswa.dashboard` | `/siswa/dashboard` | Halaman utama siswa |
| 2  | Materi Pembelajaran | `materi.index` | `/materi` | Lihat materi |
| 3  | Tugas Saya | `siswa.dashboard` | `/siswa/dashboard` | Tugas siswa (sementara) |
| 4  | Nilai Saya | `siswa.dashboard` | `/siswa/dashboard` | Nilai siswa (sementara) |
| 5  | Profil | `siswa.dashboard` | `/siswa/dashboard` | Profil siswa (sementara) |

---

## <¯ Cara Kerja Menu Aktif

### Logika Highlight Menu Aktif

Menu yang sedang dibuka akan otomatis ter-highlight dengan background berbeda. Ini menggunakan kondisi Blade:

```blade
<a class="nav-link {{ request()->routeIs('nama.route') ? 'active' : '' }}"
   href="{{ route('nama.route') }}">
    <i class="bi bi-icon"></i> Nama Menu
</a>
```

**Penjelasan:**
1. `request()->routeIs('nama.route')` ’ Cek apakah route saat ini sesuai
2. Jika **true** ’ tambahkan class `active`
3. Jika **false** ’ tidak ada class tambahan
4. Class `active` memiliki style:
   ```css
   .sidebar .nav-link.active {
       background: rgba(255,255,255,0.2);
       color: white;
   }
   ```

### Wildcard untuk Sub-Menu

Jika menu memiliki sub-halaman (create, edit, show), gunakan wildcard `*`:

```blade
{{-- Akan aktif untuk semua route yang dimulai dengan 'materi.' --}}
<a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
   href="{{ route('materi.index') }}">
    <i class="bi bi-book-fill"></i> Kelola Materi
</a>
```

**Contoh:**
- `/materi` ’  Aktif (route: `materi.index`)
- `/materi/create` ’  Aktif (route: `materi.create`)
- `/materi/5` ’  Aktif (route: `materi.show`)
- `/materi/5/edit` ’  Aktif (route: `materi.edit`)

---

## =Í Daftar Route Lengkap

### Route Umum (Tanpa Login)
| Method | URL | Route Name | Fungsi |
|--------|-----|------------|--------|
| GET | `/` | `home` | Redirect ke dashboard sesuai role |
| GET | `/login` | `login` | Form login |
| POST | `/login` | - | Proses login |
| POST | `/logout` | `logout` | Proses logout |

### Route Admin (Middleware: auth, role:admin)
| Method | URL | Route Name | Fungsi |
|--------|-----|------------|--------|
| GET | `/admin/dashboard` | `admin.dashboard` | Dashboard admin |
| GET | `/admin/pengguna` | `admin.pengguna.index` | Daftar pengguna |
| GET | `/admin/pengguna/create` | `admin.pengguna.create` | Form tambah pengguna |
| POST | `/admin/pengguna` | `admin.pengguna.store` | Simpan pengguna baru |
| GET | `/admin/pengguna/{id}` | `admin.pengguna.show` | Detail pengguna |
| GET | `/admin/pengguna/{id}/edit` | `admin.pengguna.edit` | Form edit pengguna |
| PUT | `/admin/pengguna/{id}` | `admin.pengguna.update` | Update pengguna |
| DELETE | `/admin/pengguna/{id}` | `admin.pengguna.destroy` | Hapus pengguna |

### Route Guru (Middleware: auth, role:guru)
| Method | URL | Route Name | Fungsi |
|--------|-----|------------|--------|
| GET | `/guru/dashboard` | `guru.dashboard` | Dashboard guru |

### Route Siswa (Middleware: auth, role:siswa)
| Method | URL | Route Name | Fungsi |
|--------|-----|------------|--------|
| GET | `/siswa/dashboard` | `siswa.dashboard` | Dashboard siswa |

### Route Materi (Middleware: auth, role dinamis)
| Method | URL | Route Name | Akses | Fungsi |
|--------|-----|------------|-------|--------|
| GET | `/materi` | `materi.index` | Semua | Daftar materi |
| GET | `/materi/create` | `materi.create` | Admin & Guru | Form tambah materi |
| POST | `/materi` | `materi.store` | Admin & Guru | Simpan materi |
| GET | `/materi/{id}` | `materi.show` | Semua | Detail materi |
| GET | `/materi/{id}/edit` | `materi.edit` | Admin & Guru | Form edit materi |
| PUT | `/materi/{id}` | `materi.update` | Admin & Guru | Update materi |
| DELETE | `/materi/{id}` | `materi.destroy` | Admin & Guru | Hapus materi |

---

## =» Penjelasan Kode

### 1. Template Dashboard (`template_dashboard.blade.php`)

#### Header Komentar
```blade
{{--
    =====================================================
    TEMPLATE DASHBOARD UTAMA
    =====================================================
    Template ini digunakan untuk semua halaman dashboard
    (Admin, Guru, Siswa).
    ...
--}}
```
**Fungsi:** Dokumentasi di awal file untuk jelaskan tujuan dan cara pakai template.

#### Extends Template Utama
```blade
@extends('layouts.template_utama')

@section('konten')
```
**Fungsi:** Template dashboard ini extend dari template_utama yang berisi HTML dasar, CSS, dan JS.

#### Sidebar dengan Warna Dinamis
```blade
<div class="col-md-2 sidebar sidebar-{{ Auth::user()->role }} p-0">
```
**Fungsi:**
- Class `sidebar-admin` ’ Warna ungu (untuk admin)
- Class `sidebar-guru` ’ Warna hijau (untuk guru)
- Class `sidebar-siswa` ’ Warna pink (untuk siswa)

#### Kondisi Menu Berdasarkan Role
```blade
@if(Auth::user()->role === 'admin')
    {{-- Menu khusus admin --}}
@elseif(Auth::user()->role === 'guru')
    {{-- Menu khusus guru --}}
@else
    {{-- Menu khusus siswa --}}
@endif
```
**Fungsi:** Tampilkan menu yang berbeda sesuai role user yang login.

#### Link dengan Route Name
```blade
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
   href="{{ route('admin.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
```
**Fungsi:**
- `{{ route('admin.dashboard') }}` ’ Generate URL otomatis dari route name
- `request()->routeIs('admin.dashboard')` ’ Cek apakah ini halaman aktif
- `? 'active' : ''` ’ Tambah class `active` jika true

#### Auto-hide Alert
```blade
@push('js_tambahan')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000); // 5 detik
    });
</script>
@endpush
```
**Fungsi:** Semua alert (success, error, warning, info) akan otomatis hilang setelah 5 detik.

---

### 2. Routes (`web.php`)

#### Route Grouping dengan Prefix
```php
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Route di dalam grup
    });
```
**Fungsi:**
- `prefix('admin')` ’ Semua route dimulai dengan `/admin/`
- `name('admin.')` ’ Semua route name dimulai dengan `admin.`
- `middleware(['auth', 'role:admin'])` ’ Hanya admin yang bisa akses
- Contoh: `/admin/dashboard` dengan name `admin.dashboard`

#### Route Resource
```php
Route::resource('pengguna', PenggunaController::class);
```
**Fungsi:** Otomatis membuat 7 route CRUD standar:
- GET `/pengguna` ’ index
- GET `/pengguna/create` ’ create
- POST `/pengguna` ’ store
- GET `/pengguna/{id}` ’ show
- GET `/pengguna/{id}/edit` ’ edit
- PUT `/pengguna/{id}` ’ update
- DELETE `/pengguna/{id}` ’ destroy

#### Route dengan Multiple Middleware
```php
Route::middleware(['auth'])->group(function () {
    // Route untuk semua user login

    Route::middleware('role:admin,guru')->group(function () {
        // Route khusus admin & guru
    });
});
```
**Fungsi:** Nested middleware untuk akses bertingkat.

---

## >ê Testing & Verifikasi

### 1. Cek Daftar Route
```bash
php artisan route:list
```
**Hasil yang diharapkan:**
- Semua route memiliki **name** (kolom Route Name tidak kosong)
- Route admin dimulai dengan `admin.`
- Route guru dimulai dengan `guru.`
- Route siswa dimulai dengan `siswa.`
- Route materi dimulai dengan `materi.`

### 2. Clear Cache
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
**Fungsi:** Hapus semua cache agar perubahan terdeteksi.

### 3. Testing Login & Navigasi

#### Test sebagai ADMIN:
1. Login dengan akun admin
2. Cek URL: `http://localhost:8000/admin/dashboard` 
3. Klik menu **"Kelola Pengguna"**
4. Cek URL berubah menjadi: `http://localhost:8000/admin/pengguna` 
5. Cek menu **"Kelola Pengguna"** ter-highlight 
6. Klik menu **"Kelola Materi"**
7. Cek URL berubah menjadi: `http://localhost:8000/materi` 
8. Cek menu **"Kelola Materi"** ter-highlight 

#### Test sebagai GURU:
1. Login dengan akun guru
2. Cek URL: `http://localhost:8000/guru/dashboard` 
3. Klik menu **"Kelola Materi"**
4. Cek URL berubah menjadi: `http://localhost:8000/materi` 
5. Cek menu **"Kelola Materi"** ter-highlight 

#### Test sebagai SISWA:
1. Login dengan akun siswa
2. Cek URL: `http://localhost:8000/siswa/dashboard` 
3. Klik menu **"Materi Pembelajaran"**
4. Cek URL berubah menjadi: `http://localhost:8000/materi` 
5. Cek tombol **"Tambah Materi"** tidak muncul  (siswa read-only)

---

## =' Troubleshooting

### L Error: "Route [nama.route] not defined"
**Penyebab:** Route name tidak terdaftar di `web.php`

**Solusi:**
1. Buka file `routes/web.php`
2. Cari route yang dimaksud
3. Pastikan ada `->name('nama.route')`
4. Jalankan `php artisan route:clear`

**Contoh:**
```php
// L Salah - tidak ada name
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

//  Benar - ada name
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');
```

### L Menu tidak ter-highlight saat diklik
**Penyebab:** Route name di `template_dashboard.blade.php` tidak cocok dengan route sebenarnya

**Solusi:**
1. Cek nama route di `web.php` menggunakan `php artisan route:list`
2. Sesuaikan di `template_dashboard.blade.php`:
   ```blade
   {{-- Pastikan route name sama persis --}}
   <a class="nav-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}"
      href="{{ route('admin.pengguna.index') }}">
   ```

### L URL tetap menampilkan `#`
**Penyebab:** Masih ada link yang menggunakan `href="#"`

**Solusi:**
1. Search file `dashboard.blade.php` (admin/guru)
2. Ganti semua `href="#"` dengan `href="{{ route('...') }}"`
3. **ATAU** gunakan template_dashboard yang sudah otomatis (tidak perlu define menu lagi)

### L Error: "Trying to get property 'role' of non-object"
**Penyebab:** User belum login atau `Auth::user()` null

**Solusi:**
1. Pastikan halaman menggunakan middleware `auth`
2. Tambahkan pengecekan:
   ```blade
   @if(Auth::check() && Auth::user()->role === 'admin')
       {{-- Menu admin --}}
   @endif
   ```

### L Siswa bisa akses halaman admin
**Penyebab:** Middleware role tidak berfungsi

**Solusi:**
1. Cek file `app/Http/Middleware/RoleMiddleware.php` sudah ada
2. Cek sudah didaftarkan di `bootstrap/app.php` atau `Kernel.php`
3. Pastikan route menggunakan middleware:
   ```php
   Route::middleware(['auth', 'role:admin'])->group(function () {
       // Route khusus admin
   });
   ```

---

## =Ú Referensi

### File Penting
1. **Template Dashboard:** `resources/views/layouts/template_dashboard.blade.php`
2. **Template Utama:** `resources/views/layouts/template_utama.blade.php`
3. **Routes:** `routes/web.php`
4. **Middleware Role:** `app/Http/Middleware/RoleMiddleware.php`

### Command Artisan Berguna
```bash
# Lihat daftar route
php artisan route:list

# Lihat route tertentu
php artisan route:list --name=admin

# Clear cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize untuk production
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

### Bootstrap Icons
Lihat daftar icon lengkap: https://icons.getbootstrap.com/

Contoh penggunaan:
```blade
<i class="bi bi-speedometer2"></i> Dashboard
<i class="bi bi-people-fill"></i> Kelola Pengguna
<i class="bi bi-book-fill"></i> Kelola Materi
<i class="bi bi-gear-fill"></i> Pengaturan
```

---

## ( Kesimpulan

###  Yang Sudah Diperbaiki:
1.  Menu navigasi otomatis berdasarkan role (tidak perlu define di setiap halaman)
2.  Semua link menggunakan `{{ route('...') }}` (tidak ada `#` lagi)
3.  Menu aktif ter-highlight otomatis
4.  URL berpindah dengan benar saat klik menu
5.  Alert notifikasi otomatis hilang setelah 5 detik
6.  Komentar lengkap di semua file dalam Bahasa Indonesia

### <¯ Cara Menggunakan:
1. **Buat halaman baru:** Cukup extends `template_dashboard`
2. **Menu otomatis muncul:** Tidak perlu define menu lagi
3. **Tambah route baru:** Tambahkan di `web.php` dengan name
4. **Menu aktif otomatis:** Gunakan `request()->routeIs('...')`

### =Ì Catatan Penting:
- **Admin:** Bisa akses semua fitur
- **Guru:** Bisa kelola materi, tidak bisa kelola pengguna
- **Siswa:** Hanya bisa lihat materi (read-only)

---

**Dokumentasi dibuat:** 15 Oktober 2025
**Versi:** 1.0.0
**Framework:** Laravel 11 + Bootstrap 5

**Semoga bermanfaat! =€**
