# 📸 Panduan Visual Frontpage AdabKita

## 🎨 Struktur Visual Halaman

Dokumen ini menjelaskan struktur visual frontpage AdabKita dari atas ke bawah.

---

## 1️⃣ NAVBAR (Bagian Paling Atas)

```
┌─────────────────────────────────────────────────────────────┐
│  📚 AdabKita    Keunggulan  Tentang  Video    [Login]       │
└─────────────────────────────────────────────────────────────┘
```

**Elemen:**
- Logo/Brand: "📚 AdabKita" (kiri)
- Menu: Keunggulan, Tentang, Video (tengah)
- Tombol Login (kanan)
- Warna: Ungu gradient
- Posisi: Sticky (tetap di atas saat scroll)

**Di Mobile:**
```
┌──────────────────────────────────┐
│  📚 AdabKita              ☰      │
└──────────────────────────────────┘
```
Menu berubah jadi hamburger icon

---

## 2️⃣ HERO SECTION (Banner Utama)

```
╔═════════════════════════════════════════════════════════════╗
║                  [Background: Ungu Gradient]                ║
║                                                              ║
║          Selamat Datang di AdabKita                         ║
║                                                              ║
║     Platform pembelajaran Deep Learning untuk               ║
║     Adab Islami di MTsN. Sistem evaluasi cerdas             ║
║     dan pembelajaran interaktif berbasis teknologi AI.      ║
║                                                              ║
║     [Masuk ke Sistem]  [Lihat Video]                        ║
║                                                              ║
╚═════════════════════════════════════════════════════════════╝
```

**Elemen:**
- Judul besar (56px desktop, 32px mobile)
- Deskripsi 2 baris
- 2 tombol CTA (putih + transparan)
- Background: Gradient ungu dengan circle decoration
- Padding: 120px atas, 100px bawah

---

## 3️⃣ KEUNGGULAN SECTION (3 Kartu Fitur)

```
               Keunggulan AdabKita
    Tiga fitur utama yang membuat pembelajaran...

┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│      🧠      │  │      📊      │  │      🎓      │
│              │  │              │  │              │
│ Deep Learning│  │   Evaluasi   │  │ Pembelajaran │
│      AI      │  │    Cerdas    │  │  Interaktif  │
│              │  │              │  │              │
│  Sistem...   │  │ Penilaian... │  │  Materi...   │
└──────────────┘  └──────────────┘  └──────────────┘
```

