# 📚 Dokumentasi Dashboard Siswa v2.0 - AdabKita

## 🎯 Pengenalan

Dashboard Siswa v2.0 adalah redesign tampilan dashboard dengan gaya modern ala IEEE Courses, tetapi tetap mempertahankan identitas visual AdabKita (gradasi warna ungu-pink).

**Phase:** 0.3 - UI/UX Redesign
**File:** `resources/views/halaman_siswa/dashboard_v2.blade.php`
**Inspirasi:** IEEE Courses
**Framework:** Laravel + Bootstrap 5 + Poppins Font

---

## 🎨 Perubahan Utama dari Dashboard Lama

### Dashboard Lama ❌
- Sidebar vertical di kiri
- Statistik card berbasis Bootstrap default
- Progress bar list sederhana
- Tidak ada hero banner
- Tidak ada kategorisasi materi

### Dashboard Baru v2.0 ✅
- **Navbar horizontal sticky** (seperti IEEE Courses)
- **Hero banner** dengan gradasi ungu-pink
- **Course cards horizontal scroll** (modern & interaktif)
- **Grid kategori Adab Islami** (8 kategori)
- **Section sertifikat** dengan design menarik
- **Statistik mini cards** (lebih compact)
- **Smooth scrolling** & animasi modern
- **Fully responsive** (mobile-first)

---

## 📁 Struktur Halaman (Top to Bottom)

```
┌──────────────────────────────────────────────────────┐
│ 1. NAVBAR STICKY (Horizontal)                       │
│    - Logo AdabKita                                   │
│    - Menu: Kelas Saya, Kategori, Sertifikat         │
│    - User profile dropdown                           │
├──────────────────────────────────────────────────────┤
│ 2. HERO BANNER                                       │
│    - Judul besar: "Pelajari Adab dengan Cara..."    │
│    - 2 CTA buttons (Mulai Belajar, Lihat Kategori)  │
│    - Background: Gradient ungu-pink                  │
├──────────────────────────────────────────────────────┤
│ 3. STATISTIK MINI (4 Cards)                          │
│    - Materi Selesai: 8/12                            │
│    - Tugas Dikumpulkan: 15                           │
│    - Rata-rata Nilai: 88                             │
│    - Peringkat Kelas: #5                             │
├──────────────────────────────────────────────────────┤
│ 4. KELAS SAYA (Horizontal Scroll)                   │
│    - 5 course cards dengan emoji icon                │
│    - Progress bar per course                         │
│    - Badge status (Baru, Sedang, Selesai)           │
│    - Button "Lanjutkan Belajar"                     │
├──────────────────────────────────────────────────────┤
│ 5. KATEGORI PEMBELAJARAN (Grid 4x2)                 │
│    - 8 kategori Adab Islami                          │
│    - Emoji icon besar per kategori                   │
│    - Jumlah materi tersedia                          │
├──────────────────────────────────────────────────────┤
│ 6. SERTIFIKAT SAYA                                   │
│    - List sertifikat yang sudah didapat              │
│    - Button download PDF                             │
│    - Info tanggal selesai                            │
├──────────────────────────────────────────────────────┤
│ 7. FOOTER SIMPLE                                     │
│    - Copyright AdabKita                              │
└──────────────────────────────────────────────────────┘
```

---

## 🎨 Color Palette

### Warna Utama (Tetap Konsisten dengan AdabKita)

```css
/* Gradien Navbar */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Gradien Hero Banner */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);

/* Background Section */
- White: #ffffff
- Light Purple: #f8f9ff
- Light Gray: #f8f9fa
```

### Warna Status Badge

```css
/* Baru */
background: #ff5722; (Orange-Red)

/* Sedang Dipelajari */
background: #2196f3; (Blue)

/* Selesai */
background: #4caf50; (Green)
```

---

## 🧱 Komponen Utama

### 1. Navbar Horizontal Sticky

**Fitur:**
- Sticky position (tetap di atas saat scroll)
- Menu: Kelas Saya, Kategori, Sertifikat
- Smooth scroll ke section
- User dropdown (Profil, Pengaturan, Logout)
- Responsive (collapse di mobile)

**Kode:**
```blade
<nav class="navbar navbar-expand-lg navbar-modern navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand navbar-brand-custom">📚 AdabKita</a>

    <!-- Menu -->
    <ul class="navbar-nav mx-auto">
        <li><a href="#kelas-saya">Kelas Saya</a></li>
        <li><a href="#kategori">Kategori</a></li>
        <li><a href="#sertifikat">Sertifikat Saya</a></li>
    </ul>

    <!-- User Dropdown -->
    <div class="dropdown">...</div>
</nav>
```

