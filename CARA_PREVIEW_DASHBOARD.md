# 📋 Cara Preview Dashboard Siswa v2 & v3

## 🎯 Tujuan

Dokumen ini menjelaskan cara melihat preview dashboard siswa versi v2 (Bootstrap) dan v3 (TailwindCSS) untuk perbandingan sebelum memilih versi final.

---

## 🚀 Langkah-Langkah Preview

### Step 1: Jalankan Server Laravel

```bash
cd C:\xampp\htdocs\deeplearning
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

---

### Step 2: Login sebagai Siswa

1. **Buka browser** dan akses: `http://localhost:8000/login`

2. **Login dengan kredensial siswa:**
   ```
   Username: siswa
   Password: siswa123
   ```

3. **Anda akan di-redirect ke dashboard default**

---

### Step 3: Akses Preview Dashboard

Setelah login, Anda bisa mengakses 3 versi dashboard:

#### 📌 Dashboard Original (v1)
```
URL: http://localhost:8000/siswa/dashboard
```
- Dashboard asli dengan sidebar
- Menggunakan Bootstrap default
- File: `resources/views/halaman_siswa/dashboard.blade.php`

#### 📌 Dashboard v2 (Bootstrap Modern)
```
URL: http://localhost:8000/siswa/dashboard-v2
```
- Redesign dengan Bootstrap 5 + Poppins font
- Navbar horizontal sticky
- Hero banner dengan gradient ungu-pink
- Horizontal scroll course cards
- File: `resources/views/halaman_siswa/dashboard_v2.blade.php`

#### 📌 Dashboard v3 (TailwindCSS - IEEE Style)
```
URL: http://localhost:8000/siswa/dashboard-v3
```
- Redesign dengan TailwindCSS
- Modern utility-first approach
- Fully responsive
- Konsep IEEE Courses
- File: `resources/views/halaman_siswa/dashboard_v3_tailwind.blade.php`

---

## 📸 Phase 1 - Preview Dashboard v2 (Bootstrap)

### Fitur Dashboard v2:

