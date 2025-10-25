<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Deep Learning</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.pengguna.index') }}">
                            <i class="bi bi-people-fill"></i> Kelola Pengguna
                        </a>
                        <a class="nav-link" href="{{ route('materi.index') }}">
                            <i class="bi bi-book-fill"></i> Kelola Materi
                        </a>
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
                        <span class="navbar-brand mb-0 h1">Dashboard Administrator</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3">
                                <i class="bi bi-person-circle"></i>
                                <strong>{{ Auth::user()->name }}</strong>
                                <span class="badge bg-danger ms-2">Admin</span>
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
                        <p class="mb-0">Anda login sebagai <strong>Administrator</strong>. Anda memiliki akses penuh ke seluruh sistem.</p>
                    </div>

                    {{-- Statistics Cards --}}
                    <div class="row mt-4">
                        {{-- Card Total Siswa --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Siswa</h6>
                                        <h2 class="mb-0">150</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 12% bulan ini</small>
                                    </div>
                                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Total Guru --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Guru</h6>
                                        <h2 class="mb-0">25</h2>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 5% bulan ini</small>
                                    </div>
                                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Total Materi --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Materi</h6>
                                        <h2 class="mb-0">48</h2>
                                        <small class="text-info"><i class="bi bi-arrow-right"></i> Stabil</small>
                                    </div>
                                    <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="bi bi-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Aktivitas Hari Ini --}}
                        <div class="col-md-3 mb-4">
                            <div class="card stats-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Aktivitas Hari Ini</h6>
                                        <h2 class="mb-0">89</h2>
                                        <small class="text-danger"><i class="bi bi-arrow-down"></i> 3% dari kemarin</small>
                                    </div>
                                    <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-activity"></i>
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
                                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Guru baru terdaftar</h6>
                                                <small>5 menit lalu</small>
                                            </div>
                                            <p class="mb-1">Budi Santoso telah mendaftar sebagai guru baru.</p>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Materi baru ditambahkan</h6>
                                                <small>1 jam lalu</small>
                                            </div>
                                            <p class="mb-1">Materi "Pengenalan Machine Learning" telah ditambahkan.</p>
                                        </div>
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
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
