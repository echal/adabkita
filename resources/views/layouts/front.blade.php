<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul halaman yang bisa diubah dari halaman lain --}}
    <title>@yield('judul', 'AdabKita - Pembelajaran Deep Learning')</title>

    {{-- Meta Description untuk SEO --}}
    <meta name="description" content="AdabKita - Platform pembelajaran Deep Learning untuk Adab Islami di MTsN. Sistem evaluasi cerdas dan pembelajaran interaktif berbasis teknologi AI.">

    {{-- Bootstrap 5 CSS dari CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons untuk ikon-ikon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Google Fonts - Poppins untuk tampilan modern --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Khusus untuk Frontpage --}}
    <style>
        /* ========================================
           STYLE DASAR
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #333;
            overflow-x: hidden;
        }

        /* ========================================
           NAVBAR / HEADER
           ======================================== */
        .navbar-front {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-login-nav {
            background: white;
            color: #667eea;
            border: 2px solid white;
            padding: 8px 24px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login-nav:hover {
            background: transparent;
            color: white;
            border-color: white;
            transform: translateY(-2px);
        }

        /* ========================================
           SECTION UMUM
           ======================================== */
        section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ========================================
           HERO SECTION
           ======================================== */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-description {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out;
        }

        .hero-buttons {
            animation: fadeInUp 1.2s ease-out;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-hero-primary {
            background: white;
            color: #667eea;
            border: 2px solid white;
        }

        .btn-hero-primary:hover {
            background: transparent;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }

        /* ========================================
           FEATURES / KEUNGGULAN SECTION
           ======================================== */
        .features-section {
            background: #f8f9ff;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            height: 100%;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #666;
            line-height: 1.7;
            font-size: 1rem;
        }

        /* ========================================
           VIDEO SECTION
           ======================================== */
        .video-section {
            background: white;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* Aspect ratio 16:9 */
            height: 0;
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 20px;
        }

        /* ========================================
           CTA (Call To Action) SECTION
           ======================================== */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        /* ========================================
           FOOTER
           ======================================== */
        .footer {
            background: #2d3748;
            color: rgba(255, 255, 255, 0.8);
            padding: 40px 0 20px;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-description {
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* ========================================
           ANIMASI
           ======================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        /* ========================================
           RESPONSIVE DESIGN
           ======================================== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-description {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .btn-hero {
                padding: 12px 30px;
                font-size: 1rem;
                display: block;
                width: 100%;
                max-width: 300px;
                margin: 10px auto;
            }

            .feature-card {
                margin-bottom: 20px;
            }

            .cta-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .hero-section {
                padding: 80px 0 60px;
            }

            section {
                padding: 60px 0;
            }

            .feature-icon {
                font-size: 3rem;
            }
        }
    </style>

    {{-- CSS tambahan (opsional dari halaman child) --}}
    @stack('css_tambahan')
</head>
<body>
    {{-- Konten utama halaman --}}
    @yield('konten')

    {{-- Bootstrap 5 JS Bundle (termasuk Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JavaScript tambahan (opsional dari halaman child) --}}
    @stack('js_tambahan')

    {{-- Script untuk smooth scrolling --}}
    <script>
        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