---

### 2. Hero Banner

**Fitur:**
- Background gradient ungu-pink
- Circle decoration (CSS ::before & ::after)
- Judul besar dengan tagline
- 2 CTA buttons
- Fade-in animation

**Kode:**
```blade
<section class="hero-banner">
    <div class="hero-content text-center">
        <h1 class="hero-title">Pelajari Adab dengan Cara...</h1>
        <p class="hero-subtitle">Platform pembelajaran...</p>
        <a class="btn btn-hero-primary">Mulai Belajar</a>
        <a class="btn btn-hero-secondary">Lihat Kategori</a>
    </div>
</section>
```

---

### 3. Course Cards (Horizontal Scroll)

**Fitur:**
- Horizontal scrollable container
- Card dengan emoji icon 5rem
- Progress bar custom
- Status badge (Baru, Sedang, Selesai)
- Meta info (durasi, jumlah materi)
- Hover effect (translateY + shadow)

**Data Per Card:**
```
- Emoji icon (🍽️, 🕌, 👪, 📚, 👔)
- Judul materi
- Durasi (5 menit, 7 menit, dll)
- Jumlah materi (8 materi, 10 materi, dll)
- Progress percentage (0%, 45%, 75%, 100%)
- Status badge (Baru, Sedang Dipelajari, Selesai)
```

**Kode:**
```blade
<div class="courses-scroll-container">
    <div class="course-card">
        <span class="course-badge in-progress">Sedang Dipelajari</span>
        <div class="course-card-image">🍽️</div>
        <div class="course-card-body">
            <h5>Adab Makan dan Minum</h5>
            <div class="course-meta">
                <span>⏱️ 5 menit</span>
                <span>📄 8 materi</span>
            </div>
            <div class="progress-wrapper">
                <div class="progress-custom">
                    <div class="progress-bar-custom" style="width: 75%"></div>
                </div>
            </div>
            <button class="btn btn-course">Lanjutkan Belajar</button>
        </div>
    </div>
</div>
```

---

### 4. Kategori Grid (8 Kategori Adab Islami)

**Kategori yang Tersedia:**

1. **🥣 Adab Makan** - 8 materi
2. **💧 Adab Minum** - 5 materi
3. **🕌 Adab di Masjid** - 10 materi
4. **👪 Adab kepada Orang Tua** - 9 materi
5. **📚 Adab Belajar** - 12 materi
6. **🤝 Adab Bergaul** - 7 materi
7. **👔 Adab Berpakaian** - 7 materi
8. **🌙 Adab Tidur** - 6 materi

**Fitur:**
- Grid layout (4 kolom desktop, 2 kolom tablet, 1 kolom mobile)
- Emoji icon besar (3.5rem)
- Hover effect (scale up + border color)
- Jumlah materi tersedia

---

### 5. Sertifikat Section

**Fitur:**
- Card dengan gradient background (kuning-orange)
- Icon sertifikat
- Nama kursus
- Tanggal selesai
- Button download PDF
- Hover effect (translateX)

**Kode:**
```blade
<div class="certificate-card">
    <div class="certificate-icon">
        <i class="bi bi-award-fill"></i>
    </div>
    <div class="certificate-info">
        <h6>Sertifikat Adab kepada Orang Tua</h6>
        <p class="certificate-date">Selesai: 15 Januari 2025</p>
    </div>
    <button class="btn btn-download">Unduh PDF</button>
</div>
```

---

## 🎬 Animasi & Interaksi

### 1. Fade-in Animation (Page Load)
```css
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

**Elemen yang di-animasi:**
- Hero title, subtitle, buttons
- Category cards saat scroll
- Certificate cards saat scroll

### 2. Hover Effects

**Course Cards:**
```css
.course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.2);
}
```

**Category Cards:**
```css
.category-card:hover {
    transform: translateY(-8px);
    border-color: #667eea;
}
```

### 3. Smooth Scrolling

**JavaScript:**
```javascript
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href'))
            .scrollIntoView({ behavior: 'smooth' });
    });
});
```

### 4. Active Menu Highlight (Scroll Spy)

**JavaScript:**
```javascript
window.addEventListener('scroll', function() {
    // Detect section in viewport
    // Update active menu class
});
```

---

## 📱 Responsive Design

### Breakpoints

```css
/* Desktop: > 768px */
- Navbar: Horizontal menu
- Course cards: 350px width
- Categories: 4 columns
- Stats: 4 columns (row)

/* Tablet: 768px - 576px */
- Navbar: Starts to compact
- Course cards: 280px width
- Categories: 2-3 columns
- Stats: 2 columns (2 rows)

