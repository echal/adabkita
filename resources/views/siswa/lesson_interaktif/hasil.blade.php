{{--
=====================================================
HALAMAN HASIL LESSON FLOW UNTUK SISWA
=====================================================
Menampilkan hasil pembelajaran siswa setelah menyelesaikan
lesson flow, termasuk skor, persentase, dan detail jawaban.

@package Resources\Views\Siswa\LessonInteraktif
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Hasil Pembelajaran')

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient text-white">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy-fill display-1 mb-3"></i>
                    <h2 class="fw-bold mb-2">{{ $lessonFlow->judul_materi }}</h2>
                    <h4>Selamat! Anda telah menyelesaikan lesson ini</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- PHASE 4: BADGE ACHIEVEMENT CARD --}}
    {{-- ============================================ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-{{ $badgeColor }} badge-achievement-card">
                <div class="card-body text-center py-5">
                    <div class="badge-image-container mb-3">
                        <img src="{{ asset('badges/' . $badge . '.png') }}"
                             alt="{{ $badgeLabel }} Badge"
                             class="badge-image animate-badge"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="badge-emoji-fallback" style="display:none;">
                            <span style="font-size: 120px;">{{ $badgeIcon }}</span>
                        </div>
                    </div>

                    <h3 class="fw-bold text-{{ $badgeColor }} mb-3">
                        {{ $badgeIcon }} Badge {{ $badgeLabel }}
                    </h3>

                    <p class="lead mb-4">{{ $badgeMessage }}</p>

                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="achievement-stat">
                                <h4 class="fw-bold text-{{ $badgeColor }}">{{ $persentase }}%</h4>
                                <small class="text-muted">Skor Akhir</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="achievement-stat">
                                <h4 class="fw-bold text-{{ $badgeColor }}">{{ $totalBenar }}/{{ $totalSoal }}</h4>
                                <small class="text-muted">Benar</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="achievement-stat">
                                <h4 class="fw-bold text-{{ $badgeColor }}">{{ $durasi }}</h4>
                                <small class="text-muted">Durasi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Utama --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $totalPoin }}</h3>
                    <p class="text-muted mb-0">Total Poin</p>
                    <small class="text-muted">dari {{ $maxPoin }} poin</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-percent text-primary display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $persentase }}%</h3>
                    <p class="text-muted mb-0">Persentase</p>
                    <small class="text-muted">Kebenaran</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-success">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $totalBenar }}</h3>
                    <p class="text-muted mb-0">Jawaban Benar</p>
                    <small class="text-muted">dari {{ $totalSoal }} soal</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-danger">
                <div class="card-body">
                    <i class="bi bi-x-circle-fill text-danger display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $totalSalah }}</h3>
                    <p class="text-muted mb-0">Jawaban Salah</p>
                    <small class="text-muted">perlu ditingkatkan</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Pencapaian Anda</h5>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $persentase }}%"
                             aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                            <strong>{{ $persentase }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Jawaban --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Detail Jawaban Anda</h5>
                </div>
                <div class="card-body">
                    @foreach($jawabanSiswa as $jawaban)
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <span class="badge bg-{{ $jawaban->lessonItem->is_soal ? 'primary' : 'secondary' }}">
                                    {{ $jawaban->lessonItem->tipe_label }}
                                </span>
                                <h6 class="mt-2">{{ $jawaban->lessonItem->konten }}</h6>
                            </div>
                            <span class="badge bg-{{ $jawaban->benar_salah ? 'success' : 'danger' }} ms-2">
                                <i class="bi bi-{{ $jawaban->benar_salah ? 'check-circle' : 'x-circle' }}"></i>
                                {{ $jawaban->status_label }}
                            </span>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">Jawaban Anda:</small>
                                <p class="mb-0 fw-bold">{{ strtoupper($jawaban->jawaban_siswa) }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Poin Didapat:</small>
                                <p class="mb-0 fw-bold text-warning">{{ $jawaban->poin_didapat }} poin</p>
                            </div>
                        </div>

                        @if(!$jawaban->benar_salah)
                        <div class="alert alert-warning mt-2 mb-0">
                            <small><strong>Jawaban yang benar:</strong> {{ strtoupper($jawaban->lessonItem->jawaban_benar) }}</small>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="row">
        <div class="col-12 text-center">
            <a href="{{ route('siswa.lesson-interaktif.mulai', $lessonFlow->id) }}" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-arrow-repeat me-2"></i>Ulangi Lesson
            </a>
            <a href="{{ route('siswa.lesson-interaktif.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* ============================================ */
/* PHASE 4: BADGE ACHIEVEMENT ANIMATIONS */
/* ============================================ */

/* Badge Achievement Card Styling */
.badge-achievement-card {
    border-width: 3px !important;
    animation: slideInFromTop 0.8s ease-out;
}

/* Badge Image Container */
.badge-image-container {
    display: inline-block;
    position: relative;
}

/* Badge Image Styling */
.badge-image {
    width: 150px;
    height: 150px;
    object-fit: contain;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
}

/* Badge Animation - Bounce & Glow */
@keyframes badgeBounce {
    0%, 100% {
        transform: scale(1) translateY(0);
    }
    50% {
        transform: scale(1.1) translateY(-10px);
    }
}

.animate-badge {
    animation: badgeBounce 1.5s ease-in-out infinite;
}

/* Achievement Stats Styling */
.achievement-stat {
    padding: 15px;
    border-radius: 10px;
    background: rgba(0,0,0,0.02);
    margin: 5px 0;
}

/* Card Slide-In Animation */
@keyframes slideInFromTop {
    0% {
        opacity: 0;
        transform: translateY(-50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Border Glow Effect (Gold Badge) */
.border-warning {
    box-shadow: 0 0 30px rgba(255, 193, 7, 0.4);
}

/* Border Glow Effect (Silver Badge) */
.border-secondary {
    box-shadow: 0 0 30px rgba(108, 117, 125, 0.4);
}

/* Border Glow Effect (Bronze Badge) */
.border-danger {
    box-shadow: 0 0 30px rgba(220, 53, 69, 0.4);
}
</style>

{{-- ============================================ --}}
{{-- PHASE 4: ACHIEVEMENT POPUP WITH SWEETALERT2 --}}
{{-- ============================================ --}}
@push('js_tambahan')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data badge dari controller
    const badge = "{{ $badge }}";
    const badgeLabel = "{{ $badgeLabel }}";
    const badgeMessage = "{{ $badgeMessage }}";
    const badgeIcon = "{{ $badgeIcon }}";
    const persentase = "{{ $persentase }}";
    const totalBenar = "{{ $totalBenar }}";
    const totalSoal = "{{ $totalSoal }}";

    // Tentukan icon dan warna SweetAlert berdasarkan badge
    let swalIcon = 'success';
    let swalColor = '#ffc107'; // gold

    if (badge === 'silver') {
        swalIcon = 'success';
        swalColor = '#6c757d';
    } else if (badge === 'bronze') {
        swalIcon = 'info';
        swalColor = '#dc3545';
    }

    // Cek apakah badge image ada
    const badgeImagePath = '{{ asset("badges/" . $badge . ".png") }}';

    // Tampilkan popup achievement setelah 500ms
    setTimeout(function() {
        Swal.fire({
            title: `${badgeIcon} Badge ${badgeLabel} Diraih!`,
            html: `
                <div style="padding: 20px;">
                    <img src="${badgeImagePath}"
                         alt="${badgeLabel} Badge"
                         style="width: 150px; height: 150px; margin: 20px auto; display: block; animation: rotateBadge 2s ease-in-out infinite;"
                         onerror="this.style.display='none';">

                    <h4 style="color: ${swalColor}; margin-top: 20px; font-weight: bold;">
                        ${badgeMessage}
                    </h4>

                    <div style="margin-top: 25px; padding: 20px; background: rgba(0,0,0,0.05); border-radius: 10px;">
                        <h2 style="color: ${swalColor}; font-weight: bold; margin-bottom: 5px;">
                            ${persentase}%
                        </h2>
                        <p style="color: #666; margin: 0;">
                            ${totalBenar} dari ${totalSoal} soal benar
                        </p>
                    </div>

                    <p style="margin-top: 20px; color: #888; font-size: 14px;">
                        <i class="bi bi-star-fill" style="color: ${swalColor};"></i>
                        Terus tingkatkan prestasimu!
                    </p>
                </div>

                <style>
                    @keyframes rotateBadge {
                        0%, 100% {
                            transform: rotate(0deg) scale(1);
                        }
                        25% {
                            transform: rotate(-10deg) scale(1.1);
                        }
                        75% {
                            transform: rotate(10deg) scale(1.1);
                        }
                    }
                </style>
            `,
            icon: swalIcon,
            confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Lihat Detail Hasil',
            confirmButtonColor: swalColor,
            allowOutsideClick: false,
            customClass: {
                popup: 'animated-popup',
                confirmButton: 'btn-lg'
            },
            showClass: {
                popup: 'animate__animated animate__zoomIn animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut animate__faster'
            }
        });
    }, 500);
});

// ✅ Phase 4: Achievement Popup complete
</script>
@endpush

@endsection
