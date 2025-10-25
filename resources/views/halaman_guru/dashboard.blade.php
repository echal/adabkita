@extends('layouts.template_dashboard')

{{--
    =====================================================
    DASHBOARD GURU
    =====================================================
    Halaman dashboard untuk Guru/Pengajar
    URL: /guru/dashboard
    Role: guru
    =====================================================
--}}

@section('judul', 'Dashboard Guru - Sistem Deep Learning')

@section('judul_halaman', 'Dashboard Guru')

{{-- Menu Sidebar untuk Guru --}}
@section('menu_sidebar')
    <nav class="nav flex-column">
        <a class="nav-link active" href="{{ route('guru.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-book-fill"></i> Materi Saya
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-journal-check"></i> Tugas & Quiz
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-clipboard-data"></i> Input Nilai
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-people-fill"></i> Daftar Siswa
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-gear-fill"></i> Pengaturan
        </a>
    </nav>
@endsection

{{-- Isi Konten Halaman --}}
@section('isi_halaman')
    {{-- Pesan Selamat Datang --}}
    <div class="alert alert-success" role="alert">
        <h5 class="alert-heading">
            <i class="bi bi-check-circle-fill"></i> Selamat Datang, {{ Auth::user()->name }}!
        </h5>
        <p class="mb-0">
            Anda login sebagai <strong>Guru</strong>.
            Anda dapat mengelola materi, tugas, dan melihat progress siswa.
        </p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row mt-4">
        {{-- Statistik 1: Materi Saya --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Materi Saya</h6>
                            <h2 class="mb-0">12</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 2 baru bulan ini
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                            <i class="bi bi-book fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 2: Siswa Aktif --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Siswa Aktif</h6>
                            <h2 class="mb-0">68</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 8 siswa baru
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 3: Tugas Perlu Diperiksa --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Tugas Perlu Diperiksa</h6>
                            <h2 class="mb-0">23</h2>
                            <small class="text-warning">
                                <i class="bi bi-exclamation-circle"></i> Menunggu review
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded">
                            <i class="bi bi-journal-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 4: Rata-rata Nilai --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                            <h2 class="mb-0">85</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> Meningkat 3%
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded">
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-fill"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle fs-1 d-block mb-2"></i>
                                Tambah Materi Baru
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                Buat Quiz/Tugas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-clipboard-check fs-1 d-block mb-2"></i>
                                Periksa Tugas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>
                                Lihat Statistik
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aktivitas Terbaru Siswa --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Aktivitas Terbaru Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        {{-- Aktivitas 1 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Andi Wijaya mengumpulkan tugas</h6>
                                <small>10 menit lalu</small>
                            </div>
                            <p class="mb-1">Tugas "Implementasi Neural Network" telah dikumpulkan.</p>
                        </div>

                        {{-- Aktivitas 2 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Siti Nurhaliza menyelesaikan quiz</h6>
                                <small>1 jam lalu</small>
                            </div>
                            <p class="mb-1">Quiz "Dasar-dasar Machine Learning" - Nilai: 92</p>
                        </div>

                        {{-- Aktivitas 3 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Budi Santoso membaca materi</h6>
                                <small>2 jam lalu</small>
                            </div>
                            <p class="mb-1">Materi "Convolutional Neural Network" sedang dipelajari.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
