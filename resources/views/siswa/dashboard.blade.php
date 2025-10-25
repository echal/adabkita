<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem Deep Learning</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .progress-custom {
            height: 25px;
            border-radius: 10px;
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
                        <a class="nav-link active" href="{{ route('siswa.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="#">
                            <i class="bi bi-book-fill"></i> Materi Belajar
                        </a>
                        <a class="nav-link" href="#">
                            <i class="bi bi-journal-check"></i> Tugas Saya
                        </a>
                        <a class="nav-link" href="#">
                            <i class="bi bi-trophy-fill"></i> Nilai & Prestasi
                        </a>
                        <a class="nav-link" href="#">
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
                        <span class="navbar-brand mb-0 h1">Dashboard Siswa</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <i class="bi bi-person-circle"></i>
                                <strong>{{ Auth::user()->name }}</strong>
                                <span class="badge bg-primary ms-2">Siswa</span>
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
                        <p class="mb-0">Anda login sebagai <strong>Siswa</strong>. Mari mulai belajar dan kembangkan kemampuan Anda!</p>
                    </div>

                    {{-- Statistics Cards --}}
                    <div class="row mt-4">
                        {{-- Card Materi Selesai --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Materi Selesai</h6>
                                        <h2 class="mb-0">8/12</h2>
                                        <small class="text-success"><i class="bi bi-check-circle"></i> 67% Progress</small>
                                    </div>
                                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Tugas Dikumpulkan --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Tugas Dikumpulkan</h6>
                                        <h2 class="mb-0">15</h2>
                                        <small class="text-warning"><i class="bi bi-exclamation-circle"></i> 3 tugas tertunda</small>
                                    </div>
                                    <div class="stats-icon bg-success bg-opacity-10 text-success">
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
                                        <h2 class="mb-0">88</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> Meningkat 5%</small>
                                    </div>
                                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Peringkat --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Peringkat Kelas</h6>
                                        <h2 class="mb-0">#5</h2>
                                        <small class="text-info"><i class="bi bi-trophy"></i> Top 10%</small>
                                    </div>
                                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Pembelajaran --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Progress Pembelajaran</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><strong>Pengenalan Machine Learning</strong></span>
                                            <span class="text-muted">100%</span>
                                        </div>
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><strong>Neural Network Dasar</strong></span>
                                            <span class="text-muted">75%</span>
                                        </div>
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><strong>Convolutional Neural Network</strong></span>
                                            <span class="text-muted">45%</span>
                                        </div>
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><strong>Recurrent Neural Network</strong></span>
                                            <span class="text-muted">10%</span>
                                        </div>
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-danger" style="width: 10%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tugas Terbaru --}}
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Tugas Terbaru</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Implementasi Neural Network</h6>
                                                <small class="text-danger">Deadline: 3 hari lagi</small>
                                            </div>
                                            <span class="badge bg-warning">Pending</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Quiz Machine Learning</h6>
                                                <small class="text-muted">Deadline: 1 minggu lagi</small>
                                            </div>
                                            <span class="badge bg-info">Belum Mulai</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Project Akhir CNN</h6>
                                                <small class="text-success">Sudah dikumpulkan</small>
                                            </div>
                                            <span class="badge bg-success">Selesai</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Prestasi Terbaru --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0"><i class="bi bi-trophy-fill"></i> Prestasi Terbaru</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <div class="stats-icon bg-warning bg-opacity-10 text-warning me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Nilai Sempurna!</h6>
                                                    <small class="text-muted">Mendapat nilai 100 pada quiz "Dasar ML"</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <div class="stats-icon bg-success bg-opacity-10 text-success me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-lightning-fill"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Streak 7 Hari</h6>
                                                    <small class="text-muted">Belajar konsisten selama 7 hari berturut-turut</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <div class="stats-icon bg-info bg-opacity-10 text-info me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-bookmark-check-fill"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Materi Lengkap</h6>
                                                    <small class="text-muted">Menyelesaikan semua materi "Neural Network"</small>
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
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
