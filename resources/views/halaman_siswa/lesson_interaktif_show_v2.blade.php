{{--
    =====================================================
    [NEW FEATURE] Halaman Lesson Interaktif Fullscreen V2
    =====================================================
    Halaman fullscreen untuk menampilkan materi interaktif
    dengan video, teks, gambar, dan kuis.

    Fitur Baru:
    - Navigasi next/prev lesson item
    - Progress bar detail
    - Rendering konten berdasarkan tipe item
    - Integrasi dengan LessonItemController

    URL: /siswa/lesson-interaktif/{id}/mulai?item={itemId}
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $materi->judul_materi ?? 'Materi Interaktif' }} - AdabKita</title>

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
                        'adab-purple': { light: '#8b5cf6', DEFAULT: '#8b5cf6', dark: '#7c3aed' },
                        'adab-pink': { light: '#ec4899', DEFAULT: '#ec4899', dark: '#db2777' }
                    },
                    fontFamily: { 'poppins': ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        /* Custom gradient backgrounds */
        .bg-adab-gradient { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #8b5cf6; }

        /* Fade in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    </style>
</head>
<body class="font-poppins bg-gray-50 overflow-x-hidden">

    {{-- =====================================================
        [NEW FEATURE] Header dengan Progress Bar
        ===================================================== --}}
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo & Back Button --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('siswa.dashboard') }}"
                       class="flex items-center gap-2 text-gray-700 hover:text-adab-purple transition group">
                        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden md:inline font-semibold">Kembali</span>
                    </a>

                    <div class="h-8 w-px bg-gray-300 hidden md:block"></div>

                    {{-- Judul Materi --}}
                    <div>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">
                            {{ $materi->judul_materi }}
                        </h1>
                        <p class="text-xs text-gray-500 hidden md:block">
                            Item {{ $itemsDikerjakan }}/{{ $totalItems }} • {{ $progress }}% selesai
                        </p>
                    </div>
                </div>

                {{-- Progress Indicator --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 {{ $progress >= 100 ? 'text-green-600' : 'text-purple-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">{{ $progress }}%</span>
                    </div>

                    {{-- User Avatar --}}
                    <div class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1.5">
                        <span class="text-lg">👤</span>
                        <span class="text-sm font-semibold text-gray-700 hidden md:inline">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- [NEW FEATURE] Progress Bar --}}
        <div class="w-full bg-gray-200 h-1">
            <div class="bg-adab-gradient h-1 transition-all duration-500"
                 style="width: {{ $progress }}%"></div>
        </div>
    </header>

    {{-- =====================================================
        [NEW FEATURE] Main Content Area (Fullscreen)
        ===================================================== --}}
    <main class="min-h-screen">
        <div class="container mx-auto px-4 lg:px-8 py-8">
            <div class="max-w-5xl mx-auto">

                {{-- [NEW FEATURE] Render Konten Berdasarkan Tipe Item --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 animate-fade-in">

                    @if($currentItem->tipe_item === 'video')
                        {{-- Konten Video --}}
                        <div class="aspect-video bg-gray-900">
                            @if(str_contains($currentItem->konten, 'youtube') || str_contains($currentItem->konten, 'youtu.be'))
                                <iframe
                                    class="w-full h-full"
                                    src="{{ $currentItem->konten }}"
                                    title="Video Pembelajaran"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            @else
                                <video class="w-full h-full" controls>
                                    <source src="{{ $currentItem->konten }}" type="video/mp4">
                                    Browser Anda tidak mendukung video.
                                </video>
                            @endif
                        </div>
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">📹 Video Pembelajaran</h2>
                            <p class="text-gray-600">Tonton video di atas dengan seksama untuk memahami materi.</p>
                        </div>

                    @elseif($currentItem->tipe_item === 'gambar')
                        {{-- Konten Gambar --}}
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">🖼️ Materi Gambar</h2>
                            <div class="bg-gray-100 rounded-xl p-4 text-center">
                                @if(filter_var($currentItem->konten, FILTER_VALIDATE_URL))
                                    <img src="{{ $currentItem->konten }}" alt="Gambar Materi" class="max-w-full h-auto mx-auto rounded-lg shadow-md">
                                @else
                                    <img src="{{ asset('storage/lesson_gambar/' . $currentItem->konten) }}" alt="Gambar Materi" class="max-w-full h-auto mx-auto rounded-lg shadow-md">
                                @endif
                            </div>
                        </div>

                    @elseif($currentItem->tipe_item === 'soal_pg')
                        {{-- Soal Pilihan Ganda --}}
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-7 h-7 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Soal Pilihan Ganda
                                </h2>
                                <span class="text-sm bg-purple-100 text-purple-700 px-4 py-2 rounded-full font-semibold">
                                    {{ $currentItem->poin ?? 10 }} Poin
                                </span>
                            </div>

                            <form action="{{ route('siswa.lesson-interaktif.submit', $materi->id) }}" method="POST" id="soalForm">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $currentItem->id }}">

                                <div class="mb-8">
                                    <p class="text-lg text-gray-800 font-medium mb-6">
                                        {{ $currentItem->konten }}
                                    </p>

                                    <div class="space-y-3">
                                        @foreach(['a' => $currentItem->opsi_a, 'b' => $currentItem->opsi_b, 'c' => $currentItem->opsi_c, 'd' => $currentItem->opsi_d] as $key => $opsi)
                                            @if($opsi)
                                                <label class="flex items-start gap-3 p-4 rounded-lg border-2 border-gray-200 hover:border-purple-400 hover:bg-purple-50 cursor-pointer transition group {{ $isItemDikerjakan ? 'opacity-75' : '' }}">
                                                    <input
                                                        type="radio"
                                                        name="jawaban[{{ $currentItem->id }}]"
                                                        value="{{ $key }}"
                                                        class="mt-1 w-4 h-4 text-purple-600 focus:ring-purple-500"
                                                        {{ $isItemDikerjakan ? 'disabled' : 'required' }}>
                                                    <span class="text-gray-700 group-hover:text-gray-900 flex-1">
                                                        <span class="font-semibold uppercase">{{ $key }}.</span> {{ $opsi }}
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                @if(!$isItemDikerjakan)
                                    <button
                                        type="submit"
                                        class="w-full bg-gradient-to-r from-purple-500 to-pink-400 text-white font-bold py-4 rounded-xl hover:from-purple-600 hover:to-pink-500 transition transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Submit Jawaban
                                    </button>
                                @else
                                    <div class="w-full bg-green-100 text-green-700 font-semibold py-4 rounded-xl text-center">
                                        ✅ Soal ini sudah dijawab
                                    </div>
                                @endif
                            </form>

                            @if($currentItem->penjelasan)
                                <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                    <h3 class="font-bold text-blue-800 mb-2">💡 Penjelasan:</h3>
                                    <p class="text-blue-700">{{ $currentItem->penjelasan }}</p>
                                </div>
                            @endif
                        </div>

                    @elseif($currentItem->tipe_item === 'isian')
                        {{-- Soal Isian --}}
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-800">✍️ Soal Isian</h2>
                                <span class="text-sm bg-purple-100 text-purple-700 px-4 py-2 rounded-full font-semibold">
                                    {{ $currentItem->poin ?? 10 }} Poin
                                </span>
                            </div>

                            <form action="{{ route('siswa.lesson-interaktif.submit', $materi->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $currentItem->id }}">

                                <div class="mb-8">
                                    <p class="text-lg text-gray-800 font-medium mb-6">
                                        {{ $currentItem->konten }}
                                    </p>

                                    <input
                                        type="text"
                                        name="jawaban[{{ $currentItem->id }}]"
                                        placeholder="Tulis jawaban Anda di sini..."
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition"
                                        {{ $isItemDikerjakan ? 'disabled' : 'required' }}>
                                </div>

                                @if(!$isItemDikerjakan)
                                    <button
                                        type="submit"
                                        class="w-full bg-gradient-to-r from-purple-500 to-pink-400 text-white font-bold py-4 rounded-xl hover:from-purple-600 hover:to-pink-500 transition transform hover:scale-105 shadow-lg">
                                        Submit Jawaban
                                    </button>
                                @else
                                    <div class="w-full bg-green-100 text-green-700 font-semibold py-4 rounded-xl text-center">
                                        ✅ Soal ini sudah dijawab
                                    </div>
                                @endif
                            </form>
                        </div>

                    @else
                        {{-- Tipe konten lainnya --}}
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Materi Pembelajaran</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                <p>{{ $currentItem->konten }}</p>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- [NEW FEATURE] Navigasi Next/Prev --}}
                <div class="flex items-center justify-between gap-4 animate-fade-in">
                    {{-- Previous Button --}}
                    @if($prevItem)
                        <a href="{{ route('siswa.lesson-interaktif.mulai', $materi->id) }}?item={{ $prevItem->id }}"
                           class="flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-x-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Sebelumnya</span>
                        </a>
                    @else
                        <div class="flex-1"></div>
                    @endif

                    {{-- Progress Info --}}
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Item <span class="font-bold text-purple-600">{{ $itemsDikerjakan + 1 }}</span> dari {{ $totalItems }}
                        </p>
                    </div>

                    {{-- Next Button --}}
                    @if($nextItem)
                        <a href="{{ route('siswa.lesson-interaktif.mulai', $materi->id) }}?item={{ $nextItem->id }}"
                           class="flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition transform hover:translate-x-1">
                            <span>Selanjutnya</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('siswa.dashboard') }}"
                           class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Selesai</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </main>

    {{-- =====================================================
        FOOTER MINIMALIS
        ===================================================== --}}
    <footer class="bg-gray-800 text-white py-6 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} <span class="font-bold">AdabKita</span> - Platform Pembelajaran Adab Islami</p>
        </div>
    </footer>

    {{-- =====================================================
        JAVASCRIPT
        ===================================================== --}}
    <script>
        // Form submit dengan konfirmasi
        document.getElementById('soalForm')?.addEventListener('submit', function(e) {
            const confirmed = confirm('Yakin ingin submit jawaban? Pastikan jawaban sudah benar.');
            if (!confirmed) {
                e.preventDefault();
            }
        });

        // Auto-save progress to localStorage
        const saveProgress = () => {
            const progress = {{ $progress ?? 0 }};
            localStorage.setItem('materi_{{ $materi->id }}_progress', progress);
            console.log('Progress saved:', progress + '%');
        };

        saveProgress();
    </script>

</body>
</html>
