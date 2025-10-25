# 📝 DOKUMENTASI CRUD PENGGUNA

> **Sistem Kelola Pengguna (Admin, Guru, Siswa)**
> Dibuat: 15 Oktober 2025
> Pembuat: Fasisal Kasim

---

## 📋 Daftar File yang Dibuat

### 1. **Database & Migration**

| File | Lokasi | Deskripsi |
|------|--------|-----------|
| `tambah_kolom_ke_tabel_users.php` | `database/migrations/` | Migration untuk menambah kolom NIP/NIS, Kelas, Foto, No Telepon |

### 2. **Controller**

| File | Lokasi | Deskripsi |
|------|--------|-----------|
| `PenggunaController.php` | `app/Http/Controllers/` | Controller untuk CRUD Pengguna dengan fitur lengkap |

### 3. **Views (Blade Templates)**

| File | Lokasi | Deskripsi |
|------|--------|-----------|
| `daftar_pengguna.blade.php` | `resources/views/halaman_admin/` | Halaman daftar pengguna dengan filter & pagination |
| `form_pengguna.blade.php` | `resources/views/halaman_admin/` | Form tambah & edit pengguna (1 file untuk 2 fungsi) |
| `detail_pengguna.blade.php` | `resources/views/halaman_admin/` | Halaman detail/profil pengguna |

### 4. **Routes**

| File | Lokasi | Deskripsi |
|------|--------|-----------|
| `web.php` | `routes/` | Sudah diupdate dengan route resource untuk pengguna |

---

## 🎯 Fitur CRUD yang Sudah Dibuat

### ✅ 1. **Daftar Pengguna** (`index`)
**URL:** `/admin/pengguna`
**Method:** GET

**Fitur:**
- Menampilkan semua pengguna dalam tabel
- **Filter berdasarkan role** (Admin, Guru, Siswa)
- **Pencarian** berdasarkan Nama, Email, atau Username
- **Pagination** otomatis (10 data per halaman)
- Tombol aksi: Lihat, Edit, Hapus
- Badge warna berbeda untuk setiap role
- Foto profil (atau icon default jika tidak ada)

**Kolom Tabel:**
- No Urut
- Foto Profil
- Nama Lengkap
- Username
- Email
- Role (dengan badge warna)
- NIP/NIS
- Kelas/Mata Pelajaran
- Aksi (Lihat/Edit/Hapus)

---

### ✅ 2. **Tambah Pengguna** (`create` & `store`)
**URL:** `/admin/pengguna/create`
**Method:** GET (tampil form), POST (submit data)

**Form Input:**
1. **Data Diri:**
   - Nama Lengkap (wajib)
   - Username (wajib, unik, hanya huruf/angka/underscore)
   - Email (wajib, format email, unik)
   - Nomor Telepon (opsional)

2. **Akun & Role:**
   - Role (wajib: Admin/Guru/Siswa)
   - Password (wajib, minimal 6 karakter)
   - Konfirmasi Password (harus sama)

3. **Data Tambahan:**
   - NIP/NIS (opsional)
   - Kelas/Mata Pelajaran (opsional)
   - Upload Foto Profil (opsional, maks 2MB, format JPG/PNG/JPEG)

**Validasi:**
- Semua field wajib divalidasi
- Username hanya boleh huruf, angka, dan underscore
- Email harus format yang benar dan belum terdaftar
- Password minimal 6 karakter
- Foto maksimal 2MB

**Fitur Tambahan:**
- **Dynamic form:** Label berubah otomatis berdasarkan role
  - Guru: NIP & Mata Pelajaran
  - Siswa: NIS & Kelas
  - Admin: Field opsional
- Sidebar panduan pengisian
- Notifikasi sukses setelah menyimpan

---

### ✅ 3. **Edit Pengguna** (`edit` & `update`)
**URL:** `/admin/pengguna/{id}/edit`
**Method:** GET (tampil form), PUT (submit data)

