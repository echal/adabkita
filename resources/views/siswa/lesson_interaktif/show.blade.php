{{--
    =====================================================
    [NEW FEATURE] Integrasi Materi Interaktif - Fullscreen View
    =====================================================
    Halaman fullscreen untuk menampilkan komponen lesson flow
    interaktif (video, gambar, quiz, pilihan ganda, esai)
    dengan navigasi dan progress tracking.

    URL: /siswa/lesson-interaktif/{id}?item={item_id}
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lessonFlow->judul_materi }} - AdabKita</title>

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

        /* Fullscreen content area */
        .content-fullscreen {
            min-height: calc(100vh - 180px);
        }

        /* Responsive iframe */
        .video-responsive {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }
        .video-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">

    {{-- [NEW FEATURE] Sticky Progress Header --}}
    <header class="bg-adab-gradient sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            {{-- Top Bar with Back Button --}}
            <div class="flex items-center justify-between mb-3">
                <a href="{{ route('siswa.lesson-interaktif.index') }}"
                   class="flex items-center gap-2 text-white hover:opacity-80 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Kembali ke Daftar</span>
                </a>
                <div class="flex items-center gap-2 bg-white/20 rounded-full px-4 py-2">
                    <span class="text-xl">👤</span>
                    <span class="text-white font-semibold hidden md:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>

            {{-- Title and Progress --}}
            <div class="mb-3">
                <h1 class="text-white text-xl md:text-2xl font-bold mb-2">
                    {{ $lessonFlow->judul_materi }}
                </h1>
                <div class="flex items-center gap-3 text-white text-sm">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        Item {{ $currentIndex + 1 }}/{{ $totalItems }}
                    </span>
                    <span>•</span>
                    <span class="font-semibold">{{ $itemsDikerjakan }}/{{ $totalItems }} dikerjakan</span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white/20 rounded-full h-3 overflow-hidden">
                <div class="bg-white h-3 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                     style="width: {{ $progress }}%">
                    <span class="text-xs font-bold text-purple-600">{{ round($progress) }}%</span>
                </div>
            </div>
        </div>
    </header>

    {{-- [NEW FEATURE] Main Content Area - Fullscreen --}}
    <main class="content-fullscreen py-8">
        <div class="container mx-auto px-4 lg:px-8">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animate-fade-in">
                    <p class="font-semibold">✅ {{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animate-fade-in">
                    <p class="font-semibold">❌ {{ session('error') }}</p>
                </div>
            @endif

            {{-- Content Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-10 animate-fade-in">

                @if($currentItem)
                    {{-- [NEW FEATURE] Render Content Based on Type --}}

                    @if($currentItem->tipe_item === 'video')
                        {{-- Video YouTube --}}
                        <div class="mb-6">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                                <span class="text-4xl">🎥</span>
                                {{ $currentItem->konten_text ?? 'Video Pembelajaran' }}
                            </h2>
                            @if($currentItem->konten_url)
                                <div class="video-responsive rounded-xl overflow-hidden shadow-lg">
                                    @php
                                        // Extract YouTube video ID from URL
                                        $url = $currentItem->konten_url;
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches);
                                        $videoId = $matches[1] ?? '';
                                    @endphp
                                    @if($videoId)
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="bg-gray-100 p-8 text-center rounded-xl">
                                            <p class="text-gray-600">URL video tidak valid</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($currentItem->penjelasan)
                                <div class="mt-6 bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                                    <p class="text-gray-700">{{ $currentItem->penjelasan }}</p>
                                </div>
                            @endif
                        </div>

                    @elseif($currentItem->tipe_item === 'gambar')
                        {{-- Gambar / Infografis --}}
                        <div class="mb-6">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                                <span class="text-4xl">🖼️</span>
                                {{ $currentItem->konten_text ?? 'Materi Visual' }}
                            </h2>
                            @if($currentItem->konten_url)
                                <div class="flex justify-center mb-4">
                                    <img src="{{ asset('storage/' . $currentItem->konten_url) }}"
                                         alt="{{ $currentItem->konten_text }}"
                                         class="max-w-full h-auto rounded-xl shadow-lg">
                                </div>
                            @endif
                            @if($currentItem->penjelasan)
                                <div class="bg-pink-50 border-l-4 border-pink-500 p-4 rounded">
                                    <p class="text-gray-700">{{ $currentItem->penjelasan }}</p>
                                </div>
                            @endif
                        </div>

                    @elseif(in_array($currentItem->tipe_item, ['soal_pg', 'soal_gambar', 'isian']))
                        {{-- Quiz / Soal --}}
                        <div class="mb-6">
                            <div class="flex items-start gap-3 mb-6">
                                <span class="text-4xl">
                                    @if($currentItem->tipe_item === 'soal_pg') 📝
                                    @elseif($currentItem->tipe_item === 'soal_gambar') 🖼️
                                    @else ✍️
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                                        Soal {{ $currentItem->tipe_item === 'soal_pg' ? 'Pilihan Ganda' : ($currentItem->tipe_item === 'soal_gambar' ? 'Gambar' : 'Isian') }}
                                    </h2>
                                    @if($isItemDikerjakan)
                                        <span class="inline-block bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">
                                            ✅ Sudah dijawab
                                        </span>
                                    @else
                                        <span class="inline-block bg-yellow-100 text-yellow-700 text-sm font-semibold px-3 py-1 rounded-full">
                                            ⏳ Belum dijawab
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Form Jawaban --}}
                            <form action="{{ route('siswa.lesson-interaktif.submit', $lessonFlow->id) }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="id_lesson_item" value="{{ $currentItem->id }}">

                                {{-- Pertanyaan --}}
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-6">
                                    <p class="text-lg md:text-xl font-semibold text-gray-800 mb-4">
                                        {{ $currentItem->konten_text ?? $currentItem->soal->pertanyaan ?? 'Pertanyaan' }}
                                    </p>

                                    @if($currentItem->tipe_item === 'soal_gambar' && $currentItem->konten_url)
                                        <div class="flex justify-center mb-4">
                                            <img src="{{ asset('storage/' . $currentItem->konten_url) }}"
                                                 alt="Soal Gambar"
                                                 class="max-w-full h-auto rounded-lg shadow-md">
                                        </div>
                                    @endif
                                </div>

                                {{-- Input Jawaban --}}
                                @if($currentItem->tipe_item === 'soal_pg')
                                    {{-- Pilihan Ganda --}}
                                    <div class="space-y-3">
                                        @if($currentItem->soal)
                                            @php
                                                $options = [
                                                    'A' => $currentItem->soal->opsi_a,
                                                    'B' => $currentItem->soal->opsi_b,
                                                    'C' => $currentItem->soal->opsi_c,
                                                    'D' => $currentItem->soal->opsi_d,
                                                ];
                                            @endphp
                                            @foreach($options as $key => $value)
                                                @if($value)
                                                    <label class="flex items-start gap-4 bg-white border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition">
                                                        <input type="radio"
                                                               name="jawaban"
                                                               value="{{ $key }}"
                                                               class="mt-1 w-5 h-5 text-purple-600"
                                                               {{ $isItemDikerjakan ? 'disabled' : 'required' }}>
                                                        <span class="flex-1 text-gray-800 text-lg">
                                                            <span class="font-bold text-purple-600">{{ $key }}.</span> {{ $value }}
                                                        </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>

                                @elseif($currentItem->tipe_item === 'isian')
                                    {{-- Essay / Isian --}}
                                    <div>
                                        <textarea name="jawaban"
                                                  rows="6"
                                                  class="w-full border-2 border-gray-300 rounded-xl p-4 text-gray-800 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                                  placeholder="Tulis jawaban Anda di sini..."
                                                  {{ $isItemDikerjakan ? 'disabled' : 'required' }}></textarea>
                                    </div>

                                @elseif($currentItem->tipe_item === 'soal_gambar')
                                    {{-- Soal Gambar dengan Multiple Choice --}}
                                    <div class="space-y-3">
                                        @if($currentItem->soal)
                                            @php
                                                $options = [
                                                    'A' => $currentItem->soal->opsi_a,
                                                    'B' => $currentItem->soal->opsi_b,
                                                    'C' => $currentItem->soal->opsi_c,
                                                    'D' => $currentItem->soal->opsi_d,
                                                ];
                                            @endphp
                                            @foreach($options as $key => $value)
                                                @if($value)
                                                    <label class="flex items-start gap-4 bg-white border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition">
                                                        <input type="radio"
                                                               name="jawaban"
                                                               value="{{ $key }}"
                                                               class="mt-1 w-5 h-5 text-purple-600"
                                                               {{ $isItemDikerjakan ? 'disabled' : 'required' }}>
                                                        <span class="flex-1 text-gray-800 text-lg">
                                                            <span class="font-bold text-purple-600">{{ $key }}.</span> {{ $value }}
                                                        </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                {{-- Submit Button --}}
                                @if(!$isItemDikerjakan)
                                    <div class="flex justify-center pt-4">
                                        <button type="submit"
                                                class="bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500 text-white font-bold py-3 px-8 rounded-xl transition transform hover:scale-105 shadow-lg">
                                            📤 Kirim Jawaban
                                        </button>
                                    </div>
                                @else
                                    <div class="flex justify-center pt-4">
                                        <p class="text-green-600 font-semibold">✅ Anda sudah menjawab soal ini</p>
                                    </div>
                                @endif
                            </form>

                            {{-- Penjelasan (jika ada) --}}
                            @if($currentItem->penjelasan)
                                <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                    <p class="font-semibold text-blue-900 mb-2">💡 Penjelasan:</p>
                                    <p class="text-gray-700">{{ $currentItem->penjelasan }}</p>
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- Tipe item tidak dikenali --}}
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4 opacity-20">❓</div>
                            <p class="text-gray-600">Tipe konten tidak dikenali</p>
                        </div>
                    @endif

                @else
                    {{-- No current item --}}
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4 opacity-20">📚</div>
                        <p class="text-gray-600">Tidak ada konten yang dipilih</p>
                    </div>
                @endif

            </div>
        </div>
    </main>

    {{-- [NEW FEATURE] Bottom Navigation - Sticky --}}
    <footer class="bg-white border-t-2 border-gray-200 sticky bottom-0 z-40 shadow-lg">
        <div class="container mx-auto px-4 lg:px-8 py-4">
            <div class="flex items-center justify-between gap-4">

                {{-- Previous Button --}}
                @if($prevItem)
                    <a href="{{ route('siswa.lesson-interaktif.show', ['id' => $lessonFlow->id, 'item' => $prevItem->id]) }}"
                       class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="hidden md:inline">Sebelumnya</span>
                    </a>
                @else
                    <div class="w-32"></div> {{-- Spacer --}}
                @endif

                {{-- Progress Info --}}
                <div class="flex-1 text-center">
                    <p class="text-sm text-gray-600 font-medium">
                        Item <span class="font-bold text-purple-600">{{ $currentIndex + 1 }}</span> dari <span class="font-bold">{{ $totalItems }}</span>
                    </p>
                </div>

                {{-- Next Button --}}
                @if($nextItem)
                    <a href="{{ route('siswa.lesson-interaktif.show', ['id' => $lessonFlow->id, 'item' => $nextItem->id]) }}"
                       class="flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-md">
                        <span class="hidden md:inline">Selanjutnya</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @else
                    {{-- Finish Button --}}
                    <a href="{{ route('siswa.lesson-interaktif.index') }}"
                       class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-md">
                        <span>🎉 Selesai</span>
                    </a>
                @endif

            </div>
        </div>
    </footer>

    {{-- Auto-save Progress Script --}}
    <script>
        // [NEW FEATURE] Auto-save progress setiap 30 detik
        let autoSaveInterval = setInterval(function() {
            fetch('{{ route('siswa.lesson-interaktif.save-progress', $lessonFlow->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    current_item_id: {{ $currentItem->id ?? 'null' }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Progress auto-saved:', data.progress + '%');
                }
            })
            .catch(error => console.error('Auto-save error:', error));
        }, 30000); // 30 seconds

        // Clear interval when leaving page
        window.addEventListener('beforeunload', function() {
            clearInterval(autoSaveInterval);
        });
    </script>

</body>
</html>