/* Mobile: < 576px */
- Navbar: Hamburger menu
- Course cards: 260px width
- Categories: 1-2 columns
- Stats: 1 column (stack)
- Hero buttons: Full width
```

---

## ✏️ Cara Mengubah Konten

### Mengubah Judul Hero

**File:** `dashboard_v2.blade.php`
**Baris:** ~418

```blade
<h1 class="hero-title">
    Pelajari Adab dengan Cara<br>Menyenangkan & Interaktif 🎓
</h1>
```

Ubah teks sesuai kebutuhan.

---

### Menambah Course Card Baru

**File:** `dashboard_v2.blade.php`
**Section:** Course Programs (baris ~540)

**Template:**
```blade
<div class="course-card">
    <span class="course-badge new">Baru!</span>
    <div class="course-card-image">
        😊 {{-- Ganti dengan emoji yang sesuai --}}
    </div>
    <div class="course-card-body">
        <h5 class="course-title">Judul Materi Baru</h5>
        <div class="course-meta">
            <span><i class="bi bi-clock"></i> 5 menit</span>
            <span><i class="bi bi-file-text"></i> 8 materi</span>
        </div>
        <div class="progress-wrapper">
            <div class="progress-label">
                <span>Progress</span>
                <span>0%</span>
            </div>
            <div class="progress-custom">
                <div class="progress-bar-custom" style="width: 0%"></div>
            </div>
        </div>
        <button class="btn btn-course btn-course-primary">
            <i class="bi bi-play-circle"></i> Mulai Belajar
        </button>
    </div>
</div>
```

---

### Menambah Kategori Baru

**File:** `dashboard_v2.blade.php`
**Section:** Kategori (baris ~670)

**Template:**
```blade
<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="category-card">
        <span class="category-icon">🎯</span>
        <h6 class="category-title">Nama Kategori</h6>
        <p class="category-count">10 materi tersedia</p>
    </div>
</div>
```

---

### Mengubah Warna Tema

**File:** `dashboard_v2.blade.php`
**Section:** `<style>` tag

**Navbar Gradient:**
```css
.navbar-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

**Hero Gradient:**
```css
.hero-banner {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
```

**Progress Bar:**
```css
.progress-bar-custom {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}
```

---

## 🚀 Cara Menggunakan Dashboard Baru

### Testing di Localhost

1. **Jalankan server Laravel:**
   ```bash
   cd C:\xampp\htdocs\deeplearning
   php artisan serve
   ```

2. **Buka browser:**
   ```
   http://localhost:8000/siswa/dashboard-v2
   ```

   > **Note:** Untuk saat ini, dashboard v2 adalah file terpisah untuk preview. Jika ingin mengganti dashboard lama:
   >
   > **Option 1:** Rename file
   > - Rename `dashboard.blade.php` → `dashboard_old.blade.php`
   > - Rename `dashboard_v2.blade.php` → `dashboard.blade.php`
   >
   > **Option 2:** Update route di `web.php`
   > ```php
   > Route::get('/siswa/dashboard', function() {
   >     return view('halaman_siswa.dashboard_v2');
   > });
   > ```

---

## ✅ Checklist Testing

### Desktop (> 1024px)
- [ ] Navbar menu terlihat semua (horizontal)
- [ ] Hero banner full width, teks terbaca jelas
- [ ] Course cards scroll horizontal smooth
- [ ] Kategori grid 4 kolom rapi
- [ ] Statistik 4 kolom (1 baris)
- [ ] Hover effects berfungsi semua

### Tablet (768px - 1024px)
- [ ] Navbar masih horizontal
- [ ] Course cards ukuran proporsional
- [ ] Kategori 2-3 kolom
- [ ] Statistik 2 kolom (2 baris)

### Mobile (< 768px)
- [ ] Navbar collapse (hamburger menu)
- [ ] Hero buttons stack vertical
- [ ] Course cards scroll smooth
- [ ] Kategori 1-2 kolom
- [ ] Statistik 1 kolom (stack)
- [ ] Teks tidak terpotong

### Interaksi
- [ ] Smooth scroll ke section berfungsi
- [ ] Dropdown user profile berfungsi
- [ ] Button hover effects smooth
- [ ] Card hover animations smooth
- [ ] Scroll spy active menu highlight

---

## 🔧 Integrasi dengan Backend (TODO)

Dashboard v2.0 saat ini menggunakan **data dummy/static**. Untuk integrasi dengan backend:

### 1. Controller

Buat method di `SiswaDashboardController`:

