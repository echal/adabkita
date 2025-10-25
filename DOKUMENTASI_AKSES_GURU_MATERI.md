#  DOKUMENTASI AKSES GURU - CRUD MATERI PEMBELAJARAN

## =Ê Status Verifikasi: SUDAH BERFUNGSI SEMPURNA

---

## <¯ RINGKASAN VERIFIKASI

###  Status Akses Guru:
Setelah dilakukan verifikasi menyeluruh, **Guru sudah memiliki akses penuh ke semua fitur CRUD Materi Pembelajaran**. Tidak ada yang perlu diperbaiki karena sistem sudah dikonfigurasi dengan benar sejak awal.

| Fitur | Admin | Guru | Siswa | Status |
|-------|-------|------|-------|--------|
| Lihat Daftar Materi |  |  |  |  BERFUNGSI |
| Lihat Detail Materi |  |  |  |  BERFUNGSI |
| Tambah Materi Baru |  |  | L |  BERFUNGSI |
| Edit Materi |  |  | L |  BERFUNGSI |
| Hapus Materi |  |  | L |  BERFUNGSI |
| Upload File Materi |  |  | L |  BERFUNGSI |

---

## =Á FILE YANG TELAH DIVERIFIKASI

### 1. Routes (web.php)  **SUDAH BENAR**
**File:** `routes/web.php` (Baris 124-141)

**Middleware yang Digunakan:**
```php
// Route untuk Create, Update, Delete (hanya Admin & Guru)
Route::middleware('role:admin,guru')->group(function () {
    Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
});
```

**Penjelasan:**
-  Menggunakan `middleware('role:admin,guru')` ’ Admin DAN Guru bisa akses
-  Semua route CRUD sudah diberi nama
-  Route `create` didefinisikan sebelum route `{materi}` untuk menghindari conflict
-  Komentar lengkap dalam Bahasa Indonesia

**Status:**  **TIDAK PERLU PERBAIKAN**

---

### 2. MateriController  **SUDAH BENAR**
**File:** `app/Http/Controllers/MateriController.php`

**Metode yang Bisa Diakses Guru:**

#### a. `create()` - Form Tambah Materi
```php
/**
 * Menampilkan form untuk membuat materi baru
 *
 * Fungsi ini hanya bisa diakses oleh Admin dan Guru
 *
 * @return \Illuminate\View\View
 */
public function create()
{
    // Tampilkan form tambah materi
    return view('materi.create');
}
```
**Status:**  **BERFUNGSI** (Middleware di route menghandle akses)

#### b. `store()` - Simpan Materi Baru
```php
/**
 * Menyimpan materi baru ke database
 *
 * Fungsi ini memproses data dari form tambah materi
 * dan menyimpannya ke database, termasuk upload file jika ada
 */
public function store(Request $request)
{
    // ... validasi dan simpan data ...
    $data['dibuat_oleh'] = Auth::user()->name; // Nama pembuat (Admin/Guru)
    Materi::create($data);

    return redirect()->route('materi.index')
        ->with('success', 'Materi berhasil ditambahkan!');
}
```
**Status:**  **BERFUNGSI** (Guru bisa menyimpan materi dengan namanya sebagai pembuat)

#### c. `edit()` - Form Edit Materi
```php
/**
 * Menampilkan form untuk edit materi
 *
 * Fungsi ini hanya bisa diakses oleh Admin dan Guru
 */
public function edit($id)
{
    $materi = Materi::findOrFail($id);
    return view('materi.edit', compact('materi'));
}
```
**Status:**  **BERFUNGSI**

#### d. `update()` - Update Materi
```php
/**
 * Memperbarui materi yang sudah ada di database
 *
 * Fungsi ini memproses data dari form edit materi
 * dan memperbarui data di database
 */
public function update(Request $request, $id)
{
    // ... validasi dan update data ...
    $materi->update($data);

    return redirect()->route('materi.index')
        ->with('success', 'Materi berhasil diperbarui!');
}
```
**Status:**  **BERFUNGSI** (Guru bisa edit materi apapun)

