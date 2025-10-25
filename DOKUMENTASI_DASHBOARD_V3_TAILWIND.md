# 📚 Dokumentasi Dashboard Siswa v3.0 - TailwindCSS

## 🎯 Pengenalan

Dashboard Siswa v3.0 adalah **redesign dengan TailwindCSS** berdasarkan konsep IEEE Courses. Versi ini lebih modern, modular, dan menggunakan utility-first CSS framework.

**Phase:** 0.3 - UI/UX Redesign (TailwindCSS Version)
**File:** `resources/views/halaman_siswa/dashboard_v3_tailwind.blade.php`
**Inspirasi:** IEEE Courses
**Framework:** Laravel + TailwindCSS CDN + Poppins Font

---

## 🆕 Perbedaan v3 (Tailwind) vs v2 (Bootstrap)

| Aspek | v2 (Bootstrap) | v3 (TailwindCSS) |
|-------|----------------|------------------|
| **CSS Framework** | Bootstrap 5 | TailwindCSS CDN |
| **Utility Classes** | Bootstrap utilities | Tailwind utilities |
| **Customization** | Custom CSS | Tailwind config + utilities |
| **File Size** | Larger (Bootstrap bundle) | Smaller (CDN + purge) |
| **Flexibility** | Pre-designed components | Fully customizable |
| **Learning Curve** | Easy (component-based) | Medium (utility-first) |
| **Code Style** | Class names semantik | Utility classes inline |

---

## 🎨 Tailwind Custom Config

Warna khas AdabKita didefinisikan di Tailwind config:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'adab-purple': {
                    light: '#667eea',
                    DEFAULT: '#667eea',
                    dark: '#764ba2'
                },
                'adab-pink': {
                    light: '#f093fb',
                    DEFAULT: '#f5576c',
                    dark: '#f5576c'
                }
            },
            fontFamily: {
                'poppins': ['Poppins', 'sans-serif']
            }
        }
    }
}
```

**Cara Menggunakan:**
```html
<!-- Background -->
<div class="bg-adab-purple"></div>
<div class="bg-adab-pink"></div>

<!-- Text -->
<div class="text-adab-purple"></div>

<!-- Border -->
<div class="border-adab-purple"></div>
```

---

## 🧱 Struktur Halaman

```
┌─────────────────────────────────────────────────────────┐
│ 1. NAVBAR STICKY (Tailwind Utilities)                  │
│    - Logo AdabKita                                      │
│    - Menu: Kelas Saya, Kategori, Sertifikat            │
│    - User dropdown                                      │
│    - Mobile hamburger menu                              │
├─────────────────────────────────────────────────────────┤
│ 2. HERO BANNER (Gradient Pink)                          │
│    - Grid 2 kolom (text + illustration)                │
│    - Decorative circles (absolute positioning)         │
│    - 2 CTA buttons                                      │
│    - Emoji illustration                                 │
├─────────────────────────────────────────────────────────┤
│ 3. STATISTIK MINI (Grid 4 Cards)                        │
│    - Gradient background per card                       │
│    - Hover transform -translate-y                       │
│    - Text gradient untuk angka                          │
├─────────────────────────────────────────────────────────┤
│ 4. KELAS SAYA (Horizontal Scroll)                       │
│    - 5 course cards                                     │
│    - Absolute badge positioning                         │
│    - Custom scrollbar styling                           │
│    - Hover -translate-y-2                               │
├─────────────────────────────────────────────────────────┤
│ 5. KATEGORI (Grid Responsive)                           │
│    - Grid 2-3-4 cols (mobile-tablet-desktop)           │
│    - Gradient backgrounds                               │
│    - Border hover effect                                │
├─────────────────────────────────────────────────────────┤
│ 6. SERTIFIKAT (Grid 2 Cols)                             │
│    - Gradient yellow-orange                             │
│    - Flex layout (icon + content)                       │
│    - Hover scale-105                                    │
├─────────────────────────────────────────────────────────┤
│ 7. FOOTER (Dark Background)                             │
│    - Centered text                                      │
│    - Copyright info                                     │
└─────────────────────────────────────────────────────────┘
```

---

## 🎨 Komponen Utama & Class Tailwind

### 1. Navbar Sticky

```html
<nav class="bg-adab-gradient-purple sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a class="flex items-center space-x-2 text-white font-bold text-xl">
                <span class="text-2xl">📚</span>
                <span>AdabKita</span>
            </a>

            <!-- Menu Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a class="text-white/90 hover:text-white px-4 py-2 rounded-lg hover:bg-white/20 transition">
                    📖 Kelas Saya
                </a>
            </div>

            <!-- User Dropdown -->
            <button class="bg-white text-adab-purple px-4 py-2 rounded-full">
                👤 Username
            </button>
        </div>
    </div>
