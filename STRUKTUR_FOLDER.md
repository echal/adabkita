# 📁 STRUKTUR FOLDER APLIKASI DEEP LEARNING

> **Dokumentasi Struktur Folder Laravel dengan Penamaan Bahasa Indonesia**
> Dibuat agar mudah dipahami oleh Guru, Admin, dan Pegawai Non-Teknis

---

## 🎯 Tujuan Penataan Struktur

1. **Mudah Dipahami** - Menggunakan nama folder dan file berbahasa Indonesia
2. **Mudah Dicari** - Struktur yang terorganisir dengan baik
3. **Mudah Dipelihara** - Kode yang rapi dengan komentar lengkap
4. **Siap Dikembangkan** - Fondasi yang kuat untuk fitur-fitur baru

---

## 📂 Struktur Folder Lengkap

```
deeplearning/
│
├── app/                              # Folder inti aplikasi
│   ├── Http/
│   │   ├── Controllers/              # Folder Controllers (Logika Aplikasi)
│   │   │   ├── Auth/                 # Controllers untuk Autentikasi
│   │   │   │   └── LoginController.php
│   │   │   ├── Admin/                # Controllers khusus Admin
│   │   │   │   └── DashboardController.php
│   │   │   ├── Guru/                 # Controllers khusus Guru
│   │   │   │   └── DashboardController.php
│   │   │   ├── Siswa/                # Controllers khusus Siswa
│   │   │   │   └── DashboardController.php
│   │   │   └── PenggunaController.php    # CRUD Pengguna (untuk Admin)
│   │   │
│   │   └── Middleware/               # Middleware (Filter Request)
│   │       └── CheckRole.php         # Middleware cek role user
│   │
│   └── Models/                       # Folder Models (Struktur Data)
│       └── User.php                  # Model User (Pengguna)
│
├── bootstrap/                        # Folder Bootstrap Laravel
│   └── app.php                       # Konfigurasi aplikasi & middleware
│
├── config/                           # Folder Konfigurasi
│   └── database.php                  # Konfigurasi koneksi database
│
├── database/                         # Folder Database
│   ├── migrations/                   # Folder Migration (Skema Tabel)
│   │   └── 0001_01_01_000000_create_users_table.php
│   │
│   └── seeders/                      # Folder Seeders (Data Awal)
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php            # Data awal user (admin, guru, siswa)
│
├── public/                           # Folder Public (File yang bisa diakses dari luar)
│   ├── assets/                       # Folder Assets (File Pendukung)
│   │   ├── css/                      # File CSS custom (jika ada)
│   │   ├── js/                       # File JavaScript custom (jika ada)
│   │   └── gambar/                   # Folder untuk gambar/ikon
│   │
│   └── index.php                     # Entry point aplikasi
│
├── resources/                        # Folder Resources
│   └── views/                        # Folder Views (Tampilan/UI)
│       ├── layouts/                  # Folder Template Utama
│       │   ├── template_utama.blade.php       # Template dasar semua halaman
│       │   └── template_dashboard.blade.php   # Template untuk halaman dashboard
│       │
│       ├── komponen_umum/            # Folder Komponen yang Dipakai Bersama
│       │   └── halaman_login.blade.php        # Halaman Login
│       │
│       ├── halaman_admin/            # Folder Halaman Khusus Admin
│       │   ├── dashboard.blade.php            # Dashboard Admin
│       │   ├── daftar_pengguna.blade.php      # Daftar Pengguna (akan dibuat)
│       │   ├── form_tambah_pengguna.blade.php # Form Tambah Pengguna (akan dibuat)
│       │   └── form_edit_pengguna.blade.php   # Form Edit Pengguna (akan dibuat)
│       │
│       ├── halaman_guru/             # Folder Halaman Khusus Guru
│       │   └── dashboard.blade.php            # Dashboard Guru
│       │
│       └── halaman_siswa/            # Folder Halaman Khusus Siswa
│           └── dashboard.blade.php            # Dashboard Siswa
│
├── routes/                           # Folder Routes (Routing URL)
│   └── web.php                       # File routing web (semua URL ada di sini)
│
└── storage/                          # Folder Storage (Penyimpanan)
    └── logs/                         # Log error aplikasi

```

