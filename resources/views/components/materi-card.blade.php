{{--
    =====================================================
    [NEW FEATURE] Integrasi Materi Interaktif
    MATERI CARD COMPONENT
    =====================================================
    Component untuk menampilkan card materi pembelajaran
    dengan informasi dinamis, action button yang berubah
    sesuai status progress, dan card footer.

    Props:
    - $materi: object Materi model dengan relasi dan accessor

    Usage:
    <x-materi-card :materi="$materi" />
    =====================================================
--}}

@props(['materi'])

<div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden group">

    {{-- Card Header dengan Gradient dan Icon --}}
    <div class="bg-gradient-to-r from-purple-500 to-pink-400 p-6 relative overflow-hidden">
        {{-- Decorative Pattern --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 group-hover:scale-110 transition-transform duration-500"></div>

        {{-- Icon Materi --}}
        <div class="text-5xl mb-3 relative z-10 transform group-hover:scale-110 transition-transform duration-300">
            {{ $materi->icon ?? '🧠' }}
        </div>

        {{-- Judul Materi --}}
        <h3 class="text-xl font-bold text-white mb-2 relative z-10 line-clamp-2 min-h-[3.5rem]">
            {{ $materi->judul_materi ?? 'Materi Pembelajaran' }}
        </h3>

        {{-- Kategori Badge --}}
        <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full backdrop-blur-sm relative z-10">
            {{ $materi->kategori ?? 'Adab Islami' }}
        </span>
    </div>

    {{-- Card Body --}}
    <div class="p-6">

        {{-- Meta Information --}}
        <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $materi->durasi ?? 30 }} menit</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $materi->jumlah_soal ?? 0 }} soal</span>
            </div>
        </div>

        {{-- Deskripsi Singkat --}}
        @if($materi->deskripsi)
            <p class="text-gray-600 text-sm mb-4 line-clamp-2 min-h-[2.5rem]">
                {{ Str::limit($materi->deskripsi, 100) }}
            </p>
        @else
            <div class="mb-4 min-h-[2.5rem]"></div>
        @endif

        {{-- Progress Bar --}}
        <div class="mb-4">
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-gray-600 font-medium">Progress Belajar</span>
                <span class="font-bold {{ $materi->progress >= 100 ? 'text-green-600' : 'text-purple-600' }}">
                    {{ $materi->progress }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                <div class="h-2.5 rounded-full transition-all duration-500 {{ $materi->progress >= 100 ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-purple-500 to-pink-400' }}"
                     style="width: {{ $materi->progress }}%">
                </div>
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="mb-4">
            @if($materi->status === 'selesai')
                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Selesai
                </span>
            @elseif($materi->status === 'sedang_belajar')
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

        {{-- Action Button Dinamis --}}
        {{-- [NEW FEATURE] Tombol mengarah ke Level 1 (Kategori Pembelajaran) --}}
        <a href="{{ route('siswa.kategori.index') }}"
           class="block w-full text-center bg-gradient-to-r from-purple-500 to-pink-400 hover:from-purple-600 hover:to-pink-500 text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg group/btn">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                </svg>
                Jelajahi Kategori
            </span>
        </a>

    </div>

    {{-- Card Footer (Info Guru & Siswa Selesai) --}}
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
        <div class="flex items-center justify-between">

            {{-- Guru Info --}}
            <div class="flex items-center gap-2">
                {{-- Foto Guru (jika ada) atau Avatar Default --}}
                @if($materi->guru_foto_url)
                    <img src="{{ $materi->guru_foto_url }}"
                         alt="{{ $materi->dibuat_oleh }}"
                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                @else
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-sm">
                        {{ substr($materi->dibuat_oleh ?? 'G', 0, 1) }}
                    </div>
                @endif

                {{-- Nama Guru --}}
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500">Dibuat oleh</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $materi->dibuat_oleh ?? 'Guru' }}</span>
                </div>
            </div>

            {{-- Jumlah Siswa Selesai --}}
            <div class="flex flex-col items-end">
                <span class="text-xs text-gray-500">Diselesaikan</span>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span class="text-sm font-bold text-gray-800">{{ $materi->jumlah_siswa_selesai ?? 0 }}</span>
                    <span class="text-xs text-gray-500">siswa</span>
                </div>
            </div>

        </div>

        {{-- Waktu Upload --}}
        @if($materi->created_at)
            <div class="mt-2 pt-2 border-t border-gray-200">
                <div class="flex items-center gap-1 text-xs text-gray-500">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Diunggah {{ $materi->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @endif
    </div>

</div>
