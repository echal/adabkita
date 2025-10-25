{{--
    =====================================================
    [NEW FEATURE] Halaman Lesson Interaktif Fullscreen
    =====================================================
    Halaman fullscreen untuk menampilkan materi interaktif
    dengan video, teks, dan kuis.

    URL: /siswa/lesson-interaktif/{id}/mulai
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $materi->judul ?? 'Materi Interaktif' }} - AdabKita</title>

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
                        'adab-purple': {
                            light: '#8b5cf6',
                            DEFAULT: '#8b5cf6',
                            dark: '#7c3aed'
                        },
                        'adab-pink': {
                            light: '#ec4899',
                            DEFAULT: '#ec4899',
                            dark: '#db2777'
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
        .bg-adab-gradient {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #8b5cf6;
        }

        /* Line clamp untuk teks panjang */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50 overflow-x-hidden">

    {{-- =====================================================
        [NEW FEATURE] Header Minimalis dengan Tombol Kembali
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
                        <span class="hidden md:inline font-semibold">Kembali ke Dashboard</span>
                    </a>

                    <div class="h-8 w-px bg-gray-300 hidden md:block"></div>

                    {{-- Judul Materi --}}
                    <div>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">
                            {{ $materi->judul ?? 'Materi Pembelajaran' }}
                        </h1>
                        <p class="text-xs text-gray-500 hidden md:block">
                            {{ $materi->kategori->nama ?? 'Kategori' }} • {{ $materi->durasi ?? 15 }} menit
                        </p>
                    </div>
                </div>

                {{-- Progress Indicator --}}
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">{{ $progress ?? 0 }}%</span>
                    </div>

                    {{-- User Avatar --}}
                    <div class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1.5">
                        <span class="text-lg">👤</span>
                        <span class="text-sm font-semibold text-gray-700 hidden md:inline">
                            {{ Auth::user()->name ?? 'Siswa' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Bar (di bawah header) --}}
        <div class="w-full bg-gray-200 h-1">
            <div class="bg-adab-gradient h-1 transition-all duration-500"
                 style="width: {{ $progress ?? 0 }}%"></div>
        </div>
    </header>

    {{-- =====================================================
        [NEW FEATURE] Main Content Area (Fullscreen)
        ===================================================== --}}
    <main class="min-h-screen">
        <div class="container mx-auto px-4 lg:px-8 py-8">
            <div class="grid lg:grid-cols-12 gap-8">

                {{-- =====================================================
                    KOLOM KIRI: Konten Utama (Materi)
                    ===================================================== --}}
                <div class="lg:col-span-8">

                    {{-- [NEW FEATURE] Video Section (jika ada) --}}
                    @if($materi->video_url ?? false)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 animate-fade-in">
                            <div class="aspect-video bg-gray-900">
                                {{-- YouTube Embed / Video Player --}}
                                @if(str_contains($materi->video_url, 'youtube') || str_contains($materi->video_url, 'youtu.be'))
                                    <iframe
                                        class="w-full h-full"
                                        src="{{ $materi->video_url }}"
                                        title="Video Pembelajaran"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <video class="w-full h-full" controls>
                                        <source src="{{ $materi->video_url }}" type="video/mp4">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- [NEW FEATURE] Materi Text Content --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 animate-fade-in">
                        {{-- Header Materi --}}
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-4xl">{{ $materi->icon ?? '🧠' }}</span>
                                    <span class="bg-gradient-to-r from-purple-500 to-pink-400 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $materi->kategori->nama ?? 'Umum' }}
                                    </span>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                                    {{ $materi->judul }}
                                </h2>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $materi->durasi ?? 15 }} menit
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $materi->guru->name ?? 'Guru' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $materi->jumlah_soal ?? 10 }} soal
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">

                        {{-- Konten Materi (Rich Text) --}}
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            {!! $materi->konten ?? '<p>Konten materi sedang dalam proses pembuatan...</p>' !!}
                        </div>

                        {{-- Poin-poin Penting (jika ada) --}}
                        @if($materi->poin_penting ?? false)
                            <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border-l-4 border-purple-500">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Poin Penting
                                </h3>
                                <ul class="space-y-2 text-gray-700">
                                    @foreach(explode("\n", $materi->poin_penting) as $poin)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ trim($poin) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- [NEW FEATURE] Kuis Interaktif Section --}}
                    @if($soalList ?? false)
                        <div class="bg-white rounded-2xl shadow-lg p-8 animate-fade-in">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-7 h-7 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Kuis Pemahaman
                                </h3>
                                <span class="text-sm text-gray-600 bg-gray-100 px-4 py-2 rounded-full font-semibold">
                                    {{ count($soalList) }} Soal
                                </span>
                            </div>

                            <form action="{{ route('siswa.lesson-interaktif.submit', $materi->id) }}" method="POST" id="quizForm">
                                @csrf

                                @foreach($soalList as $index => $soal)
                                    <div class="mb-8 pb-8 {{ $loop->last ? '' : 'border-b border-gray-200' }}">
                                        <div class="flex items-start gap-3 mb-4">
                                            <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-400 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <p class="text-gray-800 font-medium flex-1">
                                                {{ $soal->pertanyaan }}
                                            </p>
                                        </div>

                                        <div class="ml-11 space-y-3">
                                            @foreach(['a', 'b', 'c', 'd'] as $option)
                                                @if($soal->{"jawaban_$option"})
                                                    <label class="flex items-start gap-3 p-4 rounded-lg border-2 border-gray-200 hover:border-purple-400 hover:bg-purple-50 cursor-pointer transition group">
                                                        <input
                                                            type="radio"
                                                            name="jawaban[{{ $soal->id }}]"
                                                            value="{{ $option }}"
                                                            class="mt-1 w-4 h-4 text-purple-600 focus:ring-purple-500"
                                                            required>
                                                        <span class="text-gray-700 group-hover:text-gray-900 flex-1">
                                                            <span class="font-semibold uppercase">{{ $option }}.</span>
                                                            {{ $soal->{"jawaban_$option"} }}
                                                        </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                    <button
                                        type="submit"
                                        class="flex-1 bg-gradient-to-r from-purple-500 to-pink-400 text-white font-bold py-4 rounded-xl hover:from-purple-600 hover:to-pink-500 transition transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Submit Jawaban
                                    </button>

                                    <button
                                        type="reset"
                                        class="sm:w-auto bg-gray-200 text-gray-700 font-semibold py-4 px-8 rounded-xl hover:bg-gray-300 transition">
                                        Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>

                {{-- =====================================================
                    KOLOM KANAN: Sidebar Progress (Optional)
                    ===================================================== --}}
                <div class="lg:col-span-4">

                    {{-- Progress Card --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24 animate-fade-in">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Progress Belajar
                        </h3>

                        {{-- Progress Bar --}}
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Progres Materi</span>
                                <span class="text-lg font-bold {{ ($progress ?? 0) >= 100 ? 'text-green-600' : 'text-purple-600' }}">
                                    {{ $progress ?? 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="{{ ($progress ?? 0) >= 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-gradient-to-r from-purple-500 to-pink-400' }} h-3 rounded-full transition-all duration-500"
                                     style="width: {{ $progress ?? 0 }}%"></div>
                            </div>
                        </div>

                        <hr class="my-6">

                        {{-- Statistik Singkat --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Durasi</span>
                                <span class="font-semibold text-gray-800">{{ $materi->durasi ?? 15 }} menit</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Jumlah Soal</span>
                                <span class="font-semibold text-gray-800">{{ $materi->jumlah_soal ?? 10 }} soal</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Kategori</span>
                                <span class="font-semibold text-gray-800">{{ $materi->kategori->nama ?? 'Umum' }}</span>
                            </div>
                        </div>

                        <hr class="my-6">

                        {{-- Action Buttons --}}
                        <div class="space-y-3">
                            <a href="{{ route('siswa.dashboard') }}"
                               class="block w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-200 transition text-center">
                                ← Kembali ke Dashboard
                            </a>

                            @if(($progress ?? 0) >= 100)
                                <button class="block w-full bg-gradient-to-r from-yellow-400 to-yellow-600 text-white font-semibold py-3 rounded-lg hover:from-yellow-500 hover:to-yellow-700 transition text-center">
                                    🏆 Lihat Sertifikat
                                </button>
                            @endif
                        </div>
                    </div>

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
        document.getElementById('quizForm')?.addEventListener('submit', function(e) {
            const confirmed = confirm('Yakin ingin submit jawaban? Pastikan semua soal sudah dijawab.');
            if (!confirmed) {
                e.preventDefault();
            }
        });

        // Auto-scroll to quiz section after video ends (optional)
        const video = document.querySelector('video');
        if (video) {
            video.addEventListener('ended', function() {
                const quizSection = document.querySelector('#quizForm');
                if (quizSection) {
                    quizSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }

        // Save progress to localStorage (optional)
        const saveProgress = () => {
            const progress = {{ $progress ?? 0 }};
            localStorage.setItem('materi_{{ $materi->id }}_progress', progress);
            console.log('Progress saved:', progress + '%');
        };

        // Call save progress
        saveProgress();
    </script>

</body>
</html>