---

## 📖 Penjelasan Per Folder

### 1️⃣ **Folder `app/Http/Controllers`**
**Fungsi:** Berisi logika utama aplikasi (Controller)

| File | Fungsi |
|------|--------|
| `LoginController.php` | Menangani proses login & logout |
| `Admin/DashboardController.php` | Menampilkan dashboard admin |
| `Guru/DashboardController.php` | Menampilkan dashboard guru |
| `Siswa/DashboardController.php` | Menampilkan dashboard siswa |
| `PenggunaController.php` | CRUD (Tambah, Lihat, Edit, Hapus) Pengguna |

**Cara Kerja:**
- Controller menerima request dari user
- Memproses data (validasi, simpan ke database, dll)
- Mengembalikan response (view/redirect)

---

### 2️⃣ **Folder `app/Http/Middleware`**
**Fungsi:** Filter request sebelum sampai ke controller

| File | Fungsi |
|------|--------|
| `CheckRole.php` | Mengecek apakah user memiliki role yang sesuai |

**Cara Kerja:**
- Middleware `CheckRole` memastikan user punya akses ke halaman tertentu
- Jika Admin mencoba akses halaman Guru → Akan di-redirect

---

### 3️⃣ **Folder `app/Models`**
**Fungsi:** Struktur data dan interaksi dengan database

| File | Fungsi |
|------|--------|
| `User.php` | Model untuk tabel users (pengguna) |

**Cara Kerja:**
- Model adalah representasi tabel di database
- Digunakan untuk query data (ambil, simpan, update, hapus)

---

### 4️⃣ **Folder `database/migrations`**
**Fungsi:** Skema/struktur tabel database

| File | Fungsi |
|------|--------|
| `create_users_table.php` | Membuat tabel users dengan kolom username, email, password, role |

**Cara Kerja:**
- Migration adalah blueprint tabel database
- Jalankan `php artisan migrate` untuk membuat tabel

---

### 5️⃣ **Folder `database/seeders`**
**Fungsi:** Data awal untuk testing

| File | Fungsi |
|------|--------|
| `UserSeeder.php` | Membuat 3 user awal (admin, guru, siswa) |

**Cara Kerja:**
- Seeder mengisi database dengan data dummy
- Jalankan `php artisan db:seed` untuk mengisi data

---

### 6️⃣ **Folder `resources/views`**
**Fungsi:** Tampilan/UI aplikasi

#### **A. Layouts (Template Utama)**
- `template_utama.blade.php` → Template dasar semua halaman
- `template_dashboard.blade.php` → Template untuk dashboard (dengan sidebar)

#### **B. Komponen Umum**
- `halaman_login.blade.php` → Halaman login

#### **C. Halaman Admin**
- `dashboard.blade.php` → Dashboard admin
- `daftar_pengguna.blade.php` → Daftar semua pengguna (akan dibuat)
- `form_tambah_pengguna.blade.php` → Form tambah pengguna (akan dibuat)

#### **D. Halaman Guru**
- `dashboard.blade.php` → Dashboard guru

#### **E. Halaman Siswa**
- `dashboard.blade.php` → Dashboard siswa

---

### 7️⃣ **Folder `public/assets`**
**Fungsi:** File pendukung (CSS, JS, Gambar)

| Subfolder | Fungsi |
|-----------|--------|
| `css/` | File CSS custom |
| `js/` | File JavaScript custom |
| `gambar/` | Logo, ikon, gambar |

---

### 8️⃣ **File `routes/web.php`**
**Fungsi:** Mendefinisikan semua URL aplikasi