</nav>
```

**Key Classes:**
- `sticky top-0 z-50` - Navbar tetap di atas
- `bg-adab-gradient-purple` - Custom gradient (defined in style)
- `flex items-center justify-between` - Flexbox layout
- `hidden md:flex` - Responsive visibility
- `hover:bg-white/20` - Hover effect dengan opacity
- `transition` - Smooth transitions

---

### 2. Hero Banner

```html
<section class="bg-adab-gradient-pink relative overflow-hidden">
    <!-- Decorative Circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>

    <div class="container mx-auto px-4 lg:px-8 py-16 lg:py-24 relative z-10">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Text Content -->
            <div class="text-white animate-fade-in-up">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                    Pelajari Adab dengan Cara<br>
                    <span class="text-yellow-200">Menyenangkan & Interaktif</span> 🎓
                </h1>
                <p class="text-lg lg:text-xl text-white/90 mb-8">...</p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a class="bg-white text-adab-pink px-8 py-3 rounded-full hover:scale-105 transition transform shadow-lg">
                        ▶️ Mulai Belajar Sekarang
                    </a>
                    <a class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-adab-pink transition">
                        🗂️ Lihat Kategori
                    </a>
                </div>
            </div>

            <!-- Illustration -->
            <div class="hidden md:flex justify-center items-center">
                <div class="text-9xl animate-bounce">📚</div>
            </div>
        </div>
    </div>
</section>
```

**Key Classes:**
- `relative overflow-hidden` - Container untuk decorative elements
- `absolute top-0 right-0` - Absolute positioning untuk circles
- `grid md:grid-cols-2` - Responsive grid layout
- `text-4xl lg:text-5xl` - Responsive font sizes
- `rounded-full` - Fully rounded buttons
- `hover:scale-105 transform` - Scale effect on hover
- `animate-bounce` - Built-in Tailwind animation

---

### 3. Statistik Mini Cards

```html
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
        <div class="text-3xl font-bold text-adab-gradient mb-2">8/12</div>
        <div class="text-gray-600 text-sm font-medium">Materi Selesai</div>
    </div>
</div>
```

**Key Classes:**
- `grid grid-cols-2 lg:grid-cols-4` - Responsive grid (2 cols mobile, 4 desktop)
- `bg-gradient-to-br from-blue-50 to-blue-100` - Gradient background
- `rounded-xl` - Large border radius
- `hover:-translate-y-1 transform` - Hover translate up
- `text-adab-gradient` - Custom gradient text (CSS class)

---

### 4. Course Cards (Horizontal Scroll)

```html
<div class="overflow-x-auto custom-scrollbar pb-4 -mx-4 px-4">
    <div class="flex gap-6 min-w-max">
        <!-- Card -->
        <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
            <!-- Badge -->
            <div class="absolute top-4 left-4 z-10">
                <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Sedang Dipelajari
                </span>
            </div>

            <!-- Image/Icon -->
            <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                🍽️
            </div>

            <!-- Content -->
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-3">Adab Makan dan Minum</h3>

                <!-- Meta Info -->
                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                    <span>⏱️ 5 menit</span>
                    <span>📄 8 materi</span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Progress</span>
                        <span class="font-semibold text-adab-purple">75%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>

                <!-- Button -->
                <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                    ▶️ Lanjutkan Belajar
                </button>
            </div>
        </div>
    </div>
</div>
```

**Key Classes:**
- `overflow-x-auto` - Horizontal scrolling
- `flex gap-6 min-w-max` - Flex layout dengan gap
- `w-80` - Fixed width 320px
- `rounded-2xl` - Extra large border radius
- `absolute top-4 left-4 z-10` - Badge positioning
- `hover:-translate-y-2` - Lift effect on hover
- `h-48` - Height 192px (12rem)
- `text-8xl` - Extra large emoji icon

---

### 5. Kategori Grid

```html
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <a class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
        <div class="text-6xl mb-4">🥣</div>
        <h3 class="font-bold text-gray-800 mb-2">Adab Makan</h3>
        <p class="text-sm text-gray-600">8 materi tersedia</p>
    </a>
</div>
```

**Key Classes:**
- `grid-cols-2 md:grid-cols-3 lg:grid-cols-4` - Responsive columns
- `bg-gradient-to-br` - Gradient direction (bottom-right)
- `border-2 border-transparent hover:border-adab-purple` - Border on hover
- `text-center` - Centered text
- `text-6xl` - Large emoji

---

### 6. Sertifikat Cards

```html
<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-gradient-to-r from-yellow-100 to-orange-100 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center gap-4">
        <div class="bg-white rounded-xl p-4 text-4xl">🏆</div>
        <div class="flex-1">
            <h3 class="font-bold text-gray-800 mb-1">Sertifikat Adab...</h3>
            <p class="text-sm text-gray-600 mb-3">📅 Selesai: 15 Januari 2025</p>
            <button class="bg-white text-orange-600 px-4 py-2 rounded-lg hover:bg-orange-600 hover:text-white transition">
                📥 Unduh PDF
            </button>
        </div>
    </div>