**Fitur:**
- **Form yang sama** dengan tambah (1 file untuk 2 fungsi)
- Password **opsional** saat edit (kosongkan jika tidak ingin mengubah)
- Preview foto lama (jika ada)
- Upload foto baru akan mengganti foto lama
- Username dan email tetap harus unik (kecuali milik user yang sedang diedit)

**Proses:**
- Data lama ter-isi otomatis di form
- Jika upload foto baru, foto lama akan dihapus otomatis
- Password hanya diupdate jika field password diisi

---

### ✅ 4. **Detail Pengguna** (`show`)
**URL:** `/admin/pengguna/{id}`
**Method:** GET

**Tampilan:**
- **Kolom Kiri:**
  - Foto profil (besar)
  - Nama lengkap
  - Role badge
  - Username
  - Tombol Edit & Kembali
  - Info akun (tanggal terdaftar, update terakhir)

- **Kolom Kanan:**
  - Data pribadi lengkap
  - Link kirim email & telepon (jika ada)
  - Statistik (untuk Guru & Siswa - akan diisi nanti)

**Fitur:**
- Tampilan profesional & informatif
- Link aksi cepat (email, telepon)
- Statistik placeholder (siap untuk integrasi data materi/nilai)

---

### ✅ 5. **Hapus Pengguna** (`destroy`)
**URL:** `/admin/pengguna/{id}`
**Method:** DELETE

**Fitur:**
- **Modal konfirmasi** Bootstrap sebelum hapus
- Menampilkan data yang akan dihapus (nama, username, role)
- Peringatan bahwa data tidak bisa dikembalikan
- Auto hapus foto profil dari storage
- Notifikasi sukses setelah menghapus

---

## 🔐 Sistem Keamanan

### Middleware
Semua route pengguna dilindungi dengan:
```php
->middleware(['auth', 'role:admin'])
```

**Artinya:**
1. User harus sudah login (`auth`)
2. User harus memiliki role `admin` (`role:admin`)

Jika Guru atau Siswa mencoba akses → Otomatis di-redirect ke dashboard mereka dengan pesan error.

---

## 📂 Struktur Database

### Tabel: `users`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `id` | bigint | Primary key |
| `name` | varchar(255) | Nama lengkap |
| `username` | varchar(255) | Username untuk login (unique) |
| `email` | varchar(255) | Email (unique) |
| `password` | varchar(255) | Password (hashed) |
| `role` | enum | admin/guru/siswa |
| `nip_nis` | varchar(50) | NIP untuk Guru, NIS untuk Siswa |
| `kelas` | varchar(100) | Kelas (Siswa) atau Mata Pelajaran (Guru) |
| `foto` | varchar(255) | Nama file foto profil |
| `no_telepon` | varchar(20) | Nomor telepon |
| `created_at` | timestamp | Tanggal dibuat |
| `updated_at` | timestamp | Tanggal update terakhir |

---

## 🌐 Daftar Route

| Method | URL | Route Name | Fungsi |
|--------|-----|------------|--------|
| GET | `/admin/pengguna` | `admin.pengguna.index` | Daftar pengguna |
| GET | `/admin/pengguna/create` | `admin.pengguna.create` | Form tambah |
| POST | `/admin/pengguna` | `admin.pengguna.store` | Simpan data baru |
| GET | `/admin/pengguna/{id}` | `admin.pengguna.show` | Detail pengguna |
| GET | `/admin/pengguna/{id}/edit` | `admin.pengguna.edit` | Form edit |
| PUT/PATCH | `/admin/pengguna/{id}` | `admin.pengguna.update` | Update data |
| DELETE | `/admin/pengguna/{id}` | `admin.pengguna.destroy` | Hapus data |

---

## 📸 Upload Foto

### Lokasi Penyimpanan
- **Path Storage:** `storage/app/public/foto_profil/`
- **Path Public:** `public/storage/foto_profil/` (via symbolic link)