**Elemen:**
- 3 kartu (card) dengan emoji besar
- Judul fitur
- Deskripsi singkat
- Hover effect: Naik ke atas + border ungu
- Background: Abu-abu muda (#f8f9ff)
- Layout: 3 kolom (desktop), stack (mobile)

---

## 4️⃣ TENTANG SECTION

```
┌─────────────────┐  Tentang AdabKita
│                 │
│       📖        │  AdabKita adalah platform...
│    (200px)      │
│                 │  ✓ Materi Lengkap
└─────────────────┘    Mencakup seluruh...

                       ✓ Mudah Diakses
                         Dapat diakses...

                       ✓ Monitoring Real-time
                         Guru dapat...
```

**Elemen:**
- Emoji besar di kiri (📖)
- Konten text di kanan
- 3 benefit dengan icon checklist
- Background: Putih
- Layout: 2 kolom (desktop), stack (mobile)

---

## 5️⃣ VIDEO SECTION

```
          Lihat AdabKita dalam Aksi
    Video penjelasan singkat tentang cara...

┌───────────────────────────────────────────────┐
│                                               │
│                                               │
│           [YouTube Video Player]              │
│              (Responsive 16:9)                │
│                                               │
│                                               │
└───────────────────────────────────────────────┘
```

**Elemen:**
- Judul section
- Subtitle
- Embed YouTube responsive
- Border radius: 20px
- Shadow: 0 10px 40px
- Max width: 900px (centered)
- Background: Putih

---

## 6️⃣ CALL TO ACTION (CTA)

```
╔═════════════════════════════════════════════════════════════╗
║                  [Background: Ungu Gradient]                ║
║                                                              ║
║              Siap Memulai Pembelajaran?                     ║
║                                                              ║
║     Bergabunglah dengan ribuan siswa lain yang telah        ║
║     merasakan manfaat pembelajaran interaktif AdabKita      ║
║                                                              ║
║        [Login Sekarang]  [Hubungi Kami]                     ║
║                                                              ║
╚═════════════════════════════════════════════════════════════╝
```

**Elemen:**
- Judul CTA (40px)
- Deskripsi persuasif
- 2 tombol (putih + transparan)
- Background: Sama dengan hero (ungu gradient)
- Text: Putih

---

## 7️⃣ FOOTER (Bagian Paling Bawah)

```
┌──────────────┬──────────────┬──────────────┐
│ 📚 AdabKita  │ Link Cepat   │   Kontak     │
│              │              │              │
│ Platform...  │ Keunggulan   │ 📧 Email     │
│              │ Tentang      │ 🌐 Website   │
│              │ Video        │ 📍 Alamat    │
│              │ Login        │              │
└──────────────┴──────────────┴──────────────┘
─────────────────────────────────────────────
  © 2025 AdabKita - GASPUL. All Rights...
```

**Elemen:**
- 3 kolom info (desktop)
- Stack vertical (mobile)
- Background: Dark gray (#2d3748)
- Text: Abu-abu muda (rgba white 0.8)
- Copyright bar di bawah
- Border top pada copyright section

---

## 📱 Responsive Behavior

### Desktop (> 768px)
- Navbar horizontal, menu terlihat semua
- Hero: Text besar, 2 tombol horizontal
- Features: 3 kartu horizontal (row)
- Tentang: 2 kolom (gambar + text)
- Footer: 3 kolom

### Tablet (768px - 576px)
- Navbar: Menu mulai compact
- Features: 2 kartu per row
- Text size sedikit lebih kecil
- Footer: 2 kolom

### Mobile (< 576px)
- Navbar: Hamburger menu
- Hero: Text lebih kecil, tombol stack vertical
- Features: 1 kartu per row (stack)
- Tentang: Stack vertical
- Video: Full width responsive
- Footer: 1 kolom (stack)

---

## 🎨 Color Reference

### Primary Colors
```
┌────────┐  #667eea  Indigo (Primary)
│        │  Used: Navbar, Hero, CTA, Buttons
└────────┘

┌────────┐  #764ba2  Purple (Secondary)
│        │  Used: Gradient end, Accents
└────────┘
```

### Background Colors
```
┌────────┐  #ffffff  White
│        │  Used: Cards, Sections
└────────┘

┌────────┐  #f8f9ff  Light Blue-ish
│        │  Used: Features section background
└────────┘

┌────────┐  #2d3748  Dark Gray
│        │  Used: Footer
└────────┘
```

### Text Colors
```
┌────────┐  #333333  Dark Gray (Body text)
└────────┘

┌────────┐  #666666  Medium Gray (Secondary text)
└────────┘

┌────────┐  #ffffff  White (On dark backgrounds)
└────────┘
```

---

## 🎬 Animation Guide

### Page Load
```
[Hero Content]
  ↓ Fade In + Slide Up
  ↓ Duration: 0.8s - 1.2s
  ↓ Stagger effect (title → desc → buttons)
```

### Scroll Animations
```
[Feature Cards]
  → Invisible by default
  → When in viewport:
     - Opacity: 0 → 1
     - TranslateY: 30px → 0px
  → Duration: 0.6s
```

### Hover Effects
```
[Feature Cards]
  - Normal: box-shadow small
  - Hover:
    • TranslateY: 0 → -10px
    • Box-shadow: larger
    • Border: transparent → #667eea

[Buttons]
  - Normal: static
  - Hover:
    • TranslateY: 0 → -2px
    • Background: gradient darker
    • Box-shadow: larger
```

### Smooth Scroll
```
[Anchor Links]
  - Click "Keunggulan"
  → Smooth scroll ke #keunggulan
  → Duration: Auto
  → Behavior: smooth
```

---

## 📐 Spacing Guide

### Section Padding
```
Desktop:  80px top/bottom
Mobile:   60px top/bottom
```

### Container
```
Max-width: 1140px
Padding: 0 15px (mobile)
```

### Card Spacing
```
Padding: 40px 30px
Margin-bottom: 20px
Border-radius: 20px
```

### Typography Spacing
```
Section Title:     margin-bottom: 1rem
Section Subtitle:  margin-bottom: 3rem
Paragraph:         line-height: 1.7
```

---

## 🎯 Interactive Elements Map

### Clickable Elements:

1. **Navbar Brand** → Home (/)
2. **Menu "Keunggulan"** → Smooth scroll #keunggulan
3. **Menu "Tentang"** → Smooth scroll #tentang
4. **Menu "Video"** → Smooth scroll #video
5. **Button "Login" (Navbar)** → /login
6. **Hero Button "Masuk ke Sistem"** → /login
7. **Hero Button "Lihat Video"** → Smooth scroll #video
8. **CTA Button "Login Sekarang"** → /login
9. **CTA Button "Hubungi Kami"** → mailto:email
10. **Footer Links** → Smooth scroll / /login
11. **Footer Email** → mailto:email
12. **Footer Website** → external link

---

## 💡 Tips Kustomisasi Visual

### Mengubah Emoji/Icon
```blade
<!-- Cari di frontpage.blade.php -->
<span class="feature-icon">🧠</span>

<!-- Ganti dengan emoji lain -->
<span class="feature-icon">💻</span>
```

### Mengubah Ukuran Font
```css
/* Di layouts/front.blade.php */
.hero-title {
    font-size: 3.5rem;  /* Desktop */
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;  /* Mobile */
    }
}
```

### Mengubah Border Radius (Rounded Corner)
```css
/* Kartu lebih kotak */
.feature-card {
    border-radius: 10px;  /* dari 20px */
}

/* Kartu lebih rounded */
.feature-card {
    border-radius: 30px;  /* dari 20px */
}
```

### Mengubah Shadow
```css
/* Shadow lebih tipis */
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

/* Shadow lebih tebal */
box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
```

---

## 🖼️ Placeholder yang Perlu Diganti

1. **Video YouTube**
   - Current: `dQw4w9WgXcQ` (dummy)
   - Action: Ganti dengan ID video asli

2. **Email Kontak**
   - Current: `info@adabkita.gaspul.com`
   - Action: Ganti dengan email real

3. **Alamat Sekolah**
   - Current: "MTsN - Madrasah Tsanawiyah Negeri"
   - Action: Lengkapi dengan alamat lengkap

4. **Emoji Ilustrasi**
   - Current: 📖 (buku)
   - Option: Bisa diganti dengan gambar PNG/SVG

---

## ✅ Visual Checklist

Sebelum launch, pastikan:

- [ ] Semua teks terbaca jelas (kontras cukup)
- [ ] Spacing konsisten di semua section
- [ ] Tidak ada elemen yang terpotong di mobile
- [ ] Button mudah diklik (min 44x44px)
- [ ] Hover effect berfungsi semua
- [ ] Animasi smooth, tidak lag
- [ ] Video responsive di semua ukuran
- [ ] Footer rapi di mobile dan desktop
- [ ] Warna konsisten dengan brand

---

© 2025 AdabKita - GASPUL | Panduan Visual v1.0
