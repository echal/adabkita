{{--
=====================================================
HALAMAN PREVIEW LESSON FLOW INTERAKTIF
=====================================================
Halaman ini menampilkan preview lesson flow seperti yang
akan dilihat oleh siswa saat mengikuti pembelajaran.

Fitur:
1. Tampilan step-by-step (satu item per halaman)
2. Navigasi Previous/Next untuk berpindah antar item
3. Progress bar menunjukkan posisi saat ini
4. Tampilan berbeda untuk setiap tipe item:
   - Video: YouTube iframe
   - Gambar: Image display
   - Soal PG: Pilihan ganda
   - Soal Gambar: Pilihan dengan gambar
   - Soal Isian: Input text

Untuk Guru: Ini adalah MODE PREVIEW (tidak bisa menjawab soal)
Untuk Siswa: Nanti akan dibuat halaman terpisah dengan fitur menjawab

@package Resources\Views\LessonFlow
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Preview: ' . $lessonFlow->judul_materi)

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- Header Info Lesson Flow --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="fw-bold mb-2">
                                <i class="bi bi-book-fill me-2"></i>{{ $lessonFlow->judul_materi }}
                            </h3>
                            <p class="mb-2">{{ $lessonFlow->deskripsi }}</p>
                            <div class="d-flex gap-3">
                                <span><i class="bi bi-list-ol me-1"></i>{{ $lessonFlow->items->count() }} Item</span>
                                <span><i class="bi bi-trophy me-1"></i>{{ $lessonFlow->total_poin }} Total Poin</span>
                                <span class="badge bg-light text-dark">{{ $lessonFlow->status_label }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('lesson-flow.edit', $lessonFlow->id) }}" class="btn btn-light">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($lessonFlow->items->count() === 0)
    {{-- Jika Lesson Flow Kosong --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Lesson Flow Ini Masih Kosong</h4>
                    <p class="text-muted">Tambahkan item pembelajaran untuk mulai membuat alur interaktif</p>
                    <a href="{{ route('lesson-flow.edit', $lessonFlow->id) }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Item
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- ===============================================
        LESSON FLOW CONTENT DENGAN NAVIGASI STEP
        ===============================================
        Tampilan item dengan navigasi Previous/Next
    --}}

    {{-- Progress Bar --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Progress Pembelajaran</small>
                        <small class="text-muted">
                            <span id="current-step">1</span> / {{ $lessonFlow->items->count() }}
                        </small>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div id="progress-bar" class="progress-bar bg-success progress-bar-striped" role="progressbar"
                             style="width: {{ $lessonFlow->items->count() > 0 ? (1 / $lessonFlow->items->count() * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Container untuk Setiap Item --}}
    <div class="row">
        <div class="col-lg-10 mx-auto">
            @foreach($lessonFlow->items as $index => $item)
            <div class="lesson-item card shadow-sm mb-3" id="item-{{ $item->id }}"
                 style="display: {{ $index === 0 ? 'block' : 'none' }};"
                 data-index="{{ $index + 1 }}">

                {{-- Header Card dengan Tipe & Urutan --}}
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <span class="badge bg-{{ $item->tipe_item === 'video' ? 'danger' : ($item->is_soal ? 'primary' : 'success') }} me-2">
                                {{ $item->tipe_label }}
                            </span>
                            Item #{{ $index + 1 }}
                        </h5>
                        @if($item->is_soal)
                        <span class="badge bg-warning text-dark">{{ $item->poin }} Poin</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- ===== TAMPILAN VIDEO YOUTUBE ===== --}}
                    @if($item->tipe_item === 'video')
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Video Pembelajaran</strong> - Tonton video berikut dengan seksama
                    </div>

                    @if($item->youtube_embed_url)
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $item->youtube_embed_url }}"
                                allowfullscreen
                                class="rounded"
                                style="border: 3px solid #dee2e6;"></iframe>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Link YouTube tidak valid: {{ $item->konten }}
                    </div>
                    @endif

                    {{-- ===== TAMPILAN GAMBAR ===== --}}
                    @elseif($item->tipe_item === 'gambar')
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Gambar Ilustrasi</strong> - Perhatikan gambar berikut
                    </div>

                    <div class="text-center">
                        <img src="{{ $item->gambar_url }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 500px; object-fit: contain;"
                             alt="Gambar Pembelajaran">
                    </div>

                    {{-- ===== TAMPILAN SOAL PILIHAN GANDA ===== --}}
                    @elseif($item->tipe_item === 'soal_pg')
                    <div class="alert alert-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        <strong>Soal Pilihan Ganda</strong>
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    <div class="list-group">
                        @foreach(['a' => $item->opsi_a, 'b' => $item->opsi_b, 'c' => $item->opsi_c, 'd' => $item->opsi_d] as $key => $opsi)
                        <div class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban_{{ $item->id }}"
                                       id="opsi_{{ $item->id }}_{{ $key }}" disabled>
                                <label class="form-check-label ms-2" for="opsi_{{ $item->id }}_{{ $key }}">
                                    <strong class="text-uppercase me-2">{{ $key }}.</strong>{{ $opsi }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-success mt-3">
                        <i class="bi bi-key me-2"></i>
                        <strong>Jawaban Benar (Preview Mode):</strong> {{ strtoupper($item->jawaban_benar) }}
                    </div>

                    @if($item->penjelasan)
                    <div class="alert alert-light mt-3">
                        <strong>Penjelasan:</strong> {{ $item->penjelasan }}
                    </div>
                    @endif

                    {{-- ===== TAMPILAN SOAL DENGAN GAMBAR ===== --}}
                    @elseif($item->tipe_item === 'soal_gambar')
                    <div class="alert alert-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        <strong>Soal dengan Gambar</strong>
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    <div class="row g-3">
                        @foreach(['a', 'b', 'c', 'd'] as $key)
                        @php
                            $gambarField = 'gambar_opsi_' . $key;
                            $opsiField = 'opsi_' . $key;
                        @endphp
                        @if($item->$gambarField)
                        <div class="col-md-6">
                            <div class="card h-100 border-2">
                                <div class="card-header bg-light">
                                    <strong class="text-uppercase">Opsi {{ $key }}</strong>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/lesson_gambar/' . $item->$gambarField) }}"
                                         class="img-fluid rounded"
                                         style="max-height: 200px; object-fit: contain;"
                                         alt="Opsi {{ strtoupper($key) }}">
                                    @if($item->$opsiField)
                                    <p class="mt-2 mb-0">{{ $item->$opsiField }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="alert alert-success mt-3">
                        <i class="bi bi-key me-2"></i>
                        <strong>Jawaban Benar (Preview Mode):</strong> {{ strtoupper($item->jawaban_benar) }}
                    </div>

                    @if($item->penjelasan)
                    <div class="alert alert-light mt-3">
                        <strong>Penjelasan:</strong> {{ $item->penjelasan }}
                    </div>
                    @endif

                    {{-- ===== TAMPILAN SOAL ISIAN SINGKAT ===== --}}
                    @elseif($item->tipe_item === 'isian')
                    <div class="alert alert-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        <strong>Soal Isian Singkat</strong>
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban Anda:</label>
                        <input type="text" class="form-control form-control-lg"
                               placeholder="Ketik jawaban di sini..." disabled>
                        <small class="text-muted">Siswa akan mengetik jawaban di kolom ini</small>
                    </div>

                    <div class="alert alert-success mt-3">
                        <i class="bi bi-key me-2"></i>
                        <strong>Jawaban Benar (Preview Mode):</strong> {{ $item->jawaban_benar }}
                    </div>

                    @if($item->penjelasan)
                    <div class="alert alert-light mt-3">
                        <strong>Penjelasan:</strong> {{ $item->penjelasan }}
                    </div>
                    @endif
                    @endif
                </div>

                {{-- Footer dengan Navigasi --}}
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Tombol Previous --}}
                        @if($index > 0)
                        <button type="button" class="btn btn-secondary"
                                onclick="navigateItem({{ $index }})">
                            <i class="bi bi-arrow-left me-1"></i>Sebelumnya
                        </button>
                        @else
                        <span></span>
                        @endif

                        {{-- Tombol Next atau Selesai --}}
                        @if($index < $lessonFlow->items->count() - 1)
                        <button type="button" class="btn btn-primary"
                                onclick="navigateItem({{ $index + 2 }})">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-success" onclick="finishLesson()">
                            <i class="bi bi-check-circle me-1"></i>Selesai
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- ===============================================
    JAVASCRIPT: NAVIGASI ANTAR ITEM
    ===============================================
    Fungsi untuk berpindah antar item dan update progress