### Format File
- **Extension:** JPG, JPEG, PNG
- **Ukuran Maksimal:** 2MB (2048 KB)

### Naming Convention
- Format: `{username}_{timestamp}.{extension}`
- Contoh: `admin_1697300000.jpg`

### Auto Delete
- Saat hapus user → Foto otomatis terhapus
- Saat update foto → Foto lama otomatis terhapus

---

## 🎨 Desain & UI

### Warna Role
- **Admin:** 🔴 Merah (`bg-danger`)
- **Guru:** 🟢 Hijau (`bg-success`)
- **Siswa:** 🔵 Biru (`bg-primary`)

### Bootstrap Components
- **Tabel:** `table-hover`, `table-striped`
- **Form:** `form-control`, `form-select`
- **Modal:** Bootstrap Modal untuk konfirmasi hapus
- **Alert:** `alert-success`, `alert-danger` untuk notifikasi
- **Badge:** Untuk menampilkan role
- **Pagination:** Bootstrap pagination (otomatis dari Laravel)

### Icons
Menggunakan **Bootstrap Icons** (bi-*):
- `bi-people-fill` - Daftar pengguna
- `bi-plus-circle` - Tambah
- `bi-pencil` - Edit
- `bi-trash` - Hapus
- `bi-eye` - Lihat detail
- Dan banyak lagi...

---

## ✍️ Komentar Kode

### Semua file sudah diberi komentar lengkap:

**Contoh di View:**
```blade
{{-- Tombol tambah pengguna baru --}}
<a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">
```

**Contoh di Controller:**
```php
// Validasi input dari form
$validated = $request->validate([...]);

// Proses upload foto (jika ada)
if ($request->hasFile('foto')) {
```

---

## 🚀 Cara Menggunakan

### 1. Login sebagai Admin
```
Username: admin
Password: admin123
```

### 2. Akses Menu Kelola Pengguna
Klik menu **"Kelola Pengguna"** di sidebar

### 3. Tambah Pengguna Baru
1. Klik tombol **"Tambah Pengguna Baru"**
2. Isi form dengan lengkap
3. Upload foto (opsional)
4. Klik **"Simpan Data"**

### 4. Edit Pengguna
1. Klik tombol **Edit** (icon pensil) pada pengguna yang ingin diedit
2. Ubah data yang diperlukan
3. Upload foto baru (opsional)
4. Kosongkan password jika tidak ingin mengubah
5. Klik **"Update Data"**

### 5. Lihat Detail
Klik tombol **Lihat** (icon mata) untuk melihat profil lengkap

### 6. Hapus Pengguna
1. Klik tombol **Hapus** (icon tempat sampah)
2. Konfirmasi di modal yang muncul
3. Klik **"Ya, Hapus!"**

### 7. Filter & Pencarian
- **Filter Role:** Pilih role di dropdown, klik Cari
- **Pencarian:** Ketik nama/email/username, klik Cari
- **Reset:** Klik tombol Reset untuk menampilkan semua data

---

## 📋 Validasi Error

### Pesan Error dalam Bahasa Indonesia:

| Field | Error | Pesan |
|-------|-------|-------|
| Nama | required | "Nama lengkap wajib diisi" |
| Username | required | "Username wajib diisi" |
| Username | unique | "Username sudah digunakan, silakan pilih yang lain" |
| Username | regex | "Username hanya boleh berisi huruf, angka, dan underscore" |
| Email | required | "Email wajib diisi" |
| Email | email | "Format email tidak valid" |
| Email | unique | "Email sudah terdaftar" |
| Password | required | "Password wajib diisi" |
| Password | min:6 | "Password minimal 6 karakter" |
| Password | confirmed | "Konfirmasi password tidak cocok" |
| Role | required | "Role wajib dipilih" |
| Foto | image | "File harus berupa gambar" |
| Foto | mimes | "Format foto harus JPG, JPEG, atau PNG" |
| Foto | max:2048 | "Ukuran foto maksimal 2MB" |

