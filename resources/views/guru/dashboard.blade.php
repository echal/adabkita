<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Deep Learning</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stats-card {
            padding: 20px;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="mb-4"><i class="bi bi-mortarboard-fill"></i> Deep Learning</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('materi.index') }}">
                            <i class="bi bi-book-fill"></i> Kelola Materi
                        </a>
                        <a class="nav-link" href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-journal-check"></i> Tugas & Quiz
                        </a>
                        <a class="nav-link" href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-people-fill"></i> Daftar Siswa
                        </a>
                        <a class="nav-link" href="{{ route('guru.dashboard') }}">
                            <i class="bi bi-gear-fill"></i> Pengaturan
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-md-10 p-0">
                {{-- Top Navbar --}}
                <nav class="navbar navbar-expand-lg navbar-light px-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">Dashboard Guru</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <i class="bi bi-person-circle"></i>
                                <strong>{{ Auth::user()->name }}</strong>
                                <span class="badge bg-success ms-2">Guru</span>
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>

                {{-- Dashboard Content --}}
                <div class="p-4">
                    {{-- Pesan Error jika ada --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Welcome Message --}}
                    <div class="alert alert-success" role="alert">
                        <h5 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Selamat Datang, {{ Auth::user()->name }}!</h5>
                        <p class="mb-0">Anda login sebagai <strong>Guru</strong>. Anda dapat mengelola materi, tugas, dan melihat progress siswa.</p>
                    </div>

                    {{-- Statistics Cards --}}
                    <div class="row mt-4">
                        {{-- Card Total Materi --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Materi Saya</h6>
                                        <h2 class="mb-0">12</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 2 baru bulan ini</small>
                                    </div>
                                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Total Siswa --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Siswa Aktif</h6>
                                        <h2 class="mb-0">68</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 8 siswa baru</small>
                                    </div>
                                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Tugas Pending --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Tugas Perlu Diperiksa</h6>
                                        <h2 class="mb-0">23</h2>
                                        <small class="text-warning"><i class="bi bi-exclamation-circle"></i> Menunggu review</small>
                                    </div>
                                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-journal-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Rata-rata Nilai --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Rata-rata Nilai</h6>
                                        <h2 class="mb-0">85</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> Meningkat 3%</small>
                                    </div>
                                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Aksi Cepat</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ route('materi.create') }}" class="btn btn-outline-primary w-100 py-3">
                                                <i class="bi bi-plus-circle fs-1 d-block mb-2"></i>
                                                Tambah Materi Baru
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-success w-100 py-3">
                                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                                Buat Quiz/Tugas
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-warning w-100 py-3">
                                                <i class="bi bi-clipboard-check fs-1 d-block mb-2"></i>
                                                Periksa Tugas
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-info w-100 py-3">
                                                <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>
                                                Lihat Statistik
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru Siswa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Andi Wijaya mengumpulkan tugas</h6>
                                                <small>10 menit lalu</small>
                                            </div>
                                            <p class="mb-1">Tugas "Implementasi Neural Network" telah dikumpulkan.</p>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Siti Nurhaliza menyelesaikan quiz</h6>
                                                <small>1 jam lalu</small>
                                            </div>
                                            <p class="mb-1">Quiz "Dasar-dasar Machine Learning" - Nilai: 92</p>
                                        </div>
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
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
