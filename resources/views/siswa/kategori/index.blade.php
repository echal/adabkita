{{--
    =====================================================
    [NEW FEATURE] Halaman Kategori Pembelajaran - Level 1
    =====================================================
    Halaman untuk menampilkan semua kategori pembelajaran
    dalam bentuk grid card responsif dengan ikon dan warna berbeda.

    Route: /siswa/kategori-pembelajaran
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Pembelajaran - AdabKita</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts - Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
    </style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4 lg:px-8">

        {{-- [NEW FEATURE] Breadcrumb Navigation --}}
        <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-purple-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-800 font-semibold md:ml-2">Kategori Pembelajaran</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- [NEW FEATURE] Page Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4 animate-fade-in">
                📚 Kategori Pembelajaran
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih kategori pembelajaran yang ingin kamu pelajari. Setiap kategori berisi materi-materi menarik tentang adab dalam Islam.
            </p>
        </div>

        {{-- [NEW FEATURE] Alert Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                <p class="font-semibold">✅ {{ session('success') }}</p>
            </div>
        @endif

        {{-- [NEW FEATURE] Kategori Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">

            {{-- Kategori 1: Adab Berjalan --}}
            <a href="{{ route('siswa.kategori.show', 'adab-berjalan') }}"
               class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-400 to-cyan-500 p-8 relative">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        🚶
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-xs font-semibold">
                        8 Materi
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Adab Berjalan</h3>
                    <p class="text-sm text-gray-600 mb-4">Tata cara berjalan yang sopan dan beradab menurut ajaran Islam</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            45 siswa
                        </span>
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">
                            60% selesai
                        </span>
                    </div>
                </div>
            </a>

            {{-- Kategori 2: Adab Berpakaian --}}
            <a href="{{ route('siswa.kategori.show', 'adab-berpakaian') }}"
               class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 overflow-hidden">
                <div class="bg-gradient-to-br from-purple-400 to-pink-500 p-8 relative">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        👔
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-xs font-semibold">
                        10 Materi
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Adab Berpakaian</h3>
                    <p class="text-sm text-gray-600 mb-4">Cara berpakaian yang sopan dan sesuai syariat</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            52 siswa
                        </span>
                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-semibold">
                            75% selesai
                        </span>
                    </div>
                </div>
            </a>

            {{-- Kategori 3: Adab Makan dan Minum --}}
            <a href="{{ route('siswa.kategori.show', 'adab-makan-minum') }}"
               class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 overflow-hidden">
                <div class="bg-gradient-to-br from-orange-400 to-red-500 p-8 relative">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        🍽️
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-xs font-semibold">
                        14 Materi
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Adab Makan dan Minum</h3>
                    <p class="text-sm text-gray-600 mb-4">Tata cara makan dan minum yang baik menurut ajaran Islam</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            60 siswa
                        </span>
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-semibold">
                            85% selesai
                        </span>
                    </div>
                </div>
            </a>

            {{-- Kategori 4: Adab Bermedia Sosial --}}
            <a href="{{ route('siswa.kategori.show', 'adab-media-sosial') }}"
               class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 overflow-hidden">
                <div class="bg-gradient-to-br from-green-400 to-emerald-600 p-8 relative">
                    <div class="text-7xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        📱
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-xs font-semibold">
                        12 Materi
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Adab Bermedia Sosial</h3>
                    <p class="text-sm text-gray-600 mb-4">Etika dan adab dalam menggunakan media sosial secara islami</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            48 siswa
                        </span>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">
                            70% selesai
                        </span>
                    </div>
                </div>
            </a>

        </div>

        {{-- [NEW FEATURE] Summary Stats --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">4</div>
                    <div class="text-sm text-gray-600">Total Kategori</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">85</div>
                    <div class="text-sm text-gray-600">Total Materi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">62%</div>
                    <div class="text-sm text-gray-600">Progress Keseluruhan</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">120</div>
                    <div class="text-sm text-gray-600">Siswa Aktif</div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
