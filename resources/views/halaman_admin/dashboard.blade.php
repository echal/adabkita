@extends('layouts.template_dashboard')

{{--
    =====================================================
    DASHBOARD ADMIN
    =====================================================
    Halaman dashboard untuk Administrator
    URL: /admin/dashboard
    Role: admin
    =====================================================
--}}

@section('judul', 'Dashboard Admin - Sistem Deep Learning')

@section('judul_halaman', 'Dashboard Administrator')

{{-- Menu Sidebar untuk Admin --}}
@section('menu_sidebar')
    <nav class="nav flex-column">
        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-people-fill"></i> Kelola Pengguna
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-book-fill"></i> Kelola Materi
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-clipboard-check"></i> Kelola Nilai
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
            Anda login sebagai <strong>Administrator</strong>.
            Anda memiliki akses penuh ke seluruh sistem.
        </p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row mt-4">
        {{-- Statistik 1: Total Siswa --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Siswa</h6>
                            <h2 class="mb-0">150</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 12% bulan ini
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 2: Total Guru --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Guru</h6>
                            <h2 class="mb-0">25</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 5% bulan ini
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                            <i class="bi bi-person-badge fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 3: Total Materi --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Materi</h6>
                            <h2 class="mb-0">48</h2>
                            <small class="text-info">
                                <i class="bi bi-arrow-right"></i> Stabil
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded">
                            <i class="bi bi-book fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik 4: Aktivitas Hari Ini --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Aktivitas Hari Ini</h6>
                            <h2 class="mb-0">89</h2>
                            <small class="text-danger">
                                <i class="bi bi-arrow-down"></i> 3% dari kemarin
                            </small>
                        </div>
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded">
                            <i class="bi bi-activity fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        {{-- Aktivitas 1 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Guru baru terdaftar</h6>
                                <small>5 menit lalu</small>
                            </div>
                            <p class="mb-1">Budi Santoso telah mendaftar sebagai guru baru.</p>
                        </div>

                        {{-- Aktivitas 2 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Materi baru ditambahkan</h6>
                                <small>1 jam lalu</small>
                            </div>
                            <p class="mb-1">Materi "Pengenalan Machine Learning" telah ditambahkan.</p>
                        </div>

                        {{-- Aktivitas 3 --}}
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Siswa menyelesaikan quiz</h6>
                                <small>2 jam lalu</small>
                            </div>
                            <p class="mb-1">Andi Wijaya menyelesaikan quiz dengan nilai 95.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
