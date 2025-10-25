# 🚀 PANDUAN CEPAT - SISTEM DEEP LEARNING

> Panduan singkat untuk menjalankan dan menggunakan aplikasi

---

## ⚡ Cara Menjalankan Aplikasi

### 1. Pastikan Requirements Terpenuhi
- ✅ PHP 8.4 atau lebih tinggi
- ✅ MySQL/MariaDB
- ✅ Composer

### 2. Konfigurasi Database
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deeplearning
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies (Jika Belum)
```bash
composer install
```

### 4. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Server
```bash
php artisan serve
```

### 6. Akses Aplikasi
Buka browser, akses: **http://localhost:8000**

---

## 🔐 Akun Login

| Role | Username | Password |
|------|----------|----------|
| **Admin** | `admin` | `admin123` |
| **Guru** | `guru` | `guru123` |
| **Siswa** | `siswa` | `siswa123` |

---

## 📋 Fitur yang Tersedia

### ✅ Sudah Selesai
- [x] Login multi-role (Admin, Guru, Siswa)
- [x] Dashboard Admin dengan statistik
- [x] Dashboard Guru dengan aktivitas siswa
- [x] Dashboard Siswa dengan progress belajar
- [x] Middleware proteksi akses berdasarkan role
- [x] Layout template yang konsisten
- [x] Controller CRUD Pengguna (siap pakai)

### 🔄 Sedang Dikembangkan
- [ ] Halaman CRUD Pengguna (View)
- [ ] CRUD Materi Pembelajaran
- [ ] CRUD Tugas & Quiz
- [ ] Sistem Nilai

---

## 📁 Struktur Folder Penting

```
deeplearning/
├── app/Http/Controllers/        → Logika aplikasi
├── resources/views/             → Tampilan (UI)
│   ├── layouts/                 → Template utama
│   ├── komponen_umum/           → Komponen bersama (login, dll)
│   ├── halaman_admin/           → Halaman khusus admin
│   ├── halaman_guru/            → Halaman khusus guru
│   └── halaman_siswa/           → Halaman khusus siswa
├── routes/web.php               → Semua route/URL
└── public/assets/               → CSS, JS, Gambar
```

**Detail lengkap:** Lihat file [STRUKTUR_FOLDER.md](STRUKTUR_FOLDER.md)

---

## 🌐 URL Penting

| URL | Halaman |
|-----|---------|
| `/` | Redirect ke login atau dashboard |
| `/login` | Halaman login |
| `/admin/dashboard` | Dashboard Admin |
| `/guru/dashboard` | Dashboard Guru |
| `/siswa/dashboard` | Dashboard Siswa |
| `/admin/pengguna` | CRUD Pengguna (Admin) |

**Lihat semua route:**
```bash
php artisan route:list
```

---

## 🛠️ Command Laravel yang Berguna

### Menjalankan Aplikasi
```bash
php artisan serve
```

### Database
```bash
php artisan migrate              # Jalankan migration
php artisan migrate:fresh --seed # Reset database & isi ulang
php artisan db:seed              # Isi database dengan data awal
```

### Membuat File Baru
```bash
php artisan make:controller NamaController
php artisan make:model NamaModel
php artisan make:migration nama_migration
```

### Maintenance
```bash
php artisan cache:clear          # Hapus cache
php artisan config:clear         # Hapus cache konfigurasi
php artisan route:clear          # Hapus cache route
php artisan view:clear           # Hapus cache view
```

---

## 🎨 Tema Warna Dashboard

Setiap role memiliki warna berbeda:

- **Admin** → 🟣 Ungu (Purple)
- **Guru** → 🟢 Hijau (Green)
- **Siswa** → 🔴 Pink/Merah

---

## 📖 Dokumentasi Lengkap

- **Struktur Folder:** [STRUKTUR_FOLDER.md](STRUKTUR_FOLDER.md)
- **Laravel Docs:** https://laravel.com/docs/11.x
- **Bootstrap 5 Docs:** https://getbootstrap.com/docs/5.3

---

## 🆘 Troubleshooting

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied"
- Periksa konfigurasi database di file `.env`
- Pastikan MySQL sudah berjalan

### Halaman Putih/Error 500
```bash
php artisan cache:clear
php artisan config:clear
```

### Migration Error
```bash
php artisan migrate:fresh --seed
```

---

## 📞 Kontak

Jika ada pertanyaan atau masalah:
1. Baca dokumentasi di [STRUKTUR_FOLDER.md](STRUKTUR_FOLDER.md)
2. Cek komentar di kode (semua file sudah diberi komentar bahasa Indonesia)
3. Hubungi tim pengembang

---

**Selamat menggunakan Sistem Deep Learning! 🎓**