✅ **Navbar Horizontal Sticky**
- Logo AdabKita
- Menu: Kelas Saya, Kategori, Sertifikat
- User dropdown (Profil, Logout)
- Warna: Gradient ungu-pink (#667eea → #764ba2)

✅ **Hero Banner**
- Tagline: "Pelajari Adab dengan Cara Menyenangkan & Interaktif"
- Background: Gradient ungu-pink (#f093fb → #f5576c)
- 2 CTA buttons (Mulai Belajar, Lihat Kategori)
- Decorative circles

✅ **Statistik Mini (4 Cards)**
- Materi Selesai: 8/12
- Tugas Dikumpulkan: 15
- Rata-rata Nilai: 88
- Peringkat Kelas: #5

✅ **Course Programs (Horizontal Scroll)**
- 5 course cards dengan emoji icon
- Progress bar per course
- Badge status (Baru, Sedang Dipelajari, Selesai)
- Meta info (durasi, jumlah materi)
- Button "Lanjutkan Belajar"

✅ **Kategori Adab Islami (Grid 8)**
- 🥣 Adab Makan
- 💧 Adab Minum
- 🕌 Adab di Masjid
- 👪 Adab kepada Orang Tua
- 📚 Adab Belajar
- 🤝 Adab Bergaul
- 👔 Adab Berpakaian
- 🌙 Adab Tidur

✅ **Sertifikat Section**
- List sertifikat yang sudah didapat
- Button download PDF
- Info tanggal selesai

### Screenshot Kode v2 (Bootstrap):

```blade
{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-modern navbar-dark">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom" href="#">
            📚 AdabKita
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mx-auto">
                <li><a class="nav-link nav-link-custom" href="#kelas-saya">Kelas Saya</a></li>
                <li><a class="nav-link nav-link-custom" href="#kategori">Kategori</a></li>
                <li><a class="nav-link nav-link-custom" href="#sertifikat">Sertifikat</a></li>
            </ul>
        </div>
    </div>
</nav>

{{-- HERO BANNER --}}
<section class="hero-banner">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Pelajari Adab dengan Cara Menyenangkan & Interaktif 🎓</h1>
            <p class="hero-subtitle">Platform pembelajaran Deep Learning...</p>
            <div class="hero-buttons">
                <a href="#kelas-saya" class="btn btn-hero btn-hero-primary">Mulai Belajar</a>
                <a href="#kategori" class="btn btn-hero btn-hero-secondary">Lihat Kategori</a>
            </div>
        </div>
    </div>
</section>

{{-- COURSE CARDS --}}
<div class="courses-scroll-container">
    <div class="course-card">
        <span class="course-badge in-progress">Sedang Dipelajari</span>
        <div class="course-card-image">🍽️</div>
        <div class="course-card-body">
            <h5 class="course-title">Adab Makan dan Minum</h5>
            <div class="progress-custom">
                <div class="progress-bar-custom" style="width: 75%"></div>
            </div>
            <button class="btn btn-course">Lanjutkan Belajar</button>
        </div>
    </div>
</div>
```

**Styling:** Custom CSS dengan Bootstrap utilities + Google Fonts Poppins

---

## 📸 Phase 2 - Preview Dashboard v3 (TailwindCSS)

### Fitur Dashboard v3:

✅ **Navbar Sticky (Tailwind Utilities)**
- Menggunakan utility classes: `sticky top-0 z-50`
- Flexbox layout: `flex items-center justify-between`
- Responsive: `hidden md:flex`
- Hover effects: `hover:bg-white/20 transition`

✅ **Hero Banner (Grid 2 Kolom)**
- Grid responsive: `grid md:grid-cols-2`
- Typography: `text-4xl lg:text-5xl`
- Buttons: `rounded-full hover:scale-105 transform`
- Illustration: Emoji dengan `animate-bounce`

✅ **Statistik Mini (Grid Responsive)**
- Grid: `grid-cols-2 lg:grid-cols-4`
- Gradient: `bg-gradient-to-br from-blue-50 to-blue-100`
- Hover: `hover:-translate-y-1 transform`
- Text gradient untuk angka

✅ **Course Cards (Horizontal Scroll)**
- Container: `overflow-x-auto custom-scrollbar`
- Cards: `w-80 rounded-2xl shadow-lg`
- Badge: `absolute top-4 left-4 z-10`
- Hover: `hover:-translate-y-2`

✅ **Kategori Grid**
- Responsive: `grid-cols-2 md:grid-cols-3 lg:grid-cols-4`
- Border hover: `border-2 border-transparent hover:border-adab-purple`
- Gradient backgrounds per kategori

✅ **Sertifikat**
- Grid: `grid md:grid-cols-2`
- Flex layout: `flex items-center gap-4`
- Hover: `hover:scale-105`

### Screenshot Kode v3 (TailwindCSS):

```blade
{{-- NAVBAR --}}
<nav class="bg-adab-gradient-purple sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="#" class="flex items-center space-x-2 text-white font-bold text-xl">
                <span class="text-2xl">📚</span>
                <span>AdabKita</span>
            </a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#kelas-saya" class="text-white/90 hover:text-white px-4 py-2 rounded-lg hover:bg-white/20 transition">
                    Kelas Saya
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- HERO BANNER --}}
<section class="bg-adab-gradient-pink relative overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 py-16 lg:py-24">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="text-white animate-fade-in-up">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                    Pelajari Adab dengan Cara<br>
                    <span class="text-yellow-200">Menyenangkan & Interaktif</span> 🎓
                </h1>
                <p class="text-lg lg:text-xl text-white/90 mb-8">...</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#kelas-saya" class="bg-white text-adab-pink px-8 py-3 rounded-full hover:scale-105 transition transform">
                        Mulai Belajar
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-center">
                <div class="text-9xl animate-bounce">📚</div>
            </div>
        </div>
    </div>
</section>

{{-- COURSE CARDS --}}
<div class="overflow-x-auto custom-scrollbar pb-4">
    <div class="flex gap-6 min-w-max">
        <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
            <div class="absolute top-4 left-4 z-10">
                <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Sedang Dipelajari
                </span>
            </div>
            <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                🍽️
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-3">Adab Makan dan Minum</h3>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: 75%"></div>
                </div>
                <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg hover:scale-105 transition transform">
                    Lanjutkan Belajar
                </button>
            </div>
        </div>
    </div>
</div>
```

**Styling:** TailwindCSS utility classes + Custom config untuk warna AdabKita

---

## 📊 Perbandingan v2 vs v3

| Aspek | Dashboard v2 (Bootstrap) | Dashboard v3 (TailwindCSS) |
|-------|--------------------------|----------------------------|
| **CSS Framework** | Bootstrap 5 | TailwindCSS CDN |
| **File Size** | ~200KB (Bootstrap bundle) | ~50KB (with purge) |
| **Approach** | Component-based | Utility-first |
| **Customization** | CSS overrides | Utility classes inline |
| **Learning Curve** | ✅ Easy (familiar) | ⚠️ Medium (new approach) |
| **Maintenance** | Custom CSS file | Utilities in HTML |
| **Flexibility** | ✅ Good | ✅✅ Excellent |
| **Build Time** | ✅ Fast | ✅ Fast (CDN) |
| **Code Style** | Semantic class names | Inline utility classes |
| **Responsive** | Bootstrap grid system | Tailwind responsive utilities |
| **Performance** | Good | ✅ Better (smaller size) |

---

## 🎯 Rekomendasi

### Gunakan Dashboard v2 (Bootstrap) jika:
- ✅ Tim lebih familiar dengan Bootstrap
- ✅ Butuh komponen siap pakai
- ✅ Deadline project ketat
- ✅ Tidak ada waktu belajar framework baru
- ✅ Prefer semantic class names

### Gunakan Dashboard v3 (TailwindCSS) jika:
- ✅ Butuh customization tinggi
- ✅ Performance critical (file size penting)
- ✅ Long-term project
- ✅ Developer suka utility-first approach
- ✅ Modern tech stack
- ✅ Ingin fleksibilitas maksimal

---

## 🔄 Cara Mengganti Dashboard Default

Setelah memilih versi yang diinginkan, ada 3 cara:

### Option 1: Update Route (Recommended)

Edit `routes/web.php`:

```php
// Untuk menggunakan v2:
Route::get('/dashboard', function() {
    return view('halaman_siswa.dashboard_v2');
})->name('dashboard');

// Atau untuk menggunakan v3:
Route::get('/dashboard', function() {
    return view('halaman_siswa.dashboard_v3_tailwind');
})->name('dashboard');
```

### Option 2: Rename File

```bash
# Backup original
mv dashboard.blade.php dashboard_original.blade.php

# Untuk v2:
cp dashboard_v2.blade.php dashboard.blade.php

# Atau untuk v3:
cp dashboard_v3_tailwind.blade.php dashboard.blade.php
```

### Option 3: Update Controller

Edit `SiswaDashboardController.php`:

```php
public function index()
{
    // Untuk v2:
    return view('halaman_siswa.dashboard_v2');

    // Atau untuk v3:
    return view('halaman_siswa.dashboard_v3_tailwind');
}
```

---

## 🧪 Testing Checklist

### Desktop (> 1024px)
- [ ] Navbar horizontal, semua menu terlihat
- [ ] Hero banner full width
- [ ] Course cards scroll smooth
- [ ] Kategori grid 4 kolom
- [ ] Statistik 4 kolom
- [ ] Hover effects berfungsi

### Tablet (768px - 1024px)
- [ ] Navbar compact
- [ ] Kategori 2-3 kolom
- [ ] Stats responsive

### Mobile (< 768px)
- [ ] Navbar hamburger menu
- [ ] Hero buttons stack
- [ ] Course cards scroll smooth
- [ ] Kategori 1-2 kolom
- [ ] Stats stack vertical

---

## 📞 Troubleshooting

### Dashboard tidak muncul / error 404
```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### CSS berantakan
- Cek koneksi internet (CDN)
- Refresh browser (Ctrl + F5)
- Clear browser cache

### Data tidak muncul
- Pastikan sudah login sebagai siswa
- Cek session masih aktif
- Re-login jika perlu

---

## 🎓 Kesimpulan

**Rekomendasi Saya:**

Untuk project AdabKita, saya **merekomendasikan Dashboard v3 (TailwindCSS)** karena:

1. ✅ **Modern & Future-proof** - Tailwind adalah standar industri saat ini
2. ✅ **Lebih Fleksibel** - Easy customization tanpa CSS overrides
3. ✅ **Performance** - File size lebih kecil (~50KB vs ~200KB)
4. ✅ **Maintenance** - Utilities dalam HTML, mudah di-trace
5. ✅ **Responsive** - Tailwind responsive utilities sangat powerful
6. ✅ **Sesuai Brief** - Konsep IEEE Courses lebih terwakili di v3

**NAMUN**, jika tim lebih comfortable dengan Bootstrap, **Dashboard v2 juga sangat bagus** dan sudah production-ready.

---

**URL Testing:**
- v2: http://localhost:8000/siswa/dashboard-v2
- v3: http://localhost:8000/siswa/dashboard-v3

**Next Step:** Pilih salah satu, lalu integrasikan dengan backend data!

---

© 2025 AdabKita - GASPUL | Preview Dashboard Guide
