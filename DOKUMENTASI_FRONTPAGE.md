# 📚 Dokumentasi Frontpage AdabKita

## 🎯 Pengenalan

Frontpage (Landing Page) adalah halaman pertama yang dilihat pengunjung saat membuka website **AdabKita** di URL: `https://adabkita.gaspul.com`

Halaman ini dirancang untuk:
- Memberikan informasi tentang AdabKita
- Menampilkan keunggulan sistem pembelajaran
- Mengajak pengunjung untuk login ke sistem
- Memperkenalkan fitur-fitur utama dengan cara yang menarik

---

## 📁 Struktur File

Frontpage terdiri dari 3 file utama:

### 1. **Layout Template** (`resources/views/layouts/front.blade.php`)
   - File template dasar untuk halaman frontpage
   - Berisi kode CSS untuk styling
   - Berisi struktur HTML dasar
   - **Jangan diubah** kecuali ingin mengubah warna atau gaya keseluruhan

### 2. **Halaman Frontpage** (`resources/views/frontpage.blade.php`)
   - File utama yang berisi konten frontpage
   - Di sinilah Anda mengubah teks, gambar, dan video
   - File ini menggunakan layout dari `front.blade.php`

### 3. **Route Configuration** (`routes/web.php`)
   - File yang mengatur routing URL
   - Menentukan bahwa URL `/` menampilkan halaman frontpage
   - Sudah dikonfigurasi, tidak perlu diubah

---

## ✏️ Cara Mengubah Konten Frontpage

### 🎨 Mengubah Judul Utama (Hero Section)

**Lokasi:** `resources/views/frontpage.blade.php` baris 72-75

```blade
<h1 class="hero-title">
    Selamat Datang di <strong>AdabKita</strong>
</h1>
```

**Cara mengubah:**
1. Buka file `frontpage.blade.php`
2. Cari bagian `<h1 class="hero-title">`
3. Ubah teks di dalam tag tersebut
4. Simpan file

---

### 📝 Mengubah Deskripsi Hero Section

**Lokasi:** `resources/views/frontpage.blade.php` baris 78-82

```blade
<p class="hero-description">
    Platform pembelajaran Deep Learning untuk Adab Islami di MTsN.<br>
    Sistem evaluasi cerdas dan pembelajaran interaktif berbasis teknologi AI.
</p>
```

**Cara mengubah:**
1. Ubah teks di dalam `<p class="hero-description">`
2. Tag `<br>` digunakan untuk ganti baris
3. Simpan file

---

### 🎬 Mengganti Video YouTube

**Lokasi:** `resources/views/frontpage.blade.php` baris 235-245

```blade
<iframe
    src="https://www.youtube.com/embed/dQw4w9WgXcQ"
    ...
</iframe>
```

**Cara mengganti video:**

1. **Cari video di YouTube** yang ingin ditampilkan
2. **Buka video tersebut** di YouTube
3. **Salin URL video**, contoh: `https://www.youtube.com/watch?v=ABC123XYZ`
4. **Ambil ID video** (bagian setelah `v=`), dalam contoh di atas: `ABC123XYZ`
5. **Buka file** `frontpage.blade.php`
6. **Cari baris** yang berisi `src="https://www.youtube.com/embed/dQw4w9WgXcQ"`
7. **Ganti `dQw4w9WgXcQ`** dengan ID video baru (contoh: `ABC123XYZ`)
8. **Simpan file**

**Contoh:**
```blade
<!-- Sebelum -->
src="https://www.youtube.com/embed/dQw4w9WgXcQ"

<!-- Sesudah -->
src="https://www.youtube.com/embed/ABC123XYZ"
```

---

### 🏆 Mengubah Keunggulan (Features)

**Lokasi:** `resources/views/frontpage.blade.php` baris 117-170

Ada 3 keunggulan utama yang ditampilkan:
1. **Deep Learning AI** (🧠)
2. **Evaluasi Cerdas** (📊)
3. **Pembelajaran Interaktif** (🎓)

**Struktur satu keunggulan:**

```blade
<div class="col-lg-4 col-md-6 mb-4">
    <div class="feature-card">
        <!-- Emoji/Icon -->
        <span class="feature-icon">🧠</span>

        <!-- Judul -->
        <h3 class="feature-title">Deep Learning AI</h3>

        <!-- Deskripsi -->
        <p class="feature-description">
            Sistem pembelajaran berbasis Deep Learning...
        </p>
    </div>
</div>
```