--}}
<script>
// Total item dalam lesson flow
const totalItems = {{ $lessonFlow->items->count() }};
let currentItemIndex = 1;

/**
 * Navigasi ke item tertentu
 * @param {number} itemIndex - Index item yang dituju (1-based)
 */
function navigateItem(itemIndex) {
    // Hide semua item
    document.querySelectorAll('.lesson-item').forEach(item => {
        item.style.display = 'none';
    });

    // Show item yang dipilih
    const items = document.querySelectorAll('.lesson-item');
    if (items[itemIndex - 1]) {
        items[itemIndex - 1].style.display = 'block';
        currentItemIndex = itemIndex;

        // Update progress bar
        updateProgress();

        // Scroll ke atas
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

/**
 * Update progress bar sesuai posisi saat ini
 */
function updateProgress() {
    const percentage = (currentItemIndex / totalItems) * 100;
    document.getElementById('progress-bar').style.width = percentage + '%';
    document.getElementById('current-step').textContent = currentItemIndex;
}

/**
 * Fungsi yang dipanggil saat lesson selesai
 */
function finishLesson() {
    alert('Preview selesai!\n\nNanti siswa akan melihat hasil dan skor mereka di halaman ini.');
    window.location.href = '{{ route('lesson-flow.index') }}';
}

// Inisialisasi saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
});
</script>

<style>
/* Animasi transisi saat ganti item */
.lesson-item {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Style untuk opsi soal */
.list-group-item-action {
    cursor: pointer;
    transition: all 0.2s;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd;
}
</style>
@endsection
