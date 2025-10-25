{{--
    =====================================================
    TEMPLATE DASHBOARD UTAMA
    =====================================================
    Template ini digunakan untuk semua halaman dashboard
    (Admin, Guru, Siswa).

    Fitur:
    - Sidebar navigasi otomatis berdasarkan role user
    - Navbar atas dengan info user dan logout
    - Alert notifikasi sukses/error
    - Menu aktif otomatis ter-highlight

    Cara pakai:
    @extends('layouts.template_dashboard')
    @section('judul_halaman', 'Nama Halaman')
    @section('isi_halaman')
        ... konten di sini ...
    @endsection

    @author: System
    @created: 2025-10-15
    @updated: 2025-10-15
    =====================================================
--}}

@extends('layouts.template_utama')

@section('konten')
<div class="container-fluid">
    <div class="row">
        {{-- ============================================
            SIDEBAR (Menu Navigasi Samping)
            - Warna berbeda sesuai role
            - Menu otomatis sesuai role user login
            - Menu aktif otomatis ter-highlight
            ============================================ --}}
        <div class="col-md-2 sidebar sidebar-{{ Auth::user()->role }} p-0">
            <div class="p-4">
                {{-- Logo/Nama Aplikasi --}}
                <h4 class="mb-4">
                    <i class="bi bi-mortarboard-fill"></i> Deep Learning
                </h4>

                {{-- Menu Navigasi Berdasarkan Role --}}
                <nav class="nav flex-column">
                    @if(Auth::user()->role === 'admin')
                        {{-- ========== MENU UNTUK ADMIN ========== --}}

                        {{-- Menu Dashboard --}}
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>

                        {{-- Menu Kelola Pengguna --}}
                        <a class="nav-link {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}"
                           href="{{ route('admin.pengguna.index') }}">
                            <i class="bi bi-people-fill"></i> Kelola Pengguna
                        </a>

                        {{-- Menu Kelola Materi --}}
                        <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
                           href="{{ route('materi.index') }}">
                            <i class="bi bi-book-fill"></i> Kelola Materi
                        </a>

                        {{-- Menu Lesson Flow Interaktif --}}
                        <a class="nav-link {{ request()->routeIs('lesson-flow.*') ? 'active' : '' }}"
                           href="{{ route('lesson-flow.index') }}">
                            <i class="bi bi-diagram-3-fill"></i> Lesson Flow Interaktif
                        </a>

                        {{-- Menu Rekap Nilai Siswa --}}
                        <a class="nav-link {{ request()->routeIs('rekap-nilai-siswa.*') ? 'active' : '' }}"
                           href="{{ route('rekap-nilai-siswa.index') }}">
                            <i class="bi bi-clipboard-data-fill"></i> Rekap Nilai Siswa
                        </a>

                        {{-- Menu Pengaturan --}}
                        <a class="nav-link {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-gear-fill"></i> Pengaturan
                        </a>

                    @elseif(Auth::user()->role === 'guru')
                        {{-- ========== MENU UNTUK GURU ========== --}}

                        {{-- Menu Dashboard --}}
                        <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
                           href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>

                        {{-- Menu Kelola Materi --}}
                        <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
                           href="{{ route('materi.index') }}">
                            <i class="bi bi-book-fill"></i> Kelola Materi
                        </a>

                        {{-- Menu Lesson Flow Interaktif --}}
                        <a class="nav-link {{ request()->routeIs('lesson-flow.*') ? 'active' : '' }}"
                           href="{{ route('lesson-flow.index') }}">
                            <i class="bi bi-diagram-3-fill"></i> Lesson Flow Interaktif
                        </a>

                        {{-- Menu Rekap Nilai Siswa --}}
                        <a class="nav-link {{ request()->routeIs('rekap-nilai-siswa.*') ? 'active' : '' }}"
                           href="{{ route('rekap-nilai-siswa.index') }}">
                            <i class="bi bi-clipboard-data-fill"></i> Rekap Nilai Siswa
                        </a>

                        {{-- Menu Tugas & Quiz --}}
                        <a class="nav-link {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}"
                           href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-journal-check"></i> Tugas & Quiz
                        </a>

                        {{-- Menu Daftar Siswa --}}
                        <a class="nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : '' }}"
                           href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-people-fill"></i> Daftar Siswa
                        </a>

                        {{-- Menu Pengaturan --}}
                        <a class="nav-link {{ request()->routeIs('guru.pengaturan') ? 'active' : '' }}"
                           href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-gear-fill"></i> Pengaturan
                        </a>

                    @else
                        {{-- ========== MENU UNTUK SISWA ========== --}}

                        {{-- Menu Dashboard --}}
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"
                           href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>

                        {{-- [NEW FEATURE] Menu Kategori Pembelajaran (3-Level Navigation) --}}
                        <a class="nav-link {{ request()->routeIs('siswa.kategori.*') ? 'active' : '' }}"
                           href="{{ route('siswa.kategori.index') }}">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Kategori Pembelajaran
                        </a>

                        {{-- Menu Materi Pembelajaran --}}
                        <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
                           href="{{ route('materi.index') }}">
                            <i class="bi bi-book-fill"></i> Materi Pembelajaran
                        </a>

                        {{-- Menu Lesson Interaktif --}}
                        <a class="nav-link {{ request()->routeIs('siswa.lesson-interaktif.*') ? 'active' : '' }}"
                           href="{{ route('siswa.lesson-interaktif.index') }}">
                            <i class="bi bi-play-circle-fill"></i> Lesson Interaktif
                        </a>

                        {{-- Menu Tugas Saya --}}
                        <a class="nav-link {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}"
                           href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-journal-text"></i> Tugas Saya
                        </a>

                        {{-- Menu Nilai Saya --}}
                        <a class="nav-link {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}"
                           href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-bar-chart-fill"></i> Nilai Saya
                        </a>

                        {{-- Menu Profil --}}
                        <a class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}"
                           href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-person-circle"></i> Profil
                        </a>
                    @endif
                </nav>
            </div>
        </div>

        {{-- ============================================
            KONTEN UTAMA (Area Kerja Sebelah Kanan)
            - Navbar atas dengan info user
            - Area konten dinamis
            - Alert notifikasi
            ============================================ --}}
        <div class="col-md-10 konten-utama">
            {{-- Navbar Atas --}}
            <nav class="navbar navbar-expand-lg navbar-light navbar-atas px-4">
                <div class="container-fluid">
                    {{-- Judul Halaman Dinamis --}}
                    <span class="navbar-brand mb-0 h1">
                        @yield('judul_halaman', 'Dashboard')
                    </span>

                    {{-- Info User & Tombol Logout --}}
                    <div class="d-flex align-items-center">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i>
                            <strong>{{ Auth::user()->name }}</strong>

                            {{-- Badge Role dengan warna berbeda sesuai role --}}
                            @if(Auth::user()->role === 'admin')
                                <span class="badge bg-danger ms-2">Admin</span>
                            @elseif(Auth::user()->role === 'guru')
                                <span class="badge bg-success ms-2">Guru</span>
                            @else
                                <span class="badge bg-primary ms-2">Siswa</span>
                            @endif
                        </span>

                        {{-- Form Logout dengan konfirmasi --}}
                        <form action="{{ route('logout') }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            {{-- Area Konten Halaman --}}
            <div class="p-4">
                {{-- Notifikasi Error (jika ada) --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Notifikasi Sukses (jika ada) --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Notifikasi Warning (jika ada) --}}
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <strong>Perhatian!</strong> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Notifikasi Info (jika ada) --}}
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Info!</strong> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Konten utama dari halaman yang menggunakan template ini --}}
                @yield('isi_halaman')
            </div>
        </div>
    </div>
</div>

{{-- JavaScript untuk auto-hide alert setelah 5 detik --}}
@push('js_tambahan')
<script>
    /**
     * Auto-hide semua alert setelah 5 detik
     * Alert bisa ditutup manual dengan tombol close
     */
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000); // 5000 ms = 5 detik
    });
</script>
@endpush
@endsection