**Cara mengubah:**
1. Ubah emoji di `<span class="feature-icon">`
2. Ubah judul di `<h3 class="feature-title">`
3. Ubah deskripsi di `<p class="feature-description">`
4. Simpan file

---

### 📧 Mengubah Email Kontak

**Lokasi:** `resources/views/frontpage.blade.php` baris 289 dan 318

```blade
<!-- Di section CTA -->
<a href="mailto:info@adabkita.gaspul.com" ...>

<!-- Di footer -->
<a href="mailto:info@adabkita.gaspul.com">info@adabkita.gaspul.com</a>
```

**Cara mengubah:**
1. Cari semua `info@adabkita.gaspul.com`
2. Ganti dengan email yang baru
3. Simpan file

---

### 🎨 Mengubah Warna Tema

**Lokasi:** `resources/views/layouts/front.blade.php`

Warna utama yang digunakan: **Ungu Gradien** (`#667eea` → `#764ba2`)

**Cara mengubah warna:**

1. Buka file `layouts/front.blade.php`
2. Cari bagian CSS yang mengandung:
   ```css
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   ```
3. Ganti kode warna:
   - `#667eea` → warna pertama
   - `#764ba2` → warna kedua
4. Simpan file

**Contoh warna alternatif:**
- **Biru:** `#1e3a8a` → `#3b82f6`
- **Hijau:** `#065f46` → `#10b981`
- **Merah:** `#7f1d1d` → `#ef4444`

**Tool untuk memilih warna:**
- Google "Color Picker"
- Gunakan website: https://colorhunt.co/
- Gunakan website: https://coolors.co/

---

## 🚀 Cara Testing Frontpage

### 1. **Menggunakan PHP Built-in Server**

```bash
# Masuk ke folder project
cd C:\xampp\htdocs\deeplearning

# Jalankan server Laravel
php artisan serve
```

Kemudian buka browser dan akses: `http://localhost:8000`

### 2. **Menggunakan XAMPP**

1. Pastikan Apache sudah running di XAMPP
2. Buka browser
3. Akses: `http://localhost/deeplearning/public`

---

## 🔧 Troubleshooting

### ❌ **Halaman frontpage tidak muncul / redirect ke login**

**Solusi:**
1. Buka file `routes/web.php`
2. Pastikan baris 49 berisi: `return view('frontpage');`
3. Clear cache Laravel:
   ```bash
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

---

### ❌ **CSS tidak muncul / halaman berantakan**

**Solusi:**
1. Periksa koneksi internet (CSS menggunakan Bootstrap dari CDN)
2. Buka Inspect Element (F12) → Console
3. Lihat apakah ada error loading CSS/JS
4. Pastikan file `layouts/front.blade.php` tidak ada yang terhapus

---

### ❌ **Video YouTube tidak tampil**

**Solusi:**
1. Pastikan ID video sudah benar
2. Pastikan video bukan video private
3. Periksa URL embed, harus format:
   ```
   https://www.youtube.com/embed/VIDEO_ID
   ```
   Bukan format:
   ```
   https://www.youtube.com/watch?v=VIDEO_ID
   ```

---

## 📋 Checklist Konten yang Perlu Diubah

Sebelum launch ke production, pastikan sudah mengubah:

- [ ] Judul hero section (sesuaikan dengan nama sekolah)
- [ ] Deskripsi hero section
- [ ] ID Video YouTube (ganti dengan video penjelasan asli)
- [ ] Email kontak (sesuaikan dengan email sekolah)
- [ ] Nama sekolah di bagian "Tentang"
- [ ] Alamat sekolah di footer
- [ ] Cek semua link berfungsi dengan baik

---

## 📞 Kontak Developer

Jika ada pertanyaan atau butuh bantuan lebih lanjut, silakan hubungi:

- **Email:** developer@adabkita.gaspul.com
- **Tim GASPUL**

---

## 📝 Catatan Penting

1. **Backup file** sebelum melakukan perubahan besar
2. **Test di localhost** sebelum upload ke server production
3. **Gunakan editor text** seperti VS Code atau Notepad++ untuk mengedit
4. **Jangan edit file** saat website sedang diakses banyak user
5. **Dokumentasikan** setiap perubahan yang dilakukan

---

© 2025 AdabKita - GASPUL | Dokumentasi v1.0