#### e. `destroy()` - Hapus Materi
```php
/**
 * Menghapus materi dari database
 *
 * Fungsi ini akan menghapus materi beserta file yang terkait
 * Hanya bisa diakses oleh Admin dan Guru
 */
public function destroy($id)
{
    $materi = Materi::findOrFail($id);

    // Hapus file terkait jika ada
    if ($materi->file_materi) {
        Storage::delete('public/materi/' . $materi->file_materi);
    }

    $materi->delete();

    return redirect()->route('materi.index')
        ->with('success', 'Materi berhasil dihapus!');
}
```
**Status:**  **BERFUNGSI** (Guru bisa hapus materi apapun)

**Catatan Penting:**
-  Controller tidak melakukan pengecekan role secara manual
-  Akses dikontrol oleh middleware di level route (lebih clean & maintainable)
-  Semua fungsi diberi komentar lengkap dalam Bahasa Indonesia

**Status:**  **TIDAK PERLU PERBAIKAN**

---

### 3. View Materi Index  **SUDAH BENAR**
**File:** `resources/views/materi/index.blade.php`

#### a. Tombol Tambah Materi (Baris 32-37)
```blade
{{-- Tombol Tambah Materi (hanya untuk Admin & Guru) --}}
@if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
<a href="{{ route('materi.create') }}" class="btn btn-light btn-sm">
    <i class="bi bi-plus-circle me-1"></i>Tambah Materi
</a>
@endif
```
**Status:**  **BERFUNGSI** (Guru bisa melihat dan klik tombol)

#### b. Tombol Edit & Hapus (Baris 122-142)
```blade
{{-- Tombol Edit & Hapus (hanya Admin & Guru) --}}
@if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
<a href="{{ route('materi.edit', $item->id) }}"
   class="btn btn-sm btn-warning"
   title="Edit">
    <i class="bi bi-pencil"></i>
</a>

<form action="{{ route('materi.destroy', $item->id) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="btn btn-sm btn-danger"
            title="Hapus">
        <i class="bi bi-trash"></i>
    </button>
</form>
@endif
```
**Status:**  **BERFUNGSI** (Guru bisa edit dan hapus semua materi)

**Status:**  **TIDAK PERLU PERBAIKAN**

---

### 4. Dashboard Guru  **SUDAH BENAR**
**File:** `resources/views/guru/dashboard.blade.php`

#### a. Menu Sidebar - Kelola Materi (Baris 81-83)
```php
<a class="nav-link" href="{{ route('materi.index') }}">
    <i class="bi bi-book-fill"></i> Kelola Materi
</a>
```
**Status:**  **BERFUNGSI** (Link ke halaman materi)

#### b. Tombol Aksi Cepat - Tambah Materi (Baris 212-216)
```php
<a href="{{ route('materi.create') }}" class="btn btn-outline-primary w-100 py-3">
    <i class="bi bi-plus-circle fs-1 d-block mb-2"></i>
    Tambah Materi Baru
</a>
```
**Status:**  **BERFUNGSI** (Guru bisa langsung tambah materi dari dashboard)

**Status:**  **TIDAK PERLU PERBAIKAN**

---

### 5. Template Dashboard  **SUDAH BENAR**
**File:** `resources/views/layouts/template_dashboard.blade.php`

#### Menu Guru (Baris 74-105)
```blade
@elseif(Auth::user()->role === 'guru')
    {{-- ========== MENU UNTUK GURU ========== --}}

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
@endif
```
**Status:**  **BERFUNGSI** (Menu otomatis muncul untuk guru)

**Status:**  **TIDAK PERLU PERBAIKAN**

---

## >ê TESTING & VERIFIKASI

### Test 1: Login sebagai GURU 

#### Langkah Testing:
1.  Login dengan akun guru
2.  Akses URL: `http://localhost:8000/guru/dashboard`
3.  Klik menu **"Kelola Materi"** ’ Redirect ke `/materi`
4.  Tombol **"Tambah Materi"** muncul di header
5.  Klik **"Tambah Materi"** ’ Redirect ke `/materi/create`
6.  Isi form dan klik **"Simpan Materi"**
7.  Materi berhasil ditambahkan dengan nama pembuat = nama guru
8.  Tombol **"Edit"** dan **"Hapus"** muncul di setiap materi
9.  Klik **"Edit"** ’ Bisa edit materi
10.  Klik **"Hapus"** ’ Bisa hapus materi

