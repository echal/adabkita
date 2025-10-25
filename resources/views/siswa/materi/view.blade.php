{{--
    =====================================================
    [NEW FEATURE] Halaman Materi Interaktif - Level 3
    =====================================================
    Halaman fullscreen untuk menampilkan materi (PDF, Video, PPTX, Interaktif)
    dengan navigasi bottom dan tombol tandai selesai.

    Route: /siswa/materi/{id}/view
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $materi->judul_materi }} - AdabKita</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts - Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .fullscreen-content { min-height: calc(100vh - 140px); }

        /* PDF/Video Responsive Container */
        .content-viewer {
            position: relative;
            width: 100%;
            height: calc(100vh - 200px);
        }
        .content-viewer iframe,
        .content-viewer embed,
        .content-viewer object {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="bg-gray-900">

    {{-- [NEW FEATURE] Top Navigation Bar --}}
    <nav class="bg-gradient-to-r from-purple-600 to-pink-500 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Back Button --}}
                <a href="{{ route('siswa.kategori.show', $kategori['slug']) }}#materi-{{ $materi->id }}"
                   class="flex items-center gap-2 text-white hover:bg-white/20 px-4 py-2 rounded-lg transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold hidden md:inline">Kembali ke Daftar Materi</span>
                    <span class="font-semibold md:hidden">Kembali</span>
                </a>

                {{-- Title --}}
                <div class="flex-1 text-center px-4">
                    <h1 class="text-white font-bold text-sm md:text-lg line-clamp-1">
                        {{ $materi->judul_materi }}
                    </h1>
                </div>

                {{-- User Info --}}
                <div class="flex items-center gap-2 bg-white/20 rounded-full px-3 py-2">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-white font-semibold text-sm hidden md:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    {{-- [NEW FEATURE] Breadcrumb (Compact for Fullscreen) --}}
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="container mx-auto px-4 lg:px-8 py-2">
            <nav class="flex text-xs text-gray-400" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1">
                    <li><a href="{{ route('siswa.dashboard') }}" class="hover:text-purple-400 transition">Dashboard</a></li>
                    <li><span class="mx-2">›</span></li>
                    <li><a href="{{ route('siswa.kategori.index') }}" class="hover:text-purple-400 transition">Kategori Pembelajaran</a></li>
                    <li><span class="mx-2">›</span></li>
                    <li><a href="{{ route('siswa.kategori.show', $kategori['slug']) }}" class="hover:text-purple-400 transition">{{ $kategori['nama'] }}</a></li>
                    <li><span class="mx-2">›</span></li>
                    <li class="text-white font-semibold">{{ Str::limit($materi->judul_materi, 40) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- [NEW FEATURE] Main Content Area (Fullscreen) --}}
    <main class="fullscreen-content bg-gray-900">
        <div class="container mx-auto px-4 lg:px-8 py-6">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-600 text-white p-4 mb-4 rounded-lg shadow-lg flex items-center gap-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Content Viewer --}}
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">

                <div class="content-viewer">
                    @if($materi->tipe === 'pdf' && $materi->file_materi)
                        {{-- PDF Viewer --}}
                        <iframe src="{{ asset('storage/materi/' . $materi->file_materi) }}#toolbar=1&navpanes=1&scrollbar=1"
                                type="application/pdf">
                        </iframe>

                    @elseif($materi->tipe === 'pptx' && $materi->link_embed)
                        {{-- PPTX Embed (Google Slides or Office Online) --}}
                        <iframe src="{{ $materi->link_embed }}"
                                allowfullscreen="true">
                        </iframe>

                    @elseif($materi->tipe === 'video' && $materi->link_embed)
                        {{-- YouTube Video --}}
                        @php
                            // Extract YouTube video ID
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $materi->link_embed, $matches);
                            $videoId = $matches[1] ?? '';
                        @endphp
                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                            </iframe>
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-100">
                                <div class="text-center">
                                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-gray-600 text-lg">Video tidak dapat ditampilkan</p>
                                </div>
                            </div>
                        @endif

                    @elseif($materi->isi_materi)
                        {{-- Text Content --}}
                        <div class="p-8 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                            <div class="prose prose-lg max-w-none">
                                {!! nl2br(e($materi->isi_materi)) !!}
                            </div>
                        </div>

                    @else
                        {{-- No Content Available --}}
                        <div class="flex items-center justify-center h-full bg-gray-100">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-600 text-lg">Materi tidak tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </main>

    {{-- [NEW FEATURE] Bottom Navigation - Sticky --}}
    <footer class="bg-gray-800 border-t border-gray-700 sticky bottom-0 z-40 shadow-2xl">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                {{-- Left: Back Button --}}
                <a href="{{ route('siswa.kategori.show', $kategori['slug']) }}#materi-{{ $materi->id }}"
                   class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 w-full md:w-auto justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </a>

                {{-- Center: Progress Info --}}
                <div class="text-center text-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">{{ $materi->tipe === 'pdf' ? 'PDF' : ($materi->tipe === 'pptx' ? 'Presentasi' : ($materi->tipe === 'video' ? 'Video' : 'Materi')) }}</span>
                        <span class="text-gray-500">•</span>
                        <span>{{ $materi->durasi ?? 30 }} menit</span>
                    </div>
                </div>

                {{-- Right: Tandai Selesai Button --}}
                @if($materi->status !== 'selesai')
                    <form action="{{ route('siswa.materi.complete', $materi->id) }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg w-full md:w-auto justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            ✅ Tandai Selesai
                        </button>
                    </form>
                @else
                    <div class="flex items-center gap-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-lg w-full md:w-auto justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Sudah Selesai
                    </div>
                @endif

            </div>
        </div>
    </footer>

    {{-- Scroll to Last Position Script --}}
    <script>
        // Save scroll position before leaving
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('materiScrollPos', window.scrollY);
        });

        // Restore scroll position on load
        window.addEventListener('load', function() {
            const scrollPos = sessionStorage.getItem('materiScrollPos');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('materiScrollPos');
            }
        });
    </script>

</body>
</html>