```php
public function indexV2()
{
    $user = Auth::user();

    // Ambil data dari database
    $courses = Course::where('user_id', $user->id)->get();
    $certificates = Certificate::where('user_id', $user->id)->get();
    $stats = [
        'completed' => $courses->where('status', 'completed')->count(),
        'total' => $courses->count(),
        'assignments' => Assignment::where('user_id', $user->id)->count(),
        'average_score' => Score::where('user_id', $user->id)->avg('score'),
        'rank' => // Calculate rank
    ];

    return view('halaman_siswa.dashboard_v2', compact('courses', 'certificates', 'stats'));
}
```

### 2. Route

```php
Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'indexV2'])
    ->name('siswa.dashboard');
```

### 3. Update View (Blade)

Ganti data static dengan data dari controller:

```blade
{{-- Stats --}}
<div class="stat-number">{{ $stats['completed'] }}/{{ $stats['total'] }}</div>

{{-- Course Cards Loop --}}
@foreach($courses as $course)
<div class="course-card">
    <span class="course-badge {{ $course->status }}">{{ ucfirst($course->status) }}</span>
    <div class="course-card-image">{{ $course->emoji }}</div>
    <div class="course-card-body">
        <h5 class="course-title">{{ $course->title }}</h5>
        <div class="progress-custom">
            <div class="progress-bar-custom" style="width: {{ $course->progress }}%"></div>
        </div>
        <button class="btn btn-course btn-course-primary">
            {{ $course->progress == 100 ? 'Lihat Ulang' : 'Lanjutkan Belajar' }}
        </button>
    </div>
</div>
@endforeach

{{-- Certificates Loop --}}
@foreach($certificates as $cert)
<div class="certificate-card">
    <div class="certificate-info">
        <h6>{{ $cert->course_name }}</h6>
        <p class="certificate-date">Selesai: {{ $cert->completed_at->format('d F Y') }}</p>
    </div>
    <button class="btn btn-download" onclick="window.open('{{ $cert->pdf_url }}')">
        Unduh PDF
    </button>
</div>
@endforeach
```

---

## 📊 Perbandingan Dashboard v1 vs v2

| Fitur | Dashboard v1 | Dashboard v2 |
|-------|--------------|--------------|
| **Layout** | Sidebar vertical | Navbar horizontal sticky |
| **Hero Banner** | ❌ Tidak ada | ✅ Ada dengan gradient |
| **Course Display** | List vertical | Horizontal scroll cards |
| **Kategori** | ❌ Tidak ada | ✅ 8 kategori grid |
| **Sertifikat** | ❌ Tidak ada section | ✅ Ada section khusus |
| **Animasi** | Minimal | ✅ Fade-in, hover, smooth scroll |
| **Responsive** | ✅ Bootstrap default | ✅ Custom responsive |
| **Font** | System default | ✅ Google Fonts (Poppins) |
| **Color** | Ungu-pink sidebar | ✅ Ungu-pink consistent |
| **Style** | Traditional | ✅ Modern (IEEE-like) |

---

## 💡 Tips Maintenance

1. **Backup File Lama**
   - Simpan `dashboard.blade.php` sebagai `dashboard_old.blade.php`
   - Bisa di-restore kapan saja

2. **Gunakan Komentar**
   - Semua section sudah diberi komentar jelas
   - Mudah mencari bagian yang ingin diubah

3. **Test di Browser Berbeda**
   - Chrome/Edge (latest)
   - Firefox (latest)
   - Safari (jika ada Mac)

4. **Clear Cache**
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

5. **Version Control**
   - Commit dashboard v2 ke branch terpisah dulu
   - Test lengkap sebelum merge ke main

---

## 🎯 Next Steps (Rekomendasi)

### Short Term:
1. ✅ Testing di berbagai device (done manual test)
2. ⏳ Integrasi dengan backend controller
3. ⏳ Tambah loading state untuk course cards
4. ⏳ Implementasi filter kategori
5. ⏳ Add search functionality

### Long Term:
1. 💡 Dark mode toggle
2. 💡 Personalisasi rekomendasi materi (AI)
3. 💡 Gamification (badges, points)
4. 💡 Social features (teman belajar)
5. 💡 Analytics dashboard (progress timeline)

---

## 📞 Support

**Dokumentasi Terkait:**
- [START_HERE.md](START_HERE.md) - Getting started
- [DOKUMENTASI_FRONTPAGE.md](DOKUMENTASI_FRONTPAGE.md) - Frontpage guide

**Jika Ada Masalah:**
1. Cek console browser (F12) untuk error JavaScript
2. Clear cache Laravel
3. Pastikan Bootstrap CDN accessible
4. Hubungi tim developer GASPUL

---

**© 2025 AdabKita - GASPUL | Dashboard v2.0 Documentation**