</div>
```

**Key Classes:**
- `bg-gradient-to-r` - Horizontal gradient
- `flex items-center gap-4` - Flex layout
- `flex-1` - Flex grow
- `hover:scale-105` - Scale on hover

---

## 🎬 Animasi & Transisi

### Custom Animations (Defined in `<style>`)

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

**Usage:**
```html
<div class="animate-fade-in-up">...</div>
<div class="animate-fade-in-up-delay-1">...</div>
<div class="animate-fade-in-up-delay-2">...</div>
```

### Tailwind Built-in Transitions

```html
<!-- Transition all properties -->
<div class="transition">...</div>

<!-- Transition with transform -->
<div class="transition transform hover:scale-105">...</div>

<!-- Hover effects -->
<div class="hover:shadow-lg">...</div>
<div class="hover:-translate-y-2">...</div>
<div class="hover:bg-white/20">...</div>
```

---

## 📱 Responsive Design

### Breakpoints Tailwind

```
sm:  640px
md:  768px
lg:  1024px
xl:  1280px
2xl: 1536px
```

### Contoh Responsive Classes

```html
<!-- Text size -->
<h1 class="text-2xl md:text-4xl lg:text-5xl">...</h1>

<!-- Grid columns -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">...</div>

<!-- Padding -->
<div class="px-4 lg:px-8">...</div>

<!-- Visibility -->
<div class="hidden md:flex">...</div>  <!-- Hidden mobile, flex desktop -->
<div class="block md:hidden">...</div>  <!-- Visible mobile, hidden desktop -->

<!-- Flex direction -->
<div class="flex flex-col sm:flex-row">...</div>
```

---

## ✏️ Cara Mengubah Konten

### 1. Mengganti Warna Tema

**Lokasi:** `<script>` tag Tailwind config

```javascript
// Ganti warna di sini
colors: {
    'adab-purple': {
        light: '#667eea',    // Ganti dengan hex color baru
        DEFAULT: '#667eea',
        dark: '#764ba2'
    }
}
```

### 2. Mengubah Judul Hero

**Lokasi:** Hero Banner section

```html
<h1 class="text-4xl lg:text-5xl font-bold mb-4">
    Pelajari Adab dengan Cara<br>
    <span class="text-yellow-200">Menyenangkan & Interaktif</span> 🎓
</h1>
```

### 3. Menambah Course Card Baru

**Copy card template:**

```html
<div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
    <div class="absolute top-4 left-4 z-10">
        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
            Baru!
        </span>
    </div>
    <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
        😊 {{-- Ganti emoji --}}
    </div>
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-3">Judul Materi Baru</h3>
        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
            <span>⏱️ 5 menit</span>
            <span>📄 8 materi</span>
        </div>
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-2">
                <span>Progress</span>
                <span class="font-semibold text-adab-purple">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: 0%"></div>
            </div>
        </div>
        <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105">
            ▶️ Mulai Belajar
        </button>
    </div>
</div>
```

### 4. Menambah Kategori Baru

**Copy kategori template:**

```html
<a href="#" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
    <div class="text-6xl mb-4">🎯</div>
    <h3 class="font-bold text-gray-800 mb-2">Nama Kategori</h3>
    <p class="text-sm text-gray-600">10 materi tersedia</p>
</a>
```

---

## 🚀 Cara Menggunakan

### 1. Testing di Localhost

```bash
cd C:\xampp\htdocs\deeplearning
php artisan serve
```

Buka browser: `http://localhost:8000`

### 2. Mengganti Dashboard Lama

**Option 1:** Update route di `routes/web.php`

```php
Route::get('/siswa/dashboard', function() {
    return view('halaman_siswa.dashboard_v3_tailwind');
})->name('siswa.dashboard');
```

**Option 2:** Rename file

```bash
mv dashboard.blade.php dashboard_old.blade.php
mv dashboard_v3_tailwind.blade.php dashboard.blade.php
```

---

## 🔧 Integrasi dengan Backend

### Controller Example

