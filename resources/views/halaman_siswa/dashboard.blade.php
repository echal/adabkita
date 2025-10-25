{{--
    =====================================================
    DASHBOARD SISWA v3.0 - TAILWIND REDESIGN
    =====================================================
    Phase 0.3 - UI/UX Redesign dengan TailwindCSS
    Inspirasi: IEEE Courses dengan identitas AdabKita

    Halaman dashboard untuk Siswa dengan tampilan modern:
    - Hero banner dengan CTA (gradasi ungu-pink)
    - Navbar horizontal sticky
    - Course cards dengan grid/slider
    - Kategori Adab Islami (8 kategori)
    - Section sertifikat

    URL: /siswa/dashboard
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdabKita - Dashboard Siswa</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Custom Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Warna khas AdabKita (ungu-pink gradient)
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
    </script>

    <style>
        /* Custom gradient backgrounds */
        .bg-adab-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-adab-gradient-pink {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .text-adab-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom scrollbar untuk horizontal scroll */
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #667eea;
        }

        /* Animation */
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

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-up-delay-1 {
            animation: fadeInUp 1s ease-out;
        }

        .animate-fade-in-up-delay-2 {
            animation: fadeInUp 1.2s ease-out;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">

    {{-- =====================================================
        NAVBAR STICKY (Horizontal - Inspirasi IEEE Courses)
        ===================================================== --}}
    <nav class="bg-adab-gradient-purple sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- LOGO / BRAND NAME --}}
                <a href="{{ route('siswa.dashboard') }}" class="flex items-center space-x-2 text-white font-bold text-xl hover:opacity-90 transition">
                    <span class="text-2xl">📚</span>
                    <span>AdabKita</span>
                </a>

                {{-- MENU NAVIGATION (Desktop) --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#kelas-saya" class="nav-link-hover text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        <span class="mr-2">📖</span> Kelas Saya
                    </a>
                    <a href="#kategori" class="nav-link-hover text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        <span class="mr-2">🗂️</span> Kategori
                    </a>
                    <a href="#sertifikat" class="nav-link-hover text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        <span class="mr-2">🏆</span> Sertifikat Saya
                    </a>
                </div>

                {{-- USER PROFILE DROPDOWN --}}
                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 bg-white text-adab-purple-light font-semibold px-4 py-2 rounded-full hover:bg-white/90 transition">
                        <span class="text-lg">👤</span>
                        <span class="hidden md:inline">{{ Auth::user()->name ?? 'Siswa' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2">
                        <a href="#profil" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <span class="mr-2">👤</span> Profil Saya
                        </a>
                        <a href="#pengaturan" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <span class="mr-2">⚙️</span> Pengaturan
                        </a>
                        <hr class="my-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                <span class="mr-2">🚪</span> Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- MOBILE MENU BUTTON --}}
                <button onclick="toggleMobileMenu()" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- MOBILE MENU --}}
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <a href="#kelas-saya" class="block text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                    <span class="mr-2">📖</span> Kelas Saya
                </a>
                <a href="#kategori" class="block text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                    <span class="mr-2">🗂️</span> Kategori
                </a>
                <a href="#sertifikat" class="block text-white/90 hover:text-white font-medium px-4 py-2 rounded-lg hover:bg-white/20 transition">
                    <span class="mr-2">🏆</span> Sertifikat Saya
                </a>
            </div>
        </div>
    </nav>

    {{-- =====================================================
        HERO BANNER SECTION
        Tagline dan ajakan untuk memulai belajar
        ===================================================== --}}
    <section class="bg-adab-gradient-pink relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full -ml-48 -mb-48"></div>

        <div class="container mx-auto px-4 lg:px-8 py-16 lg:py-24 relative z-10">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                {{-- TEXT CONTENT --}}
                <div class="text-white animate-fade-in-up">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                        Pelajari Adab dengan Cara<br>
                        <span class="text-yellow-200">Menyenangkan & Interaktif</span> 🎓
                    </h1>
                    <p class="text-lg lg:text-xl text-white/90 mb-8">
                        Platform pembelajaran Deep Learning untuk Adab Islami di MTsN.<br>
                        Belajar adab sehari-hari dengan metode modern yang menyenangkan!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up-delay-1">
                        <a href="#kelas-saya" class="bg-white text-adab-pink font-semibold px-8 py-3 rounded-full hover:bg-white/90 hover:scale-105 transition transform shadow-lg text-center">
                            <span class="mr-2">▶️</span> Mulai Belajar Sekarang
                        </a>
                        <a href="#kategori" class="bg-transparent border-2 border-white text-white font-semibold px-8 py-3 rounded-full hover:bg-white hover:text-adab-pink transition transform hover:scale-105 text-center">
                            <span class="mr-2">🗂️</span> Lihat Kategori
                        </a>
                    </div>
                </div>

                {{-- ILLUSTRATION (Placeholder - bisa diganti dengan gambar) --}}
                <div class="hidden md:flex justify-center items-center animate-fade-in-up-delay-2">
                    <div class="text-9xl animate-bounce">
                        📚
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
        [NEW FEATURE] SEARCH SECTION
        Pencarian pelajaran dengan real-time search
        ===================================================== --}}
    <section class="bg-white py-6 sticky top-16 z-40 shadow-md">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('siswa.dashboard') }}" method="GET" id="searchForm">
                    <div class="relative">
                        {{-- Search Icon --}}
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        {{-- Search Input --}}
                        <input
                            type="text"
                            id="searchPelajaran"
                            name="search"
                            placeholder="Cari pelajaran adab..."
                            value="{{ request('search') }}"
                            class="w-full pl-12 pr-12 py-3 border-2 border-gray-300 rounded-full focus:outline-none focus:border-adab-purple transition"
                            autocomplete="off"
                        >

                        {{-- Clear Button (jika ada search) --}}
                        @if(request('search'))
                            <button
                                type="button"
                                onclick="clearSearch()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- Search Results Count --}}
                    @if(request('search'))
                        <div class="mt-3 text-sm text-gray-600 text-center">
                            <span class="font-semibold">Hasil pencarian untuk:</span> "{{ request('search') }}"
                            @if($pelajaranList->count() > 0)
                                <span class="ml-2 text-adab-purple font-semibold">({{ $pelajaranList->count() }} pelajaran ditemukan)</span>
                            @else
                                <span class="ml-2 text-red-500 font-semibold">(Tidak ada hasil)</span>
                            @endif
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>

    {{-- =====================================================
        SECTION: STATISTIK MINI (Quick Overview)
        Data dinamis dari database
        ===================================================== --}}
    <section class="bg-white shadow-md">
        <div class="container mx-auto px-4 lg:px-8 py-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Stat 1: Materi Selesai --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">{{ $pelajaranSelesai }}/{{ $totalPelajaran }}</div>
                    <div class="text-gray-600 text-sm font-medium">Materi Selesai</div>
                </div>

                {{-- Stat 2: Tugas --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">{{ $tugasDikumpulkan }}</div>
                    <div class="text-gray-600 text-sm font-medium">Tugas Dikumpulkan</div>
                </div>

                {{-- Stat 3: Rata-rata Nilai --}}
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">{{ $rataRataNilai }}</div>
                    <div class="text-gray-600 text-sm font-medium">Rata-rata Nilai</div>
                </div>

                {{-- Stat 4: Peringkat --}}
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">
                        @if($peringkat > 0)
                            #{{ $peringkat }}
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-gray-600 text-sm font-medium">Peringkat Kelas</div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
        SECTION: KELAS SAYA (Course Programs)
        Horizontal scroll cards seperti IEEE Courses
        ===================================================== --}}
    <section id="kelas-saya" class="py-16 bg-gradient-to-b from-purple-50 to-white">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- SECTION HEADER --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    📖 Kelas Saya
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Lanjutkan pembelajaran Adab Islami yang sedang kamu ikuti
                </p>
            </div>

            {{-- HORIZONTAL SCROLL CONTAINER - Data Dinamis dari Database --}}
            <div class="overflow-x-auto custom-scrollbar pb-4 -mx-4 px-4">
                <div class="flex gap-6 min-w-max">

                    @forelse($pelajaranList as $pelajaran)
                        {{-- COURSE CARD - Dinamis dari Database --}}
                        <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                            {{-- Badge Status --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="{{ $pelajaran['badge_class'] }} text-white text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $pelajaran['badge'] }}
                                </span>
                            </div>

                            {{-- Course Image/Icon --}}
                            <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                                {{ $pelajaran['icon'] }}
                            </div>

                            {{-- Course Content --}}
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $pelajaran['judul'] }}</h3>

                                {{-- Meta Info --}}
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $pelajaran['durasi'] }} menit
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                        {{ $pelajaran['jumlah_materi'] }} materi
                                    </span>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-semibold {{ $pelajaran['progress'] >= 100 ? 'text-green-600' : 'text-adab-purple' }}">
                                            {{ $pelajaran['progress'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $pelajaran['progress'] >= 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-adab-gradient-purple' }} h-2 rounded-full"
                                             style="width: {{ $pelajaran['progress'] }}%"></div>
                                    </div>
                                </div>

                                {{-- [NEW FEATURE] Button Section dengan Sertifikat --}}
                                <div class="space-y-2">
                                    {{-- Button Lanjutkan/Mulai Belajar --}}
                                    <a href="{{ route('siswa.lesson-interaktif.mulai', $pelajaran['id']) }}"
                                       class="block w-full {{ $pelajaran['progress'] >= 100 ? 'bg-green-500' : 'bg-adab-gradient-purple' }} text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105 text-center">
                                        @if($pelajaran['progress'] >= 100)
                                            ✅ Lihat Ulang
                                        @elseif($pelajaran['progress'] > 0)
                                            ▶️ Lanjutkan Belajar
                                        @else
                                            ▶️ Mulai Belajar
                                        @endif
                                    </a>

                                    {{-- [NEW FEATURE] Button Download Sertifikat (hanya untuk yang selesai dengan nilai >= 80%) --}}
                                    @if($pelajaran['dapat_sertifikat'] ?? false)
                                        <a href="{{ route('siswa.sertifikat.download', $pelajaran['progress_id']) }}"
                                           class="block w-full bg-gradient-to-r from-yellow-400 to-yellow-600 text-white font-semibold py-3 rounded-lg hover:from-yellow-500 hover:to-yellow-700 hover:shadow-lg transition transform hover:scale-105 text-center"
                                           title="Download Sertifikat PDF">
                                            🏆 Unduh Sertifikat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Empty State jika belum ada pelajaran --}}
                        <div class="w-full text-center py-12">
                            <div class="text-8xl mb-4 opacity-30">📚</div>
                            <p class="text-gray-500">Belum ada pelajaran tersedia. Silakan hubungi guru Anda!</p>
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- [NEW FEATURE] PAGINATION LINKS --}}
            @if($pelajaranPaginated->hasPages())
                <div class="mt-12">
                    <nav class="flex justify-center items-center space-x-2">
                        {{-- Previous Button --}}
                        @if ($pelajaranPaginated->onFirstPage())
                            <span class="px-4 py-2 rounded-lg border-2 border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $pelajaranPaginated->previousPageUrl() }}" class="px-4 py-2 rounded-lg border-2 border-adab-purple bg-white text-adab-purple font-semibold hover:bg-adab-purple hover:text-white transition transform hover:scale-105 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sebelumnya
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        <div class="flex space-x-1">
                            @foreach ($pelajaranPaginated->links()->elements[0] as $page => $url)
                                @if ($page == $pelajaranPaginated->currentPage())
                                    <span class="px-4 py-2 rounded-lg bg-adab-gradient-purple text-white font-bold shadow-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 rounded-lg border-2 border-gray-300 bg-white text-gray-700 font-semibold hover:border-adab-purple hover:bg-adab-purple hover:text-white transition transform hover:scale-105">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        {{-- Next Button --}}
                        @if ($pelajaranPaginated->hasMorePages())
                            <a href="{{ $pelajaranPaginated->nextPageUrl() }}" class="px-4 py-2 rounded-lg border-2 border-adab-purple bg-white text-adab-purple font-semibold hover:bg-adab-purple hover:text-white transition transform hover:scale-105 flex items-center">
                                Selanjutnya
                                <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-lg border-2 border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed flex items-center">
                                Selanjutnya
                                <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        @endif
                    </nav>

                    {{-- Pagination Info --}}
                    <div class="text-center mt-4 text-sm text-gray-600">
                        Menampilkan {{ $pelajaranPaginated->firstItem() }} - {{ $pelajaranPaginated->lastItem() }} dari {{ $pelajaranPaginated->total() }} pelajaran
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- =====================================================
        [NEW FEATURE] GRAFIK PROGRESS MINGGUAN
        Visualisasi progress belajar dengan Chart.js
        ===================================================== --}}
    <section class="py-16 bg-gradient-to-b from-white to-purple-50">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- SECTION HEADER --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    📊 Progress Belajar Mingguan
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Lihat perkembangan nilai rata-rata kamu selama 8 minggu terakhir
                </p>
            </div>

            {{-- CHART CONTAINER --}}
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
                <canvas id="progressChart" class="w-full" style="max-height: 400px;"></canvas>

                {{-- Chart Legend Info --}}
                <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-adab-gradient-purple"></div>
                        <span>Rata-rata Nilai</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-green-500"></div>
                        <span>Target: 80%</span>
                    </div>
                </div>
            </div>

            {{-- Additional Stats --}}
            <div class="max-w-4xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-6 shadow-md text-center">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">
                        {{ max($progressData['values']) }}%
                    </div>
                    <div class="text-sm text-gray-600">Nilai Tertinggi</div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md text-center">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">
                        {{ $rataRataNilai }}%
                    </div>
                    <div class="text-sm text-gray-600">Rata-rata Keseluruhan</div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md text-center">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">
                        {{ $pelajaranSelesai }}
                    </div>
                    <div class="text-sm text-gray-600">Pelajaran Selesai</div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
        SECTION: KATEGORI ADAB ISLAMI
        Grid layout kategori pembelajaran
        ===================================================== --}}
    <section id="kategori" class="py-16 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- SECTION HEADER --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    🗂️ Kategori Pembelajaran
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pilih kategori Adab Islami yang ingin kamu pelajari
                </p>
            </div>

            {{-- GRID KATEGORI - Data dari Controller --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @foreach($kategoriList as $kategori)
                    <a href="#kelas-saya" class="bg-gradient-to-br {{ $kategori['warna'] }} rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                        <div class="text-6xl mb-4">{{ $kategori['icon'] }}</div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ $kategori['nama'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $kategori['jumlah'] }} materi tersedia</p>
                    </a>
                @endforeach

            </div>
        </div>
    </section>

    {{-- =====================================================
        [NEW FEATURE] Materi Interaktif Section START
        Section untuk menampilkan materi pembelajaran interaktif
        dengan format grid card responsif
        ===================================================== --}}
    <section id="materi-interaktif" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- SECTION HEADER --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    🎯 Materi Interaktif
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-6">
                    Materi pembelajaran interaktif dengan video, teks, dan kuis untuk pemahaman lebih dalam
                </p>

                {{-- [NEW FEATURE] Tombol Akses Kategori Pembelajaran --}}
                <div class="flex justify-center gap-4 mt-6">
                    <a href="{{ route('siswa.kategori.index') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                        <span>📚 Jelajahi Kategori Pembelajaran</span>
                    </a>
                </div>
            </div>

            {{-- {{-- [NEW FEATURE] Integrasi Materi Interaktif --}} GRID MATERI INTERAKTIF - Menggunakan Component --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($materiList ?? [] as $materi)
                    {{-- [NEW FEATURE] Materi Card Component --}}
                    <x-materi-card :materi="$materi" />

                @empty
                    {{-- [NEW FEATURE] Empty State untuk Materi Interaktif --}}
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <div class="text-8xl mb-6 opacity-20">🎯</div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                                Belum Ada Materi Interaktif
                            </h3>
                            <p class="text-gray-600 max-w-md mx-auto mb-6">
                                Belum ada materi interaktif untuk kategori ini. Guru Anda akan segera menambahkan materi pembelajaran yang menarik!
                            </p>
                            <div class="flex justify-center gap-4">
                                <a href="#kelas-saya" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-400 text-white font-semibold px-6 py-3 rounded-lg hover:from-purple-600 hover:to-pink-500 transition transform hover:scale-105">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Lihat Kelas Reguler
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>

            {{-- [NEW FEATURE] Filter & Sort Options (Optional - bisa diaktifkan nanti) --}}
            @if(isset($materiList) && $materiList->count() > 0)
                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-purple-600">{{ $materiList->count() }}</span> materi interaktif
                    </p>
                </div>
            @endif
        </div>
    </section>
    {{-- [NEW FEATURE] Materi Interaktif Section END --}}

    {{-- =====================================================
        SECTION: SERTIFIKAT SAYA
        Menampilkan sertifikat yang sudah didapatkan
        ===================================================== --}}
    <section id="sertifikat" class="py-16 bg-gradient-to-b from-yellow-50 to-white">
        <div class="container mx-auto px-4 lg:px-8">
            {{-- SECTION HEADER --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                    🏆 Sertifikat Saya
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Sertifikat yang telah kamu dapatkan setelah menyelesaikan pembelajaran
                </p>
            </div>

            {{-- GRID SERTIFIKAT - Data Dinamis dari Database --}}
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">

                @forelse($sertifikatList as $sertifikat)
                    {{-- Certificate Card - Dinamis --}}
                    <div class="bg-gradient-to-r from-yellow-100 to-orange-100 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center gap-4">
                        <div class="bg-white rounded-xl p-4 text-4xl">
                            🏆
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 mb-1">{{ $sertifikat['judul'] }}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="mr-2">📅</span> Selesai: {{ $sertifikat['tanggal_selesai'] }}
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                <span class="mr-2">⭐</span> Nilai: {{ $sertifikat['nilai'] }}%
                            </p>
                            {{-- [ACTIVATED] Download Button dengan route ke SertifikatController --}}
                            <a href="{{ route('siswa.sertifikat.download', $sertifikat['id']) }}"
                               class="inline-block bg-white text-orange-600 font-semibold px-4 py-2 rounded-lg hover:bg-orange-600 hover:text-white transition text-sm"
                               title="Download Sertifikat PDF">
                                📥 Unduh PDF
                            </a>
                        </div>
                    </div>
                @empty
                    {{-- Empty State jika belum ada sertifikat --}}
                    <div class="col-span-full text-center py-12">
                        <div class="text-8xl mb-4 opacity-30">🏆</div>
                        <p class="text-gray-500">Belum ada sertifikat. Selesaikan pembelajaran untuk mendapatkan sertifikat!</p>
                        <p class="text-sm text-gray-400 mt-2">(Minimal nilai 80% untuk mendapat sertifikat)</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- =====================================================
        FOOTER SECTION
        ===================================================== --}}
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 lg:px-8 text-center">
            <p class="mb-2">&copy; {{ date('Y') }} <span class="font-bold">AdabKita</span> - Platform Pembelajaran Adab Islami</p>
            <p class="text-sm text-gray-400">MTsN | Powered by Deep Learning Technology</p>
        </div>
    </footer>

    {{-- =====================================================
        [NEW FEATURE] Chart.js CDN
        ===================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- =====================================================
        JAVASCRIPT
        ===================================================== --}}
    <script>
        // Toggle Dropdown User Profile
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Toggle Mobile Menu
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdown');
            const button = event.target.closest('button');

            if (!button) {
                dropdown.classList.add('hidden');
            }
        });

        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Close mobile menu after clicking
                    const mobileMenu = document.getElementById('mobileMenu');
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Scroll spy - highlight active menu
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link-hover');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('bg-white/20');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('bg-white/20');
                }
            });
        });

        // =====================================================
        // [NEW FEATURE] SEARCH FUNCTIONALITY
        // =====================================================

        // Clear Search Function
        function clearSearch() {
            window.location.href = '{{ route("siswa.dashboard") }}';
        }

        // Auto-submit search form on Enter
        const searchInput = document.getElementById('searchPelajaran');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('searchForm').submit();
                }
            });
        }

        // =====================================================
        // [NEW FEATURE] PROGRESS CHART WITH CHART.JS
        // =====================================================
        const ctx = document.getElementById('progressChart');
        if (ctx) {
            // Data dari controller
            const chartLabels = @json($progressData['labels']);
            const chartValues = @json($progressData['values']);

            const progressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Rata-rata Nilai (%)',
                        data: chartValues,
                        borderColor: 'rgb(102, 126, 234)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        tension: 0.4, // Curved line
                        fill: true,
                        pointBackgroundColor: 'rgb(102, 126, 234)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: 'rgb(118, 75, 162)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    family: 'Poppins',
                                    weight: '600'
                                },
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 14,
                                family: 'Poppins',
                                weight: '600'
                            },
                            bodyFont: {
                                size: 13,
                                family: 'Poppins'
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Nilai: ' + context.parsed.y + '%';
                                },
                                afterLabel: function(context) {
                                    if (context.parsed.y >= 80) {
                                        return '✓ Target tercapai!';
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                },
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                },
                                stepSize: 20
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        }
    </script>

</body>
</html>