---

## 🔄 Alur Kerja CRUD

### Create (Tambah)
```
User klik "Tambah Pengguna"
→ Tampil form kosong
→ User isi form & upload foto
→ Klik "Simpan"
→ Validasi input
→ Upload foto ke storage
→ Simpan ke database
→ Redirect ke daftar pengguna
→ Tampil notifikasi sukses
```

### Read (Lihat)
```
User akses /admin/pengguna
→ Load data dari database
→ Tampilkan dalam tabel
→ Support filter & pencarian
→ Pagination otomatis
```

### Update (Edit)
```
User klik "Edit"
→ Load data pengguna
→ Tampil form dengan data ter-isi
→ User ubah data yang perlu
→ Upload foto baru (opsional)
→ Klik "Update"
→ Validasi input
→ Hapus foto lama (jika upload baru)
→ Update database
→ Redirect ke daftar
→ Tampil notifikasi sukses
```

### Delete (Hapus)
```
User klik "Hapus"
→ Tampil modal konfirmasi
→ User klik "Ya, Hapus!"
→ Hapus foto dari storage
→ Hapus data dari database
→ Redirect ke daftar
→ Tampil notifikasi sukses
```

---

## 📦 Dependencies

Fitur ini menggunakan:
- **Laravel 11.x** - Framework utama
- **Bootstrap 5** - UI/CSS Framework
- **Bootstrap Icons** - Icon library
- **Laravel Storage** - File upload
- **Laravel Pagination** - Pagination otomatis
- **Laravel Validation** - Validasi form

---

## 🐛 Troubleshooting

### Foto tidak muncul?
**Solusi:**
```bash
php artisan storage:link
```

### Error saat upload foto?
**Cek:**
1. Folder `storage/app/public/foto_profil` ada dan writable
2. Ukuran foto tidak melebihi 2MB
3. Format foto JPG/PNG/JPEG

### Pagination tidak muncul?
**Normal jika:** Data kurang dari 10 (pagination auto hide)

---

## ✅ Checklist Fitur

- [x] Daftar pengguna dengan tabel
- [x] Filter berdasarkan role
- [x] Pencarian berdasarkan nama/email/username
- [x] Pagination (10 data per halaman)
- [x] Form tambah pengguna
- [x] Form edit pengguna (1 file untuk 2 fungsi)
- [x] Validasi lengkap dengan pesan bahasa Indonesia
- [x] Upload foto profil
- [x] Auto delete foto saat hapus/update
- [x] Detail pengguna dengan info lengkap
- [x] Modal konfirmasi hapus
- [x] Notifikasi sukses/error
- [x] Dynamic form berdasarkan role
- [x] Sidebar panduan pengisian
- [x] Breadcrumb navigasi
- [x] Responsive design (Bootstrap 5)
- [x] Komentar lengkap di semua kode
- [x] Route terstruktur dengan middleware

---

## 🎉 Kesimpulan

**CRUD Pengguna sudah 100% selesai dan siap digunakan!**

Fitur ini mencakup semua kebutuhan dasar pengelolaan pengguna dengan:
- ✅ Tampilan profesional & modern
- ✅ Kode yang mudah dipahami (komentar bahasa Indonesia)
- ✅ Validasi ketat
- ✅ Keamanan terjaga (middleware)
- ✅ User-friendly (notifikasi, modal konfirmasi, dll)
- ✅ Mudah dikembangkan untuk fitur selanjutnya

**Siap untuk tahap berikutnya: CRUD Materi, Nilai, Refleksi, dll.**

---

**Dokumentasi ini dibuat dengan 💙 untuk kemudahan pemeliharaan sistem**
**Versi: 1.0 | Tanggal: 15 Oktober 2025**