```php
public function index()
{
    $user = Auth::user();

    $courses = Course::where('user_id', $user->id)
        ->with('category')
        ->get()
        ->map(function($course) {
            return [
                'title' => $course->title,
                'emoji' => $course->emoji ?? '📖',
                'duration' => $course->duration_minutes,
                'total_materials' => $course->materials_count,
                'progress' => $course->progress_percentage,
                'status' => $course->status, // 'new', 'in-progress', 'completed'
            ];
        });

    $certificates = Certificate::where('user_id', $user->id)->get();

    $stats = [
        'completed' => $courses->where('progress', 100)->count(),
        'total' => $courses->count(),
        'assignments' => Assignment::where('user_id', $user->id)->count(),
        'average_score' => Score::where('user_id', $user->id)->avg('score'),
    ];

    return view('halaman_siswa.dashboard_v3_tailwind', compact('courses', 'certificates', 'stats'));
}
```

### Update View dengan Data Dinamis

```blade
{{-- Stats --}}
<div class="text-3xl font-bold text-adab-gradient mb-2">
    {{ $stats['completed'] }}/{{ $stats['total'] }}
</div>

{{-- Course Cards Loop --}}
@foreach($courses as $course)
<div class="w-80 bg-white rounded-2xl shadow-lg...">
    <!-- Badge Status -->
    <div class="absolute top-4 left-4 z-10">
        <span class="
            @if($course['status'] == 'new') bg-red-500
            @elseif($course['status'] == 'in-progress') bg-blue-500
            @else bg-green-500
            @endif
            text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ ucfirst($course['status']) }}
        </span>
    </div>

    <!-- Icon -->
    <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
        {{ $course['emoji'] }}
    </div>

    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $course['title'] }}</h3>

        <!-- Meta -->
        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
            <span>⏱️ {{ $course['duration'] }} menit</span>
            <span>📄 {{ $course['total_materials'] }} materi</span>
        </div>

        <!-- Progress -->
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-2">
                <span>Progress</span>
                <span class="font-semibold text-adab-purple">{{ $course['progress'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: {{ $course['progress'] }}%"></div>
            </div>
        </div>

        <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg...">
            @if($course['progress'] == 100)
                ✅ Lihat Ulang
            @else
                ▶️ {{ $course['progress'] > 0 ? 'Lanjutkan' : 'Mulai' }} Belajar
            @endif
        </button>
    </div>
</div>
@endforeach
```

---

## 💡 Tips Tailwind

### 1. Gunakan Arbitrary Values

```html
<!-- Custom values -->
<div class="w-[350px]">...</div>
<div class="top-[13px]">...</div>
<div class="text-[#667eea]">...</div>
```

### 2. Group Hover

```html
<div class="group">
    <div class="group-hover:scale-110">...</div>
</div>
```

### 3. Dark Mode (Future)

```html
<div class="bg-white dark:bg-gray-800">...</div>
<div class="text-gray-900 dark:text-white">...</div>
```

### 4. Space & Divide Utilities

```html
<!-- Space between children -->
<div class="space-y-4">...</div>
<div class="space-x-6">...</div>

<!-- Divide between children -->
<div class="divide-y divide-gray-200">...</div>
```

### 5. Ring Utilities (Focus States)

```html
<input class="focus:ring-2 focus:ring-adab-purple">
<button class="focus:ring-4 focus:ring-offset-2">...</button>
```

---

## 📊 Perbandingan v2 vs v3

| Feature | v2 (Bootstrap) | v3 (TailwindCSS) |
|---------|----------------|------------------|
| CSS Size | ~200KB | ~50KB (with purge) |
| Customization | CSS overrides | Utility classes |
| Responsive | Bootstrap grid | Tailwind responsive |
| Components | Pre-built | Build from utilities |
| Learning | Easier | Steeper curve |
| Flexibility | Good | Excellent |
| Maintenance | Custom CSS | Inline utilities |

---

## ✅ Checklist Testing

### Desktop (> 1024px)
- [ ] Navbar horizontal, semua menu terlihat
- [ ] Hero banner grid 2 kolom
- [ ] Course cards horizontal scroll smooth
- [ ] Kategori grid 4 kolom
- [ ] Sertifikat grid 2 kolom

### Tablet (768px - 1024px)
- [ ] Navbar compact tapi horizontal
- [ ] Kategori 3 kolom
- [ ] Stats 4 kolom tetap

### Mobile (< 768px)
- [ ] Navbar hamburger menu
- [ ] Hero text stack, buttons full width
- [ ] Stats 2 kolom
- [ ] Kategori 2 kolom
- [ ] Course cards scroll smooth

---

## 🎯 Next Steps

1. ⏳ Testing cross-browser (Chrome, Firefox, Safari)
2. ⏳ Integrasi backend data
3. ⏳ Optimize dengan PurgeCSS
4. 💡 Add dark mode support
5. 💡 Add loading skeletons
6. 💡 PWA support

---

**© 2025 AdabKita - GASPUL | Dashboard v3.0 TailwindCSS Documentation**
