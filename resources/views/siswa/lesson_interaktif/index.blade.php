{{--
    =====================================================
    [NEW FEATURE] Integrasi Materi Interaktif - Index
    =====================================================
    Halaman daftar materi pembelajaran interaktif
    dalam bentuk grid card Tailwind CSS

    URL: /siswa/lesson-interaktif
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Interaktif - AdabKita</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts - Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'adab-purple': { light: '#8b5cf6', DEFAULT: '#8b5cf6', dark: '#7c3aed' },
                        'adab-pink': { light: '#ec4899', DEFAULT: '#ec4899', dark: '#db2777' }
                    },
                    fontFamily: { 'poppins': ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        .bg-adab-gradient { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="font-poppins bg-gray-50">

    {{-- [NEW FEATURE] Navbar --}}
    <nav class="bg-adab-gradient sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-2 text-white hover:opacity-80 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Kembali ke Dashboard</span>
                    </a>
                </div>
                <div class="flex items-center gap-2 bg-white/20 rounded-full px-4 py-2">
                    <span class="text-xl">👤</span>
                    <span class="text-white font-semibold hidden md:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    {{-- [NEW FEATURE] Hero Section --}}
    <section class="bg-adab-gradient text-white py-12 md:py-16">
        <div class="container mx-auto px-4 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 animate-fade-in">
                📚 Materi Pembelajaran Interaktif
            </h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto animate-fade-in">
                Belajar Adab Islami dengan cara yang menyenangkan dan interaktif
            </p>
        </div>
    </section>

    {{-- [NEW FEATURE] Main Content - Grid Card --}}
    <section class="py-12">
        <div class="container mx-auto px-4 lg:px-8">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-lg animate-fade-in">
                    <p class="font-semibold">✅ {{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg animate-fade-in">
                    <p class="font-semibold">❌ {{ session('error') }}</p>
                </div>
            @endif

            {{-- Grid Container --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse($materiList as $materi)
                    {{-- [NEW FEATURE] Card Materi Interaktif --}}
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden animate-fade-in">

                        {{-- Thumbnail / Icon Header --}}
                        <div class="bg-gradient-to-r from-purple-500 to-pink-400 h-48 flex items-center justify-center text-8xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
                            <span class="relative z-10">{{ $materi['icon'] }}</span>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-6">
                            {{-- Badge Status --}}
                            <div class="flex items-center justify-between mb-3">
                                <span class="{{ $materi['badge_class'] }} text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $materi['badge_status'] }}
                                </span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $materi['kategori'] }}
                                </span>
                            </div>

                            {{-- Judul --}}
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                {{ $materi['judul'] }}
                            </h3>

                            {{-- Deskripsi --}}
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $materi['deskripsi'] ?? 'Materi pembelajaran interaktif dengan berbagai komponen menarik' }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $materi['durasi'] }} menit
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $materi['total_items'] }} konten
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">Progress Belajar</span>
                                    <span class="font-bold {{ $materi['progress'] >= 100 ? 'text-green-600' : 'text-purple-600' }}">
                                        {{ $materi['progress'] }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $materi['progress'] >= 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-gradient-to-r from-purple-500 to-pink-400' }} h-2.5 rounded-full transition-all duration-500"
                                         style="width: {{ $materi['progress'] }}%">
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <a href="{{ route('siswa.lesson-interaktif.show', $materi['id']) }}"
                               class="block w-full text-center {{ $materi['progress'] >= 100 ? 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' : 'bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500' }} text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                @if($materi['progress'] >= 100)
                                    ✅ Ulangi Materi
                                @elseif($materi['progress'] > 0)
                                    ▶️ Lanjutkan Belajar
                                @else
                                    🚀 Mulai Belajar
                                @endif
                            </a>

                            {{-- Guru Info --}}
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Dibuat oleh: <span class="font-semibold">{{ $materi['guru_nama'] }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    {{-- Empty State --}}
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl shadow-lg p-12 md:p-16 text-center">
                            <div class="text-8xl mb-6 opacity-20">📚</div>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                                Belum Ada Materi Interaktif
                            </h3>
                            <p class="text-gray-600 max-w-md mx-auto mb-8">
                                Saat ini belum ada materi pembelajaran interaktif yang tersedia.
                                Silakan hubungi guru Anda untuk informasi lebih lanjut.
                            </p>
                            <a href="{{ route('siswa.dashboard') }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-400 text-white font-semibold px-8 py-3 rounded-lg hover:from-purple-600 hover:to-pink-500 transition transform hover:scale-105">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endforelse

            </div>

        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} <span class="font-bold">AdabKita</span> - Platform Pembelajaran Adab Islami</p>
        </div>
    </footer>

</body>
</html>
