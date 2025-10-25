{{--
    =====================================================
    [NEW FEATURE] Halaman Kelas - Level 2
    =====================================================
    Halaman untuk menampilkan daftar materi dalam satu kategori
    dengan header kategori, progress ring, dan card materi.

    Route: /siswa/kategori-pembelajaran/{slug}
    Role: siswa
    =====================================================
--}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kategori['nama'] }} - AdabKita</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts - Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4 lg:px-8">

        {{-- [NEW FEATURE] Breadcrumb Navigation --}}
        <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('siswa.dashboard') }}" class="text-gray-600 hover:text-purple-600 transition">
                        <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
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
                        <a href="{{ route('siswa.kategori.index') }}" class="ml-1 text-gray-600 hover:text-purple-600 transition md:ml-2">
                            Kategori Pembelajaran
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-800 font-semibold md:ml-2">{{ $kategori['nama'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- [NEW FEATURE] Category Header with Progress Ring --}}
        <div class="bg-gradient-to-r {{ $kategori['gradient'] }} rounded-2xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
            {{-- Decorative Patterns --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                {{-- Icon & Title --}}
                <div class="md:col-span-2">
                    <div class="text-8xl mb-4">{{ $kategori['icon'] }}</div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">{{ $kategori['nama'] }}</h1>
                    <p class="text-lg text-white/90 mb-6">{{ $kategori['deskripsi'] }}</p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                            📚 {{ count($materiList) }} Materi
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                            👥 {{ $kategori['jumlah_siswa'] }} Siswa
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2">
                            ⏱️ {{ $kategori['total_durasi'] }} menit
                        </div>
                    </div>
                </div>

                {{-- [NEW FEATURE] Progress Ring --}}
                <div class="flex justify-center">
                    <div class="relative w-48 h-48">
                        {{-- SVG Progress Ring --}}
                        <svg class="transform -rotate-90 w-48 h-48">
                            <circle cx="96" cy="96" r="88" stroke="rgba(255,255,255,0.2)" stroke-width="12" fill="none"/>
                            <circle cx="96" cy="96" r="88" stroke="white" stroke-width="12" fill="none"
                                    stroke-dasharray="{{ 2 * 3.14159 * 88 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $kategori['progress'] / 100) }}"
                                    stroke-linecap="round"
                                    class="transition-all duration-1000"/>
                        </svg>
                        {{-- Progress Text --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="text-5xl font-bold">{{ $kategori['progress'] }}%</div>
                            <div class="text-sm text-white/80">Progress</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- [NEW FEATURE] Alert Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p class="font-semibold">✅ {{ session('success') }}</p>
            </div>
        @endif

        {{-- [NEW FEATURE] Materi List Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($materiList as $materi)
                {{-- [NEW FEATURE] Materi Card with Dynamic Status --}}
                <div id="materi-{{ $materi->id }}" class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">

                    {{-- Card Header --}}
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-3">
                            {{-- Tipe File Icon --}}
                            <div class="text-4xl">
                                @if($materi->tipe === 'pdf')
                                    📄
                                @elseif($materi->tipe === 'pptx')
                                    📊
                                @elseif($materi->tipe === 'video')
                                    🎥
                                @else
                                    🎯
                                @endif
                            </div>

                            {{-- [NEW FEATURE] Status Badge dengan Warna Dinamis --}}
                            @if($materi->status === 'selesai')
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Selesai
                                </span>
                            @elseif($materi->status === 'berjalan')
                                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Sedang Belajar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    Belum Dimulai
                                </span>
                            @endif
                        </div>

                        {{-- Judul Materi --}}
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 min-h-[3.5rem]">
                            {{ $materi->judul_materi }}
                        </h3>

                        {{-- Meta Info --}}
                        <div class="flex items-center gap-4 text-xs text-gray-600 mb-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $materi->durasi }} menit
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                {{ strtoupper($materi->tipe) }}
                            </span>
                        </div>

                        {{-- Progress Bar (jika status berjalan) --}}
                        @if($materi->status === 'berjalan')
                            <div class="mb-4">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-600">Progress</span>
                                    <span class="font-bold text-blue-600">{{ $materi->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ $materi->progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        {{-- [NEW FEATURE] Tombol Dinamis --}}
                        @if($materi->status === 'selesai')
                            <a href="{{ route('siswa.materi.view', $materi->id) }}"
                               class="block w-full text-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
                                🔄 Ulangi
                            </a>
                        @elseif($materi->status === 'berjalan')
                            <a href="{{ route('siswa.materi.view', $materi->id) }}"
                               class="block w-full text-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
                                ▶️ Lanjutkan
                            </a>
                        @else
                            <a href="{{ route('siswa.materi.view', $materi->id) }}"
                               class="block w-full text-center bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500 text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
                                🚀 Mulai Belajar
                            </a>
                        @endif
                    </div>

                    {{-- [NEW FEATURE] Card Footer - Guru Info & Siswa Selesai --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100">
                        <div class="flex items-center justify-between">
                            {{-- Guru Info --}}
                            <div class="flex items-center gap-2">
                                @if($materi->guru_foto)
                                    <img src="{{ asset('storage/foto_profil/' . $materi->guru_foto) }}"
                                         alt="{{ $materi->guru_nama }}"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-sm">
                                        {{ substr($materi->guru_nama ?? 'G', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-xs text-gray-500">Pengampu</div>
                                    <div class="text-sm font-semibold text-gray-800">{{ $materi->guru_nama }}</div>
                                </div>
                            </div>

                            {{-- Jumlah Siswa Selesai --}}
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Diselesaikan</div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-800">{{ $materi->siswa_selesai }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        {{-- Back Button --}}
        <div class="mt-8 text-center">
            <a href="{{ route('siswa.kategori.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Kembali ke Kategori
            </a>
        </div>

    </div>
</div>

</body>
</html>
