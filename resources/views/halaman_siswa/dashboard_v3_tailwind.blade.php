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
        SECTION: STATISTIK MINI (Quick Overview)
        ===================================================== --}}
    <section class="bg-white shadow-md">
        <div class="container mx-auto px-4 lg:px-8 py-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Stat 1: Materi Selesai --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">8/12</div>
                    <div class="text-gray-600 text-sm font-medium">Materi Selesai</div>
                </div>

                {{-- Stat 2: Tugas --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">15</div>
                    <div class="text-gray-600 text-sm font-medium">Tugas Dikumpulkan</div>
                </div>

                {{-- Stat 3: Rata-rata Nilai --}}
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">88</div>
                    <div class="text-gray-600 text-sm font-medium">Rata-rata Nilai</div>
                </div>

                {{-- Stat 4: Peringkat --}}
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-adab-gradient mb-2">#5</div>
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

            {{-- HORIZONTAL SCROLL CONTAINER --}}
            <div class="overflow-x-auto custom-scrollbar pb-4 -mx-4 px-4">
                <div class="flex gap-6 min-w-max">

                    {{-- COURSE CARD 1: Adab Makan --}}
                    <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                        {{-- Badge Status --}}
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                Sedang Dipelajari
                            </span>
                        </div>

                        {{-- Course Image/Icon --}}
                        <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                            🍽️
                        </div>

                        {{-- Course Content --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Adab Makan dan Minum</h3>

                            {{-- Meta Info --}}
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    5 menit
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                    8 materi
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-adab-purple">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>

                            {{-- Button --}}
                            <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                                ▶️ Lanjutkan Belajar
                            </button>
                        </div>
                    </div>

                    {{-- COURSE CARD 2: Adab di Masjid --}}
                    <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                Baru!
                            </span>
                        </div>
                        <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                            🕌
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Adab di Masjid</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span>⏱️ 7 menit</span>
                                <span>📄 10 materi</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progress</span>
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

                    {{-- COURSE CARD 3: Adab kepada Orang Tua --}}
                    <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                Selesai
                            </span>
                        </div>
                        <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                            👪
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Adab kepada Orang Tua</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span>⏱️ 6 menit</span>
                                <span>📄 9 materi</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-green-600">100%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                            <button class="w-full bg-green-500 text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                                ✅ Lihat Ulang
                            </button>
                        </div>
                    </div>

                    {{-- COURSE CARD 4: Adab Belajar --}}
                    <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                Sedang Dipelajari
                            </span>
                        </div>
                        <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                            📚
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Adab Belajar dan Menuntut Ilmu</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span>⏱️ 8 menit</span>
                                <span>📄 12 materi</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-semibold text-adab-purple">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-adab-gradient-purple h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                            <button class="w-full bg-adab-gradient-purple text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105">
                                ▶️ Lanjutkan Belajar
                            </button>
                        </div>
                    </div>

                    {{-- COURSE CARD 5: Adab Berpakaian --}}
                    <div class="w-80 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                Baru!
                            </span>
                        </div>
                        <div class="bg-adab-gradient-purple h-48 flex items-center justify-center text-8xl">
                            👔
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Adab Berpakaian dalam Islam</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span>⏱️ 5 menit</span>
                                <span>📄 7 materi</span>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progress</span>
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

                </div>
            </div>

            {{-- Button Lihat Semua --}}
            <div class="text-center mt-8">
                <a href="#" class="inline-block bg-white text-adab-purple font-semibold px-8 py-3 rounded-full border-2 border-adab-purple hover:bg-adab-purple hover:text-white transition transform hover:scale-105">
                    Lihat Semua Kelas →
                </a>
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

            {{-- GRID KATEGORI --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                {{-- Kategori 1: Adab Makan --}}
                <a href="#" class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">🥣</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Makan</h3>
                    <p class="text-sm text-gray-600">8 materi tersedia</p>
                </a>

                {{-- Kategori 2: Adab Minum --}}
                <a href="#" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">💧</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Minum</h3>
                    <p class="text-sm text-gray-600">5 materi tersedia</p>
                </a>

                {{-- Kategori 3: Adab di Masjid --}}
                <a href="#" class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">🕌</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab di Masjid</h3>
                    <p class="text-sm text-gray-600">10 materi tersedia</p>
                </a>

                {{-- Kategori 4: Adab kepada Orang Tua --}}
                <a href="#" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">👪</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab kepada Orang Tua</h3>
                    <p class="text-sm text-gray-600">9 materi tersedia</p>
                </a>

                {{-- Kategori 5: Adab Belajar --}}
                <a href="#" class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Belajar</h3>
                    <p class="text-sm text-gray-600">12 materi tersedia</p>
                </a>

                {{-- Kategori 6: Adab Bergaul --}}
                <a href="#" class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">🤝</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Bergaul</h3>
                    <p class="text-sm text-gray-600">7 materi tersedia</p>
                </a>

                {{-- Kategori 7: Adab Berpakaian --}}
                <a href="#" class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">👔</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Berpakaian</h3>
                    <p class="text-sm text-gray-600">7 materi tersedia</p>
                </a>

                {{-- Kategori 8: Adab Tidur --}}
                <a href="#" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 text-center hover:shadow-xl transition transform hover:-translate-y-2 border-2 border-transparent hover:border-adab-purple">
                    <div class="text-6xl mb-4">🌙</div>
                    <h3 class="font-bold text-gray-800 mb-2">Adab Tidur</h3>
                    <p class="text-sm text-gray-600">6 materi tersedia</p>
                </a>

            </div>
        </div>
    </section>

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

            {{-- GRID SERTIFIKAT --}}
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">

                {{-- Certificate Card 1 --}}
                <div class="bg-gradient-to-r from-yellow-100 to-orange-100 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center gap-4">
                    <div class="bg-white rounded-xl p-4 text-4xl">
                        🏆
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 mb-1">Sertifikat Adab kepada Orang Tua</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="mr-2">📅</span> Selesai: 15 Januari 2025
                        </p>
                        <button class="bg-white text-orange-600 font-semibold px-4 py-2 rounded-lg hover:bg-orange-600 hover:text-white transition text-sm">
                            📥 Unduh PDF
                        </button>
                    </div>
                </div>

                {{-- Certificate Card 2 --}}
                <div class="bg-gradient-to-r from-yellow-100 to-orange-100 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center gap-4">
                    <div class="bg-white rounded-xl p-4 text-4xl">
                        🏆
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 mb-1">Sertifikat Adab Makan dan Minum</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            <span class="mr-2">📅</span> Selesai: 10 Januari 2025
                        </p>
                        <button class="bg-white text-orange-600 font-semibold px-4 py-2 rounded-lg hover:bg-orange-600 hover:text-white transition text-sm">
                            📥 Unduh PDF
                        </button>
                    </div>
                </div>

                {{-- Empty State (jika belum ada sertifikat - uncomment jika diperlukan) --}}
                {{--
                <div class="col-span-full text-center py-12">
                    <div class="text-8xl mb-4 opacity-30">🏆</div>
                    <p class="text-gray-500">Belum ada sertifikat. Selesaikan pembelajaran untuk mendapatkan sertifikat!</p>
                </div>
                --}}

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
    </script>

</body>
</html>
