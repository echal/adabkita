<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul halaman yang bisa diubah dari halaman lain --}}
    <title>@yield('judul', 'Sistem Deep Learning')</title>

    {{-- Bootstrap 5 CSS dari CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons untuk ikon-ikon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- CSS Khusus (jika ada) --}}
    @stack('css_tambahan')

    <style>
        /* Style dasar untuk semua halaman */
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Style untuk sidebar */
        .sidebar {
            min-height: 100vh;
            color: white;
            position: sticky;
            top: 0;
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

        /* Style untuk navbar atas */
        .navbar-atas {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Style untuk card */
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

        /* Warna untuk setiap role */
        .sidebar-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .sidebar-guru {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .sidebar-siswa {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        /* Style untuk konten utama */
        .konten-utama {
            padding: 0;
        }

        /* Alert yang bisa ditutup */
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    {{-- Konten utama halaman --}}
    @yield('konten')

    {{-- Bootstrap 5 JS Bundle (termasuk Popper untuk dropdown, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert2 untuk popup konfirmasi, notifikasi, dan validasi (CDN) --}}
    {{-- Digunakan untuk fitur lesson interaktif: validasi jawaban, popup motivasi, dll --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- JavaScript tambahan (jika ada) --}}
    @stack('js_tambahan')
</body>
</html>