**Struktur Route:**
```
/                    → Redirect ke login atau dashboard
/login               → Halaman login
/admin/dashboard     → Dashboard admin
/guru/dashboard      → Dashboard guru
/siswa/dashboard     → Dashboard siswa
/admin/pengguna      → CRUD pengguna (hanya admin)
```

---

## 🔧 Cara Menambahkan Fitur Baru

### Contoh: Menambah CRUD Materi

**1. Buat Controller**
```bash
php artisan make:controller MateriController
```

**2. Buat Migration**
```bash
php artisan make:migration buat_tabel_materi
```

**3. Buat View**
- Buat folder: `resources/views/halaman_admin/materi/`
- Buat file: `daftar_materi.blade.php`, `form_tambah_materi.blade.php`, dll

**4. Tambahkan Route**
Buka `routes/web.php`, tambahkan di grup admin:
```php
Route::resource('materi', MateriController::class);
```

---

## 📝 Konvensi Penamaan

### File & Folder
- **Folder:** gunakan snake_case → `halaman_admin`, `komponen_umum`
- **Controller:** gunakan PascalCase → `PenggunaController`, `MateriController`
- **View:** gunakan snake_case → `daftar_pengguna.blade.php`

### Variabel
- **Variable:** gunakan snake_case → `$daftar_pengguna`, `$data_materi`
- **Function:** gunakan camelCase → `index()`, `showLoginForm()`

### Database
- **Tabel:** gunakan plural snake_case → `users`, `materis`, `nilais`
- **Kolom:** gunakan snake_case → `user_id`, `created_at`

---

## 🎨 Konvensi Warna Dashboard

Setiap role memiliki warna tema berbeda:

| Role | Warna | Gradient |
|------|-------|----------|
| **Admin** | Ungu (Purple) | `#667eea` → `#764ba2` |
| **Guru** | Hijau (Green) | `#11998e` → `#38ef7d` |
| **Siswa** | Pink/Merah | `#f093fb` → `#f5576c` |

---

## 🚀 Command Laravel yang Sering Digunakan

| Command | Fungsi |
|---------|--------|
| `php artisan serve` | Menjalankan server development |
| `php artisan migrate` | Membuat tabel database |
| `php artisan db:seed` | Mengisi database dengan data awal |
| `php artisan migrate:fresh --seed` | Reset database & isi ulang |
| `php artisan make:controller NamaController` | Membuat controller baru |
| `php artisan make:migration nama_migration` | Membuat migration baru |
| `php artisan route:list` | Melihat semua route yang tersedia |

---

## 📚 File Penting yang Perlu Diketahui

| File | Fungsi |
|------|--------|
| `.env` | Konfigurasi environment (database, app key, dll) |
| `routes/web.php` | Semua URL aplikasi |
| `config/database.php` | Konfigurasi koneksi database |
| `STRUKTUR_FOLDER.md` | Dokumentasi ini |

---

## ✅ Checklist Pengembangan Selanjutnya

- [x] Sistem Login Multi-Role
- [x] Dashboard Admin, Guru, Siswa
- [x] Struktur Folder Rapi
- [x] CRUD Pengguna (Controller sudah siap, tinggal buat view)
- [ ] CRUD Materi Pembelajaran
- [ ] CRUD Tugas & Quiz
- [ ] Sistem Input & Lihat Nilai
- [ ] Sistem Refleksi Pembelajaran
- [ ] Rubrik Penilaian

---

## 💡 Tips untuk Admin Pemelihara

1. **Selalu baca komentar di kode** - Setiap file penting ada penjelasannya
2. **Gunakan struktur folder yang sudah ada** - Jangan membuat folder baru tanpa alasan
3. **Ikuti konvensi penamaan** - Agar konsisten dan mudah dicari
4. **Backup database secara berkala** - Untuk mencegah kehilangan data
5. **Test fitur baru di environment development dulu** - Jangan langsung di production

---

**Dibuat dengan 💙 untuk kemudahan pemeliharaan sistem**
**Versi: 1.0 | Tanggal: 2025**
