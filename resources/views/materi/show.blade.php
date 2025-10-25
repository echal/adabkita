{{--
    View: Detail Materi Pembelajaran

    Halaman ini menampilkan detail lengkap dari materi pembelajaran.
    Dapat diakses oleh semua role (Admin, Guru, dan Siswa).

    Fitur:
    - Menampilkan semua informasi materi
    - Tombol download file jika ada
    - Tombol edit dan hapus untuk Admin & Guru
    - Tampilan yang rapi dan mudah dibaca

    @created 2025-10-15
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Detail Materi Pembelajaran')

@section('isi_halaman')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Card Detail Materi --}}
            <div class="card shadow-sm">
                {{-- Header Card --}}
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book-half me-2"></i>Detail Materi Pembelajaran
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Tombol Navigasi --}}
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Materi
                        </a>

                        {{-- Tombol Aksi untuk Admin & Guru --}}
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
                        <div>
                            <a href="{{ route('materi.edit', $materi->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>Edit Materi
                            </a>
                            <form action="{{ route('materi.destroy', $materi->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    {{-- Judul Materi --}}
                    <div class="mb-4">
                        <h2 class="text-primary border-bottom pb-3">
                            {{ $materi->judul_materi }}
                        </h2>
                    </div>

                    {{-- Informasi Materi --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-tag fs-3 text-info"></i>
                                    <p class="mb-1 mt-2"><small class="text-muted">Kategori</small></p>
                                    <h6 class="mb-0">
                                        <span class="badge bg-info">{{ $materi->kategori }}</span>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-person fs-3 text-success"></i>
                                    <p class="mb-1 mt-2"><small class="text-muted">Dibuat Oleh</small></p>
                                    <h6 class="mb-0">{{ $materi->dibuat_oleh }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-event fs-3 text-warning"></i>
                                    <p class="mb-1 mt-2"><small class="text-muted">Tanggal Upload</small></p>
                                    <h6 class="mb-0">{{ $materi->tanggal_upload->format('d M Y') }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark fs-3 text-danger"></i>
                                    <p class="mb-1 mt-2"><small class="text-muted">File Pendukung</small></p>
                                    @if($materi->file_materi)
                                    <a href="{{ $materi->url_file }}" target="_blank" class="btn btn-sm btn-primary mt-1">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                    @else
                                    <h6 class="mb-0 text-muted">Tidak ada</h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi Materi --}}
                    <div class="mb-4">
                        <div class="card border-start border-primary border-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Deskripsi Materi
                                </h5>
                                <p class="card-text text-justify" style="line-height: 1.8;">
                                    {{ $materi->deskripsi }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Isi Materi Lengkap --}}
                    <div class="mb-4">
                        <div class="card border-start border-success border-4">
                            <div class="card-body">
                                <h5 class="card-title text-success mb-3">
                                    <i class="bi bi-book me-2"></i>Isi Materi Lengkap
                                </h5>
                                <div class="materi-content">
                                    {!! nl2br(e($materi->isi_materi)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =====================================================
                         BAGIAN TAMPILAN MATERI BERDASARKAN TIPE
                         =====================================================

                         PRIORITAS TAMPILAN:
                         1. Jika ada link_embed → Tampilkan iframe embed
                         2. Jika file PPT/PPTX → Tampilkan Office Viewer + tombol download
                         3. Jika file PDF → Tampilkan PDF viewer
                         4. Jika file lain → Tampilkan tombol download saja
                    --}}

                    {{-- MODE 1: TAMPILAN LINK EMBED (GOOGLE SLIDES / ONEDRIVE) --}}
                    @if($materi->hasEmbed())
                        <div class="materi-embed-section mb-4">
                            <div class="card border-start border-info border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title text-info mb-0">
                                            <i class="bi bi-globe me-2"></i>🌐 Materi Interaktif
                                        </h5>
                                        <span class="badge bg-info">Embed</span>
                                    </div>

                                    {{-- Info: Materi menggunakan embed --}}
                                    <div class="alert alert-success mb-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Materi ini ditampilkan dari sumber eksternal (Google Slides / OneDrive).
                                        Anda dapat melihat dan berinteraksi langsung dengan materi.
                                    </div>

                                    {{-- Iframe untuk menampilkan embed --}}
                                    <div class="embed-container">
                                        <iframe
                                            src="{{ $materi->link_embed }}"
                                            width="100%"
                                            height="600px"
                                            frameborder="0"
                                            allowfullscreen
                                            class="rounded shadow-sm">
                                        </iframe>
                                    </div>

                                    <p class="text-muted small mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Jika materi tidak tampil, pastikan link embed valid.
                                    </p>
                                </div>
                            </div>
                        </div>

                    {{-- MODE 2: TAMPILAN FILE POWERPOINT (.ppt / .pptx) --}}
                    @elseif($materi->isPowerPoint())
                        <div class="materi-powerpoint-section mb-4">
                            <div class="card border-start border-danger border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title text-danger mb-0">
                                            <i class="bi bi-file-earmark-slides me-2"></i>Materi PowerPoint
                                        </h5>
                                        <div>
                                            <span class="badge bg-danger me-2">{{ strtoupper($materi->getFileExtension()) }}</span>
                                            {{-- Tombol Unduh --}}
                                            <a href="{{ $materi->url_file }}"
                                               download
                                               class="btn btn-success">
                                                <i class="bi bi-download me-1"></i>Unduh Materi PPT
                                            </a>
                                        </div>
                                    </div>

                                    {{-- KONDISI 1: OFFICE VIEWER TERSEDIA (PRODUCTION) --}}
                                    @if($materi->canUseOfficeViewer())
                                        {{-- Info: Preview PowerPoint dengan Office Viewer --}}
                                        <div class="alert alert-success mb-3">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <strong>Preview Online Tersedia:</strong> Materi PowerPoint dapat dilihat langsung.
                                            Untuk hasil terbaik, silakan <strong>unduh file</strong> dan buka di PowerPoint.
                                        </div>

                                        {{-- Office Viewer untuk preview PPT online --}}
                                        <div class="powerpoint-viewer-container">
                                            <iframe
                                                src="{{ $materi->getOfficeViewerUrl() }}"
                                                width="100%"
                                                height="600px"
                                                frameborder="0"
                                                class="rounded shadow-sm">
                                            </iframe>
                                        </div>

                                        <p class="text-muted small mt-2 mb-0">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Preview menggunakan Microsoft Office Online. Jika tidak tampil, klik <strong>"Unduh Materi PPT"</strong>.
                                        </p>

                                    {{-- KONDISI 2: OFFICE VIEWER TIDAK TERSEDIA (LOCALHOST) --}}
                                    @else
                                        {{-- Info: File PowerPoint dengan preview visual --}}
                                        <div class="alert alert-info mb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-2 text-center">
                                                    <i class="bi bi-file-earmark-slides display-1 text-danger"></i>
                                                </div>
                                                <div class="col-md-10">
                                                    <h5 class="mb-2">
                                                        <i class="bi bi-info-circle me-2"></i>File PowerPoint Tersedia
                                                    </h5>
                                                    <p class="mb-2">
                                                        File: <strong>{{ $materi->file_materi }}</strong>
                                                    </p>
                                                    <p class="mb-3">
                                                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                                        Preview online tidak tersedia di environment development.
                                                        Silakan <strong>unduh file</strong> untuk melihat materi lengkap.
                                                    </p>
                                                    <div>
                                                        <a href="{{ $materi->url_file }}"
                                                           download
                                                           class="btn btn-success btn-lg">
                                                            <i class="bi bi-download me-2"></i>Unduh Materi PPT
                                                        </a>
                                                        <a href="{{ $materi->url_file }}"
                                                           target="_blank"
                                                           class="btn btn-outline-primary btn-lg ms-2">
                                                            <i class="bi bi-box-arrow-up-right me-2"></i>Buka di Tab Baru
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info tambahan untuk localhost --}}
                                        <div class="alert alert-light border mt-3 mb-0">
                                            <small>
                                                <strong><i class="bi bi-lightbulb me-1"></i>Tips:</strong>
                                                Jika ingin materi dapat dilihat langsung tanpa download, gunakan fitur
                                                <strong>"Link Embed"</strong> dari Google Slides saat menambah/edit materi.
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    {{-- MODE 3: TAMPILAN FILE PDF --}}
                    @elseif($materi->isPDF())
                        <div class="materi-pdf-section mb-4">
                            <div class="card border-start border-danger border-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title text-danger mb-0">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>Materi PDF
                                        </h5>
                                        <div>
                                            <span class="badge bg-danger me-2">PDF</span>
                                            <a href="{{ $materi->url_file }}" download class="btn btn-success btn-sm">
                                                <i class="bi bi-download me-1"></i>Unduh PDF
                                            </a>
                                        </div>
                                    </div>

                                    {{-- PDF Viewer --}}
                                    <div class="pdf-viewer-container">
                                        <embed
                                            src="{{ $materi->url_file }}"
                                            type="application/pdf"
                                            width="100%"
                                            height="600px"
                                            class="rounded shadow-sm">
                                    </div>

                                    <p class="text-muted small mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Jika PDF tidak tampil, klik <strong>"Unduh PDF"</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                    {{-- MODE 4: FILE LAIN (DOC, DOCX, dll) --}}
                    @elseif($materi->file_materi)
                        <div class="materi-file-section mb-4">
                            <div class="card border-start border-warning border-4">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">
                                        <i class="bi bi-paperclip me-2"></i>File Pendukung
                                    </h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-file-earmark fs-1 text-primary"></i>
                                            <span class="ms-3">
                                                <strong>{{ $materi->file_materi }}</strong>
                                                <br>
                                                <span class="badge bg-primary">{{ strtoupper($materi->getFileExtension()) }}</span>
                                                <small class="text-muted ms-2">Klik tombol untuk mengunduh</small>
                                            </span>
                                        </div>
                                        <a href="{{ $materi->url_file }}" download class="btn btn-primary">
                                            <i class="bi bi-download me-2"></i>Download File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Informasi Tambahan --}}
                    <div class="alert alert-light border mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Terakhir diupdate: {{ $materi->updated_at->format('d M Y, H:i') }} WIB
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="bi bi-key me-1"></i>
                                    ID Materi: #{{ $materi->id }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Bawah --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>

                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
                        <a href="{{ route('materi.edit', $materi->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit Materi
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Custom CSS --}}
@push('styles')
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .text-justify {
        text-align: justify;
    }

    .materi-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .border-4 {
        border-width: 4px !important;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.2rem;
    }

    .row .col-md-3 {
        margin-bottom: 15px;
    }

    /* ==========================================
       STYLING UNTUK EMBED & POWERPOINT VIEWER
       ========================================== */

    /* Container untuk embed/iframe agar responsive */
    .embed-container,
    .powerpoint-viewer-container,
    .pdf-viewer-container {
        position: relative;
        width: 100%;
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #dee2e6;
    }

    .embed-container iframe,
    .powerpoint-viewer-container iframe,
    .pdf-viewer-container embed {
        display: block;
        border: none;
    }

    /* Hover effect untuk tombol download */
    .btn-success:hover,
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    /* Badge styling untuk file type */
    .badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.7rem;
    }

    /* Loading indicator untuk iframe (optional) */
    .embed-container::before,
    .powerpoint-viewer-container::before {
        content: "Memuat materi...";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #6c757d;
        font-size: 1.1rem;
        z-index: -1;
    }
</style>
@endpush

{{-- Custom JavaScript --}}
@push('scripts')
<script>
    // Print materi (opsional)
    function printMateri() {
        window.print();
    }

    // Tambahkan tombol print jika diperlukan
    // Bisa diaktifkan dengan menambahkan tombol di HTML
</script>
@endpush