**Hasil:**  **SEMUA LULUS**

---

### Test 2: Login sebagai ADMIN 

#### Langkah Testing:
1.  Login dengan akun admin
2.  Akses URL: `http://localhost:8000/admin/dashboard`
3.  Klik menu **"Kelola Materi"** ’ Redirect ke `/materi`
4.  Semua fitur CRUD berfungsi sama seperti guru

**Hasil:**  **SEMUA LULUS**

---

### Test 3: Login sebagai SISWA 

#### Langkah Testing:
1.  Login dengan akun siswa
2.  Akses URL: `http://localhost:8000/siswa/dashboard`
3.  Klik menu **"Materi Pembelajaran"** ’ Redirect ke `/materi`
4.  Tombol **"Tambah Materi"** TIDAK muncul 
5.  Tombol **"Edit"** dan **"Hapus"** TIDAK muncul 
6.  Siswa hanya bisa melihat dan membaca materi 
7. L Coba akses langsung `/materi/create` ’ **403 Forbidden** 
8. L Coba akses langsung `/materi/1/edit` ’ **403 Forbidden** 

**Hasil:**  **SEMUA LULUS** (Siswa tidak bisa akses CRUD)

---

## =Ë DAFTAR AKSES GURU

### URL yang Bisa Diakses Guru:

| Method | URL | Fungsi | Status |
|--------|-----|--------|--------|
| GET | `/materi` | Lihat daftar materi |  BISA |
| GET | `/materi/create` | Form tambah materi |  BISA |
| POST | `/materi` | Simpan materi baru |  BISA |
| GET | `/materi/{id}` | Lihat detail materi |  BISA |
| GET | `/materi/{id}/edit` | Form edit materi |  BISA |
| PUT | `/materi/{id}` | Update materi |  BISA |
| DELETE | `/materi/{id}` | Hapus materi |  BISA |

---

## =¡ PENJELASAN CARA KERJA

### 1. Middleware Role
**File:** `routes/web.php` (Baris 130)

```php
Route::middleware('role:admin,guru')->group(function () {
    // Route CRUD di sini
});
```

**Cara Kerja:**
1. User akses URL (misalnya `/materi/create`)
2. Laravel cek middleware `role:admin,guru`
3. Jika user role = **admin** ’  **BOLEH AKSES**
4. Jika user role = **guru** ’  **BOLEH AKSES**
5. Jika user role = **siswa** ’ L **403 FORBIDDEN**

**Keuntungan:**
-  Akses dikontrol di satu tempat (route)
-  Tidak perlu cek role di setiap controller method
-  Lebih aman dan maintainable
-  Mudah dipahami pegawai IT

---

### 2. Kondisi Blade View
**File:** `materi/index.blade.php` (Baris 33)

```blade
@if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
    <a href="{{ route('materi.create') }}" class="btn btn-light btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Tambah Materi
    </a>
@endif
```

**Cara Kerja:**
1. Cek role user yang sedang login
2. Jika **admin** ATAU **guru** ’ Tampilkan tombol
3. Jika **siswa** ’ Tombol TIDAK ditampilkan

**Keuntungan:**
-  UI otomatis menyesuaikan dengan role
-  Siswa tidak bingung karena tidak lihat tombol yang tidak bisa diakses
-  UX lebih baik

---

### 3. Column `dibuat_oleh`
**File:** `MateriController.php` (Baris 93)

```php
$data['dibuat_oleh'] = Auth::user()->name; // Ambil nama user yang login
```

**Cara Kerja:**
1. Saat simpan materi baru, ambil nama user yang sedang login
2. Simpan nama tersebut ke kolom `dibuat_oleh`
3. Di tabel materi akan muncul nama pembuat (Admin/Guru)

**Contoh:**
- Admin "John Doe" tambah materi ’ `dibuat_oleh` = "John Doe"
- Guru "Jane Smith" tambah materi ’ `dibuat_oleh` = "Jane Smith"

**Keuntungan:**
-  Jelas siapa yang membuat materi
-  Bisa untuk tracking dan accountability
-  Memudahkan audit

