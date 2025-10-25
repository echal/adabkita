{{--
    =====================================================
    HALAMAN FRONTPAGE / LANDING PAGE
    =====================================================
    Halaman ini adalah halaman pertama yang dilihat pengunjung
    saat membuka URL utama: https://adabkita.gaspul.com

    Halaman ini berisi:
    1. Navbar dengan logo dan tombol login
    2. Hero Section (judul besar + deskripsi + tombol CTA)
    3. Keunggulan AdabKita (3 fitur utama)
    4. Video penjelasan
    5. Call to Action (ajakan mendaftar/login)
    6. Footer dengan informasi hak cipta

    CARA MENGUBAH KONTEN:
    - Ubah teks di dalam tag HTML (contoh: <h1>, <p>, dll)
    - Ganti VIDEO_ID di bagian embed YouTube
    - Ubah warna di file layouts/front.blade.php (bagian <style>)

    =====================================================
--}}

@extends('layouts.front')

@section('judul', 'AdabKita - Pembelajaran Deep Learning untuk Adab Islami')

@section('konten')

{{-- =====================================================
    NAVBAR / HEADER
    ===================================================== --}}
<nav class="navbar navbar-expand-lg navbar-front navbar-dark">
    <div class="container">
        {{-- Logo / Brand Name --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            📚 AdabKita
        </a>

        {{-- Tombol toggle untuk mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Navigasi --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#keunggulan">Keunggulan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#video">Video</a>
                </li>
                <li class="nav-item">
                    {{-- Tombol Login mengarah ke halaman login --}}
                    <a href="{{ route('login') }}" class="btn btn-login-nav">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- =====================================================
    HERO SECTION (Bagian Utama dengan Judul Besar)
    ===================================================== --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="hero-content text-center">
                    {{-- Judul Utama --}}
                    <h1 class="hero-title">
                        Selamat Datang di <strong>AdabKita</strong>
                    </h1>

                    {{-- Deskripsi Singkat --}}
                    <p class="hero-description">
                        Platform pembelajaran Deep Learning untuk Adab Islami di MTsN.<br>
                        Sistem evaluasi cerdas dan pembelajaran interaktif berbasis teknologi AI.
                    </p>

                    {{-- Tombol Call-to-Action --}}
                    <div class="hero-buttons">
                        {{-- Tombol utama: Login --}}
                        <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk ke Sistem
                        </a>

                        {{-- Tombol sekunder: Lihat Video --}}
                        <a href="#video" class="btn btn-hero btn-hero-secondary">
                            <i class="bi bi-play-circle"></i> Lihat Video
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================
    SECTION: KEUNGGULAN ADABKITA
    Menampilkan 3 keunggulan utama sistem
    ===================================================== --}}
<section class="features-section" id="keunggulan">
    <div class="container">
        {{-- Judul Section --}}
        <h2 class="section-title">Keunggulan AdabKita</h2>
        <p class="section-subtitle">
            Tiga fitur utama yang membuat pembelajaran Adab Islami lebih efektif dan menyenangkan
        </p>

        {{-- Grid 3 Kolom untuk Keunggulan --}}
        <div class="row">
            {{-- Keunggulan 1: Deep Learning --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    {{-- Emoji sebagai ikon --}}
                    <span class="feature-icon">🧠</span>

                    {{-- Judul Fitur --}}
                    <h3 class="feature-title">Deep Learning AI</h3>

                    {{-- Deskripsi Fitur --}}
                    <p class="feature-description">
                        Sistem pembelajaran berbasis Deep Learning yang menganalisis pola jawaban siswa
                        dan memberikan rekomendasi pembelajaran yang dipersonalisasi untuk setiap individu.
                    </p>
                </div>
            </div>

            {{-- Keunggulan 2: Evaluasi Cerdas --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    {{-- Emoji sebagai ikon --}}
                    <span class="feature-icon">📊</span>

                    {{-- Judul Fitur --}}
                    <h3 class="feature-title">Evaluasi Cerdas</h3>

                    {{-- Deskripsi Fitur --}}
                    <p class="feature-description">
                        Penilaian otomatis dan real-time dengan analisis mendalam tentang pemahaman siswa.
                        Guru dapat melihat progress setiap siswa dengan detail dan akurat.
                    </p>
                </div>
            </div>

            {{-- Keunggulan 3: Pembelajaran Interaktif --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    {{-- Emoji sebagai ikon --}}
                    <span class="feature-icon">🎓</span>

                    {{-- Judul Fitur --}}
                    <h3 class="feature-title">Pembelajaran Interaktif</h3>

                    {{-- Deskripsi Fitur --}}
                    <p class="feature-description">
                        Materi pembelajaran yang interaktif dengan video, kuis, dan gamification.
                        Membuat proses belajar Adab Islami lebih menarik dan mudah dipahami.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================
    SECTION: TENTANG ADABKITA
    ===================================================== --}}
<section class="py-5" id="tentang">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                {{-- Gambar ilustrasi (bisa diganti dengan gambar custom) --}}
                <div class="text-center">
                    <div style="font-size: 200px; line-height: 1;">📖</div>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title text-start">Tentang AdabKita</h2>
                <p class="section-subtitle text-start" style="margin-left: 0;">
                    AdabKita adalah platform pembelajaran digital yang dikembangkan khusus untuk
                    membantu siswa MTsN mempelajari Adab Islami dengan cara yang modern dan efektif.
                </p>
                <div class="mb-3">
                    <h5><i class="bi bi-check-circle-fill text-success"></i> Materi Lengkap</h5>
                    <p class="text-muted">
                        Mencakup seluruh materi Adab Islami yang sesuai dengan kurikulum madrasah.
                    </p>
                </div>
                <div class="mb-3">
                    <h5><i class="bi bi-check-circle-fill text-success"></i> Mudah Diakses</h5>
                    <p class="text-muted">
                        Dapat diakses kapan saja, di mana saja melalui perangkat komputer atau smartphone.
                    </p>
                </div>
                <div class="mb-3">
                    <h5><i class="bi bi-check-circle-fill text-success"></i> Monitoring Real-time</h5>
                    <p class="text-muted">
                        Guru dapat memantau perkembangan siswa secara langsung dan memberikan feedback yang tepat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================
    SECTION: VIDEO PENJELASAN
    Embed video YouTube tentang AdabKita

    CARA MENGGANTI VIDEO:
    1. Cari video di YouTube yang ingin ditampilkan
    2. Klik tombol "Share" di bawah video
    3. Pilih "Embed"
    4. Salin kode VIDEO_ID dari URL
       Contoh: https://www.youtube.com/watch?v=VIDEO_ID
    5. Ganti "dQw4w9WgXcQ" di bawah dengan VIDEO_ID yang baru
    ===================================================== --}}
<section class="video-section" id="video">
    <div class="container">
        {{-- Judul Section --}}
        <h2 class="section-title">Lihat AdabKita dalam Aksi</h2>
        <p class="section-subtitle">
            Video penjelasan singkat tentang cara menggunakan platform AdabKita
        </p>

        {{-- Container Video Responsive --}}
        <div class="video-container">
            {{--
                PENTING: Ganti "dQw4w9WgXcQ" dengan ID video YouTube yang sesuai
                Cara mendapatkan ID video:
                - Buka video di YouTube
                - Lihat URL: https://www.youtube.com/watch?v=VIDEO_ID
                - Salin bagian setelah "v=" (contoh: dQw4w9WgXcQ)
            --}}
            <iframe
                src="https://www.youtube.com/embed/buBfwA7IjBM"
                title="Video Penjelasan AdabKita"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

{{-- =====================================================
    SECTION: CALL TO ACTION (CTA)
    Ajakan untuk login / mendaftar
    ===================================================== --}}
<section class="cta-section">
    <div class="container text-center">
        {{-- Judul CTA --}}
        <h2 class="cta-title">Siap Memulai Pembelajaran?</h2>

        {{-- Deskripsi CTA --}}
        <p class="cta-description">
            Bergabunglah dengan ribuan siswa lain yang telah merasakan manfaat pembelajaran interaktif AdabKita
        </p>

        {{-- Tombol CTA --}}
        <div class="hero-buttons">
            {{-- Tombol Login --}}
            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                <i class="bi bi-box-arrow-in-right"></i> Login Sekarang
            </a>

            {{-- Tombol Kontak (opsional, bisa dihapus jika tidak diperlukan) --}}
            <a href="mailto:info@adabkita.gaspul.com" class="btn btn-hero btn-hero-secondary">
                <i class="bi bi-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

{{-- =====================================================
    FOOTER
    Bagian bawah halaman dengan informasi hak cipta
    ===================================================== --}}
<footer class="footer">
    <div class="container">
        <div class="row">
            {{-- Kolom 1: Informasi AdabKita --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-brand">📚 AdabKita</div>
                <p class="footer-description">
                    Platform pembelajaran Deep Learning untuk Adab Islami di MTsN.
                    Membantu siswa belajar dengan cara yang lebih efektif dan menyenangkan.
                </p>
            </div>

            {{-- Kolom 2: Link Cepat --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-white mb-3">Link Cepat</h5>
                <ul class="footer-links">
                    <li><a href="#keunggulan">Keunggulan</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#video">Video</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-white mb-3">Kontak</h5>
                <ul class="footer-links">
                    <li>
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:info@adabkita.gaspul.com">info@adabkita.gaspul.com</a>
                    </li>
                    <li>
                        <i class="bi bi-globe"></i>
                        <a href="https://adabkita.gaspul.com">adabkita.gaspul.com</a>
                    </li>
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        MTsN - Madrasah Tsanawiyah Negeri
                    </li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="footer-bottom">
            <p class="mb-0">
                &copy; {{ date('Y') }} AdabKita - GASPUL. All Rights Reserved. |
                Powered by Deep Learning Technology
            </p>
        </div>
    </div>
</footer>

{{-- =====================================================
    [ACCESSIBILITY FEATURE] Floating Toggle Button
    Tombol aksesibilitas melayang di pojok kanan bawah
    ===================================================== --}}
<div class="accessibility-toggle-wrapper">
    {{-- Floating Button dengan gradasi AdabKita (ungu-pink) --}}
    <button
        id="accessibilityToggle"
        class="btn btn-accessibility shadow-lg"
        aria-label="Buka Pengaturan Aksesibilitas"
        title="Pengaturan Aksesibilitas">
        <i class="bi bi-universal-access-circle fs-3"></i>
    </button>

    {{-- [ACCESSIBILITY FEATURE] Panel Pengaturan Aksesibilitas --}}
    <div id="accessibilityPanel" class="accessibility-panel">
        <div class="accessibility-panel-content">
            {{-- Header Panel --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="bi bi-gear-fill text-primary"></i>
                    Aksesibilitas
                </h5>
                <button
                    id="closeAccessibilityPanel"
                    class="btn-close"
                    aria-label="Tutup Panel">
                </button>
            </div>

            {{-- Divider --}}
            <hr class="my-3">

            {{-- Opsi 1: Font Size Adjustment --}}
            <div class="accessibility-option mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-1">
                            <i class="bi bi-fonts text-info"></i>
                            Ukuran Teks
                        </h6>
                        <small class="text-muted">Sesuaikan ukuran font</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button
                            id="decreaseFontSize"
                            class="btn btn-sm btn-outline-secondary"
                            aria-label="Perkecil Teks"
                            title="Perkecil Teks">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                        <span id="fontSizeIndicator" class="badge bg-primary align-self-center">100%</span>
                        <button
                            id="increaseFontSize"
                            class="btn btn-sm btn-outline-secondary"
                            aria-label="Perbesar Teks"
                            title="Perbesar Teks">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Opsi 2: Kontras Tinggi --}}
            <div class="accessibility-option mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <i class="bi bi-circle-half text-warning"></i>
                            Kontras Tinggi
                        </h6>
                        <small class="text-muted">Mode gelap untuk penglihatan lebih baik</small>
                    </div>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="toggleHighContrast"
                            role="switch">
                    </div>
                </div>
            </div>

            {{-- Opsi 3: Text Spacing --}}
            <div class="accessibility-option mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <i class="bi bi-text-paragraph text-purple"></i>
                            Spasi Teks
                        </h6>
                        <small class="text-muted">Jarak antar teks lebih longgar</small>
                    </div>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="toggleTextSpacing"
                            role="switch">
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <hr class="my-3">

            {{-- Tombol Reset Default --}}
            <button
                id="resetAccessibility"
                class="btn btn-outline-danger w-100">
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset ke Default
            </button>

            {{-- Info tambahan --}}
            <div class="mt-3 p-2 bg-light rounded">
                <small class="text-muted d-block text-center">
                    <i class="bi bi-info-circle"></i>
                    Pengaturan akan tersimpan otomatis
                </small>
            </div>
        </div>
    </div>
</div>

{{-- =====================================================
    [ACCESSIBILITY FEATURE] Text-to-Speech Floating Button
    Tombol untuk membaca isi halaman dengan Web Speech API
    ===================================================== --}}
<div class="text-to-speech-wrapper">
    <button
        id="textToSpeechButton"
        class="btn btn-speech shadow-lg"
        aria-label="Baca Halaman"
        title="Baca Halaman (Text-to-Speech)">
        <i class="bi bi-volume-up-fill fs-4"></i>
        <span class="speech-status-text d-none d-md-inline ms-2">Baca</span>
    </button>
</div>

{{-- [ACCESSIBILITY FEATURE] Custom CSS untuk Accessibility --}}
<style>
    /* Wrapper untuk accessibility toggle */
    .accessibility-toggle-wrapper {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
    }

    /* Floating Button dengan gradasi ungu-pink AdabKita */
    .btn-accessibility {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    .btn-accessibility:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-accessibility:active {
        transform: scale(0.95);
    }

    /* Animasi pulse untuk menarik perhatian */
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        50% {
            box-shadow: 0 4px 25px rgba(102, 126, 234, 0.6);
        }
    }

    /* [ACCESSIBILITY FEATURE] Text-to-Speech Floating Button */
    .text-to-speech-wrapper {
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 9999;
    }

    .btn-speech {
        min-width: 60px;
        height: 60px;
        border-radius: 50px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0 20px;
        animation: pulse-green 2s infinite;
    }

    .btn-speech:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
    }

    .btn-speech:active {
        transform: translateY(0);
    }

    .btn-speech.speaking {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        animation: pulse-red 1s infinite;
    }

    .btn-speech.speaking:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    @keyframes pulse-green {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        50% {
            box-shadow: 0 4px 25px rgba(16, 185, 129, 0.6);
        }
    }

    @keyframes pulse-red {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        50% {
            box-shadow: 0 4px 25px rgba(239, 68, 68, 0.6);
        }
    }

    /* Panel Pengaturan Aksesibilitas */
    .accessibility-panel {
        position: fixed;
        bottom: 100px;
        right: 24px;
        width: 350px;
        max-width: calc(100vw - 48px);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 9998;
    }

    /* Panel ketika aktif */
    .accessibility-panel.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* Konten panel */
    .accessibility-panel-content {
        padding: 20px;
    }

    /* Styling untuk setiap opsi */
    .accessibility-option {
        padding: 12px;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }

    .accessibility-option:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    /* Switch toggle styling */
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* [ACCESSIBILITY FEATURE] Font Size Adjustment (Dynamic) */
    body[data-font-size="80"] {
        font-size: 80% !important;
    }

    body[data-font-size="90"] {
        font-size: 90% !important;
    }

    body[data-font-size="100"] {
        font-size: 100% !important;
    }

    body[data-font-size="110"] {
        font-size: 110% !important;
    }

    body[data-font-size="120"] {
        font-size: 120% !important;
    }

    body[data-font-size="130"] {
        font-size: 130% !important;
    }

    body[data-font-size="140"] {
        font-size: 140% !important;
    }

    body[data-font-size="150"] {
        font-size: 150% !important;
    }

    /* [ACCESSIBILITY FEATURE] Text Spacing Mode */
    body.text-spacing {
        line-height: 1.8 !important;
        letter-spacing: 0.08em !important;
        word-spacing: 0.16em !important;
    }

    body.text-spacing h1,
    body.text-spacing h2,
    body.text-spacing h3,
    body.text-spacing h4,
    body.text-spacing h5,
    body.text-spacing h6 {
        line-height: 1.6 !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 1.5rem !important;
    }

    body.text-spacing p {
        line-height: 2 !important;
        margin-bottom: 1.5rem !important;
    }

    body.text-spacing li {
        line-height: 2 !important;
        margin-bottom: 0.75rem !important;
    }

    body.text-spacing .btn {
        letter-spacing: 0.1em !important;
        padding: 0.75rem 1.75rem !important;
    }

    /* [ACCESSIBILITY FEATURE] Mode Kontras Tinggi */
    body.high-contrast {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    body.high-contrast .navbar,
    body.high-contrast .hero,
    body.high-contrast section {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    body.high-contrast .card,
    body.high-contrast .feature-card {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
        border-color: #444444 !important;
    }

    body.high-contrast h1,
    body.high-contrast h2,
    body.high-contrast h3,
    body.high-contrast h4,
    body.high-contrast h5,
    body.high-contrast h6 {
        color: #ffffff !important;
    }

    body.high-contrast p,
    body.high-contrast .text-muted {
        color: #e0e0e0 !important;
    }

    body.high-contrast a {
        color: #4db8ff !important;
    }

    body.high-contrast .btn-primary {
        background-color: #0066cc !important;
        border-color: #0066cc !important;
        color: #ffffff !important;
    }

    body.high-contrast .bg-light {
        background-color: #2a2a2a !important;
    }

    body.high-contrast footer {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    /* Panel aksesibilitas dalam mode kontras tinggi */
    body.high-contrast .accessibility-panel {
        background: rgba(26, 26, 26, 0.98) !important;
        color: #ffffff !important;
    }

    body.high-contrast .accessibility-panel h5,
    body.high-contrast .accessibility-panel h6 {
        color: #ffffff !important;
    }

    body.high-contrast .accessibility-option:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Responsive untuk mobile */
    @media (max-width: 768px) {
        .accessibility-toggle-wrapper {
            bottom: 16px;
            right: 16px;
        }

        .btn-accessibility {
            width: 50px;
            height: 50px;
        }

        .btn-accessibility i {
            font-size: 1.5rem !important;
        }

        .accessibility-panel {
            bottom: 80px;
            right: 16px;
            width: calc(100vw - 32px);
        }

        /* [ACCESSIBILITY FEATURE] Text-to-Speech responsive */
        .text-to-speech-wrapper {
            bottom: 16px;
            left: 16px;
        }

        .btn-speech {
            min-width: 50px;
            height: 50px;
            padding: 0 15px;
        }

        .btn-speech i {
            font-size: 1.25rem !important;
        }

        .speech-status-text {
            display: none !important;
        }
    }
</style>

@endsection

{{-- =====================================================
    JAVASCRIPT TAMBAHAN (Opsional)
    ===================================================== --}}
@push('js_tambahan')
<script>
    // Script untuk animasi fade-in saat scroll
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer untuk animasi saat scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe semua feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });

    // =====================================================
    // [ACCESSIBILITY FEATURE] JavaScript untuk Accessibility Toggle
    // =====================================================
    document.addEventListener('DOMContentLoaded', function() {
        // Element references - Panel Aksesibilitas
        const toggleButton = document.getElementById('accessibilityToggle');
        const panel = document.getElementById('accessibilityPanel');
        const closeButton = document.getElementById('closeAccessibilityPanel');
        const increaseFontBtn = document.getElementById('increaseFontSize');
        const decreaseFontBtn = document.getElementById('decreaseFontSize');
        const fontSizeIndicator = document.getElementById('fontSizeIndicator');
        const highContrastToggle = document.getElementById('toggleHighContrast');
        const textSpacingToggle = document.getElementById('toggleTextSpacing');
        const resetButton = document.getElementById('resetAccessibility');

        // Element references - Text-to-Speech
        const ttsButton = document.getElementById('textToSpeechButton');
        const ttsIcon = ttsButton.querySelector('i');
        const ttsText = ttsButton.querySelector('.speech-status-text');

        // localStorage keys
        const STORAGE_KEYS = {
            fontSize: 'accessibility_fontSize',
            highContrast: 'accessibility_highContrast',
            textSpacing: 'accessibility_textSpacing'
        };

        // State variables
        let currentFontSize = 100; // Default 100%
        let isSpeaking = false;
        let speechUtterance = null;

        // =====================================================
        // FUNGSI: Load Preferensi dari localStorage
        // =====================================================
        function loadPreferences() {
            // Load Font Size preference
            const savedFontSize = localStorage.getItem(STORAGE_KEYS.fontSize);
            if (savedFontSize) {
                currentFontSize = parseInt(savedFontSize);
                applyFontSize(currentFontSize);
            }

            // Load High Contrast preference
            const highContrastEnabled = localStorage.getItem(STORAGE_KEYS.highContrast) === 'true';
            if (highContrastEnabled) {
                document.body.classList.add('high-contrast');
                highContrastToggle.checked = true;
            }

            // Load Text Spacing preference
            const textSpacingEnabled = localStorage.getItem(STORAGE_KEYS.textSpacing) === 'true';
            if (textSpacingEnabled) {
                document.body.classList.add('text-spacing');
                textSpacingToggle.checked = true;
            }
        }

        // =====================================================
        // FUNGSI: Apply Font Size
        // =====================================================
        function applyFontSize(size) {
            document.body.setAttribute('data-font-size', size);
            fontSizeIndicator.textContent = size + '%';
            currentFontSize = size;
        }

        // =====================================================
        // FUNGSI: Simpan Preferensi ke localStorage
        // =====================================================
        function savePreference(key, value) {
            localStorage.setItem(key, value);
            console.log(`✅ Preferensi disimpan: ${key} = ${value}`);
        }

        // =====================================================
        // EVENT: Toggle Panel Visibility
        // =====================================================
        toggleButton.addEventListener('click', function() {
            panel.classList.toggle('active');

            // Update ARIA attributes untuk accessibility
            const isOpen = panel.classList.contains('active');
            toggleButton.setAttribute('aria-expanded', isOpen);

            // Animasi button saat panel terbuka
            if (isOpen) {
                toggleButton.style.transform = 'rotate(180deg)';
            } else {
                toggleButton.style.transform = 'rotate(0deg)';
            }
        });

        // =====================================================
        // EVENT: Close Panel
        // =====================================================
        closeButton.addEventListener('click', function() {
            panel.classList.remove('active');
            toggleButton.setAttribute('aria-expanded', 'false');
            toggleButton.style.transform = 'rotate(0deg)';
        });

        // Tutup panel saat klik di luar
        document.addEventListener('click', function(event) {
            const isClickInside = panel.contains(event.target) || toggleButton.contains(event.target);

            if (!isClickInside && panel.classList.contains('active')) {
                panel.classList.remove('active');
                toggleButton.setAttribute('aria-expanded', 'false');
                toggleButton.style.transform = 'rotate(0deg)';
            }
        });

        // =====================================================
        // EVENT: Increase Font Size
        // =====================================================
        increaseFontBtn.addEventListener('click', function() {
            if (currentFontSize < 150) {
                currentFontSize += 10;
                applyFontSize(currentFontSize);
                savePreference(STORAGE_KEYS.fontSize, currentFontSize);
                showNotification(`Ukuran teks: ${currentFontSize}%`, 'success');
            } else {
                showNotification('Ukuran maksimum tercapai (150%)', 'warning');
            }
        });

        // =====================================================
        // EVENT: Decrease Font Size
        // =====================================================
        decreaseFontBtn.addEventListener('click', function() {
            if (currentFontSize > 80) {
                currentFontSize -= 10;
                applyFontSize(currentFontSize);
                savePreference(STORAGE_KEYS.fontSize, currentFontSize);
                showNotification(`Ukuran teks: ${currentFontSize}%`, 'success');
            } else {
                showNotification('Ukuran minimum tercapai (80%)', 'warning');
            }
        });

        // =====================================================
        // EVENT: Toggle High Contrast (Kontras Tinggi)
        // =====================================================
        highContrastToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('high-contrast');
                savePreference(STORAGE_KEYS.highContrast, 'true');
                showNotification('Mode kontras tinggi aktif', 'success');
            } else {
                document.body.classList.remove('high-contrast');
                savePreference(STORAGE_KEYS.highContrast, 'false');
                showNotification('Mode kontras tinggi dimatikan', 'info');
            }
        });

        // =====================================================
        // EVENT: Toggle Text Spacing (Spasi Teks)
        // =====================================================
        textSpacingToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('text-spacing');
                savePreference(STORAGE_KEYS.textSpacing, 'true');
                showNotification('Spasi teks diperlebar', 'success');
            } else {
                document.body.classList.remove('text-spacing');
                savePreference(STORAGE_KEYS.textSpacing, 'false');
                showNotification('Spasi teks dikembalikan', 'info');
            }
        });

        // =====================================================
        // EVENT: Reset ke Default
        // =====================================================
        resetButton.addEventListener('click', function() {
            // Konfirmasi terlebih dahulu
            if (confirm('Yakin ingin reset semua pengaturan aksesibilitas ke default?')) {
                // Reset font size ke 100%
                currentFontSize = 100;
                applyFontSize(currentFontSize);

                // Hapus semua class
                document.body.classList.remove('high-contrast');
                document.body.classList.remove('text-spacing');

                // Uncheck semua toggle
                highContrastToggle.checked = false;
                textSpacingToggle.checked = false;

                // Hapus dari localStorage
                localStorage.removeItem(STORAGE_KEYS.fontSize);
                localStorage.removeItem(STORAGE_KEYS.highContrast);
                localStorage.removeItem(STORAGE_KEYS.textSpacing);

                showNotification('Pengaturan direset ke default', 'success');

                console.log('✅ Semua preferensi aksesibilitas telah direset');
            }
        });

        // =====================================================
        // FUNGSI: Show Notification (Toast-like)
        // =====================================================
        function showNotification(message, type = 'info') {
            // Cek apakah sudah ada notifikasi
            const existingNotif = document.querySelector('.accessibility-notification');
            if (existingNotif) {
                existingNotif.remove();
            }

            // Buat element notifikasi
            const notification = document.createElement('div');
            notification.className = 'accessibility-notification';
            notification.style.cssText = `
                position: fixed;
                bottom: 100px;
                left: 50%;
                transform: translateX(-50%) translateY(100px);
                background-color: ${type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#17a2b8'};
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                font-size: 14px;
                font-weight: 500;
                transition: transform 0.3s ease;
            `;
            notification.textContent = message;

            // Tambahkan ke body
            document.body.appendChild(notification);

            // Animasi masuk
            setTimeout(() => {
                notification.style.transform = 'translateX(-50%) translateY(0)';
            }, 10);

            // Hapus setelah 3 detik
            setTimeout(() => {
                notification.style.transform = 'translateX(-50%) translateY(100px)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // =====================================================
        // [ACCESSIBILITY FEATURE] TEXT-TO-SPEECH FUNCTIONALITY
        // =====================================================

        // FUNGSI: Extract readable text from page
        function getPageContent() {
            const contentSelectors = [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'p', '.lead', '.description',
                '.feature-card h3', '.feature-card p'
            ];

            let textContent = [];

            contentSelectors.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                elements.forEach(el => {
                    // Skip elements inside navbar and footer
                    if (!el.closest('.navbar') && !el.closest('footer') &&
                        !el.closest('.accessibility-panel') &&
                        !el.closest('.text-to-speech-wrapper')) {
                        const text = el.textContent.trim();
                        if (text && text.length > 0) {
                            textContent.push(text);
                        }
                    }
                });
            });

            return textContent.join('. ');
        }

        // FUNGSI: Start Text-to-Speech
        function startSpeech() {
            if ('speechSynthesis' in window) {
                const textToRead = getPageContent();

                if (!textToRead) {
                    showNotification('Tidak ada teks untuk dibaca', 'warning');
                    return;
                }

                // Cancel any ongoing speech
                window.speechSynthesis.cancel();

                // Create utterance
                speechUtterance = new SpeechSynthesisUtterance(textToRead);
                speechUtterance.lang = 'id-ID'; // Bahasa Indonesia
                speechUtterance.rate = 0.9; // Kecepatan normal
                speechUtterance.pitch = 1.0;
                speechUtterance.volume = 1.0;

                // Event handlers
                speechUtterance.onstart = function() {
                    isSpeaking = true;
                    ttsButton.classList.add('speaking');
                    ttsIcon.className = 'bi bi-pause-circle-fill fs-4';
                    if (ttsText) ttsText.textContent = 'Hentikan';
                    showNotification('Memulai pembacaan halaman...', 'success');
                };

                speechUtterance.onend = function() {
                    stopSpeech();
                    showNotification('Pembacaan selesai', 'info');
                };

                speechUtterance.onerror = function(event) {
                    console.error('Speech synthesis error:', event);
                    stopSpeech();
                    showNotification('Terjadi kesalahan pada text-to-speech', 'warning');
                };

                // Start speaking
                window.speechSynthesis.speak(speechUtterance);
            } else {
                showNotification('Browser Anda tidak mendukung Text-to-Speech', 'warning');
            }
        }

        // FUNGSI: Stop Text-to-Speech
        function stopSpeech() {
            if (window.speechSynthesis) {
                window.speechSynthesis.cancel();
            }
            isSpeaking = false;
            ttsButton.classList.remove('speaking');
            ttsIcon.className = 'bi bi-volume-up-fill fs-4';
            if (ttsText) ttsText.textContent = 'Baca';
        }

        // EVENT: Text-to-Speech Button Click
        ttsButton.addEventListener('click', function() {
            if (isSpeaking) {
                stopSpeech();
                showNotification('Pembacaan dihentikan', 'info');
            } else {
                startSpeech();
            }
        });

        // Stop speech when user navigates away
        window.addEventListener('beforeunload', function() {
            if (isSpeaking) {
                stopSpeech();
            }
        });

        // =====================================================
        // INIT: Load preferensi saat halaman dimuat
        // =====================================================
        loadPreferences();

        // Log untuk debugging
        console.log('✅ Accessibility Features initialized');
        console.log('📊 Current preferences:', {
            fontSize: localStorage.getItem(STORAGE_KEYS.fontSize) || '100',
            highContrast: localStorage.getItem(STORAGE_KEYS.highContrast),
            textSpacing: localStorage.getItem(STORAGE_KEYS.textSpacing)
        });
        console.log('🎤 Text-to-Speech:', 'speechSynthesis' in window ? 'Available' : 'Not supported');
    });
</script>
@endpush