---

## = KEAMANAN AKSES

### Lapisan Keamanan:

#### 1. Middleware di Route 
```php
Route::middleware('role:admin,guru')->group(...);
```
**Fungsi:** Blokir akses di level route sebelum masuk controller

#### 2. Kondisi di Blade View 
```blade
@if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
```
**Fungsi:** Sembunyikan UI element yang tidak boleh diakses

#### 3. CSRF Protection 
```blade
@csrf
@method('DELETE')
```
**Fungsi:** Protect form dari CSRF attack

---

## =Ê PERBANDINGAN AKSES

### Tabel Perbandingan Lengkap:

| Fitur | Admin | Guru | Siswa |
|-------|-------|------|-------|
| **Dashboard** |  `/admin/dashboard` |  `/guru/dashboard` |  `/siswa/dashboard` |
| **Lihat Daftar Materi** |  Bisa |  Bisa |  Bisa |
| **Tombol "Tambah Materi"** |  Muncul |  Muncul | L Tidak muncul |
| **Akses Form Tambah** |  Bisa |  Bisa | L 403 Forbidden |
| **Simpan Materi Baru** |  Bisa |  Bisa | L 403 Forbidden |
| **Lihat Detail Materi** |  Bisa |  Bisa |  Bisa |
| **Tombol "Edit"** |  Muncul |  Muncul | L Tidak muncul |
| **Akses Form Edit** |  Bisa |  Bisa | L 403 Forbidden |
| **Update Materi** |  Bisa |  Bisa | L 403 Forbidden |
| **Tombol "Hapus"** |  Muncul |  Muncul | L Tidak muncul |
| **Hapus Materi** |  Bisa |  Bisa | L 403 Forbidden |
| **Upload File** |  Bisa |  Bisa | L Tidak bisa |
| **Download File** |  Bisa |  Bisa |  Bisa |
| **Kelola Pengguna** |  Bisa | L Tidak bisa | L Tidak bisa |

---

##  KESIMPULAN AKHIR

### Status Sistem:  **SUDAH BERFUNGSI SEMPURNA**

**Tidak ada yang perlu diperbaiki** karena:

1.  Route sudah menggunakan `middleware('role:admin,guru')`
2.  Controller tidak melakukan pengecekan role (clean code)
3.  View sudah menggunakan kondisi `@if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')`
4.  Tombol aksi hanya muncul untuk Admin & Guru
5.  Siswa tidak bisa akses CRUD secara langsung (403 Forbidden)
6.  Guru bisa menambah, mengedit, dan menghapus materi
7.  Semua kode diberi komentar lengkap Bahasa Indonesia

### Yang Sudah Bekerja dengan Baik:

 **Guru dapat:**
- Melihat daftar semua materi
- Menambah materi baru dengan nama pembuat = nama guru
- Mengedit materi (semua materi, bukan hanya miliknya)
- Menghapus materi (semua materi, bukan hanya miliknya)
- Upload file pendukung materi

 **Admin tetap memiliki:**
- Akses penuh ke semua fitur
- Bisa kelola pengguna (guru tidak bisa)
- Semua hak sama dengan guru untuk materi

 **Siswa tetap dibatasi:**
- Hanya bisa lihat dan baca materi
- Tidak bisa tambah/edit/hapus
- UI element CRUD tidak muncul
- Akses langsung diblokir dengan 403 Forbidden

---

## =Þ KONTAK & DUKUNGAN

Jika ada pertanyaan atau kendala:
1. Baca dokumentasi lengkap: `DOKUMENTASI_CRUD_MATERI.md`
2. Baca dokumentasi navigasi: `DOKUMENTASI_NAVIGASI.md`
3. Cek troubleshooting di dokumentasi
4. Jalankan command: `php artisan route:list --name=materi`

---

**Dokumentasi dibuat:** 15 Oktober 2025
**Status:**  SUDAH BERFUNGSI SEMPURNA - TIDAK PERLU PERBAIKAN
**Versi:** 1.0.0

**SISTEM AKSES GURU KE CRUD MATERI SUDAH BERFUNGSI DENGAN SEMPURNA! <‰**
