{{--
=====================================================
HALAMAN INTERAKTIF SISWA MENGIKUTI LESSON FLOW
=====================================================
Halaman ini adalah halaman pembelajaran interaktif
dimana siswa mengikuti lesson flow step-by-step.

Fitur Utama:
1. Tampilan item berurutan (video, gambar, soal)
2. Siswa bisa menjawab soal langsung
3. Jawaban disimpan otomatis via AJAX
4. Notifikasi realtime (benar/salah)
5. Progress bar menunjukkan posisi
6. Navigasi Previous/Next

@package Resources\Views\Siswa\LessonInteraktif
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Lesson: ' . $lessonFlow->judul_materi)

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- Header Lesson Flow --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body">
                    <h3 class="fw-bold mb-2">
                        <i class="bi bi-book-fill me-2"></i>{{ $lessonFlow->judul_materi }}
                    </h3>
                    <p class="mb-2">{{ $lessonFlow->deskripsi }}</p>
                    <div class="d-flex gap-3">
                        <span><i class="bi bi-list-ol me-1"></i>{{ $lessonFlow->items->count() }} Item</span>
                        <span><i class="bi bi-trophy me-1"></i>{{ $lessonFlow->total_poin }} Total Poin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted fw-bold">Progress Pembelajaran</small>
                        <small class="text-primary fw-bold">
                            <span id="current-step">1</span> / {{ $lessonFlow->items->count() }}
                        </small>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div id="progress-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                             role="progressbar"
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
                 data-index="{{ $index + 1 }}"
                 data-item-id="{{ $item->id }}"
                 data-tipe="{{ $item->tipe_item }}">

                {{-- Header Card --}}
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <span class="badge bg-{{ $item->is_soal ? 'primary' : ($item->tipe_item === 'video' ? 'danger' : 'success') }} me-2">
                                {{ $item->tipe_label }}
                            </span>
                            Item #{{ $index + 1 }}
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->is_soal)
                            <span class="badge bg-warning text-dark">{{ $item->poin }} Poin</span>
                            {{-- Timer Countdown untuk Soal --}}
                            <div class="timer-container" id="timer-{{ $item->id }}" data-duration="{{ $item->tipe_item === 'soal_pg' ? 30 : ($item->tipe_item === 'soal_gambar' ? 45 : 60) }}">
                                <span class="timer-display badge bg-info">
                                    <i class="bi bi-clock-fill"></i> <span class="timer-text">30</span>s
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    {{-- Progress Bar Timer --}}
                    @if($item->is_soal)
                    <div class="timer-progress-bar mt-2">
                        <div class="timer-progress" id="timer-progress-{{ $item->id }}" style="width: 100%;"></div>
                    </div>
                    @endif
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
                        {{-- YouTube iframe dengan enablejsapi=1 untuk IFrame API --}}
                        <iframe id="youtube-player-{{ $item->id }}"
                                src="{{ $item->youtube_embed_url }}?enablejsapi=1&rel=0&modestbranding=1{{ $index === 0 && $isVideoFirst ? '&autoplay=1&mute=1' : '' }}"
                                allowfullscreen
                                class="rounded"
                                style="border: 3px solid #dee2e6;"></iframe>
                    </div>

                    {{-- Info unmute untuk autoplay (hanya item pertama) --}}
                    @if($index === 0 && $isVideoFirst)
                    <div class="alert alert-warning mt-3" id="unmute-alert-{{ $item->id }}">
                        <i class="bi bi-volume-mute me-2"></i>
                        <strong>Video dimulai dengan mode MUTE</strong> (kebijakan browser).
                        Klik icon <i class="bi bi-volume-up"></i> di video untuk menyalakan suara.
                    </div>
                    @endif

                    {{-- Loader overlay saat transisi dari video ke soal --}}
                    <div id="video-loader-{{ $item->id }}" class="video-loader" style="display:none;">
                        <div class="loader-content">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="text-primary fw-bold">✨ Bersiap ke soal berikutnya...</h5>
                            <p class="text-muted">Mohon tunggu sebentar</p>
                        </div>
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
                        <strong>Soal Pilihan Ganda</strong> - Pilih jawaban yang benar
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    {{-- Form Jawaban --}}
                    <form class="form-jawaban" data-item-id="{{ $item->id }}">
                        @csrf
                        <div class="list-group mb-3">
                            @foreach(['a' => $item->opsi_a, 'b' => $item->opsi_b, 'c' => $item->opsi_c, 'd' => $item->opsi_d] as $key => $opsi)
                            <label class="list-group-item list-group-item-action cursor-pointer opsi-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jawaban" value="{{ $key }}"
                                           id="opsi_{{ $item->id }}_{{ $key }}"
                                           {{ isset($jawabanSiswa[$item->id]) && $jawabanSiswa[$item->id]->jawaban_siswa === $key ? 'checked' : '' }}>
                                    <label class="form-check-label w-100 cursor-pointer" for="opsi_{{ $item->id }}_{{ $key }}">
                                        <strong class="text-uppercase me-2">{{ $key }}.</strong>{{ $opsi }}
                                    </label>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-submit-jawaban">
                            <i class="bi bi-check-circle me-2"></i>Kirim Jawaban
                        </button>
                    </form>

                    {{-- Area Feedback (hidden by default) --}}
                    <div class="feedback-area mt-3" style="display: none;"></div>

                    {{-- ===== TAMPILAN SOAL ISIAN ===== --}}
                    @elseif($item->tipe_item === 'isian')
                    <div class="alert alert-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        <strong>Soal Isian Singkat</strong> - Ketik jawaban Anda
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    {{-- Form Jawaban --}}
                    <form class="form-jawaban" data-item-id="{{ $item->id }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jawaban Anda:</label>
                            <input type="text" class="form-control form-control-lg" name="jawaban"
                                   placeholder="Ketik jawaban di sini..."
                                   value="{{ isset($jawabanSiswa[$item->id]) ? $jawabanSiswa[$item->id]->jawaban_siswa : '' }}"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-submit-jawaban">
                            <i class="bi bi-check-circle me-2"></i>Kirim Jawaban
                        </button>
                    </form>

                    {{-- Area Feedback --}}
                    <div class="feedback-area mt-3" style="display: none;"></div>

                    {{-- ===== TAMPILAN SOAL GAMBAR ===== --}}
                    @elseif($item->tipe_item === 'soal_gambar')
                    <div class="alert alert-primary">
                        <i class="bi bi-question-circle me-2"></i>
                        <strong>Soal dengan Gambar</strong> - Pilih gambar yang benar
                    </div>

                    <h5 class="mb-4">{{ $item->konten }}</h5>

                    {{-- Form Jawaban --}}
                    <form class="form-jawaban" data-item-id="{{ $item->id }}">
                        @csrf
                        <div class="row g-3 mb-3">
                            @foreach(['a', 'b', 'c', 'd'] as $key)
                            @php
                                $gambarField = 'gambar_opsi_' . $key;
                                $opsiField = 'opsi_' . $key;
                            @endphp
                            @if($item->$gambarField)
                            <div class="col-md-6">
                                <label class="card h-100 opsi-gambar cursor-pointer"
                                       for="opsi_gambar_{{ $item->id }}_{{ $key }}">
                                    <input type="radio" name="jawaban" value="{{ $key }}"
                                           id="opsi_gambar_{{ $item->id }}_{{ $key }}"
                                           style="display: none;"
                                           {{ isset($jawabanSiswa[$item->id]) && $jawabanSiswa[$item->id]->jawaban_siswa === $key ? 'checked' : '' }}>
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
                                </label>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-submit-jawaban">
                            <i class="bi bi-check-circle me-2"></i>Kirim Jawaban
                        </button>
                    </form>

                    {{-- Area Feedback --}}
                    <div class="feedback-area mt-3" style="display: none;"></div>
                    @endif

                    {{-- Jika sudah dijawab, tampilkan status --}}
                    @if(isset($jawabanSiswa[$item->id]) && $item->is_soal)
                    <div class="alert alert-{{ $jawabanSiswa[$item->id]->benar_salah ? 'success' : 'danger' }} mt-3">
                        <i class="bi bi-{{ $jawabanSiswa[$item->id]->benar_salah ? 'check-circle' : 'x-circle' }} me-2"></i>
                        <strong>Status:</strong> {{ $jawabanSiswa[$item->id]->status_label }}
                        (Poin: {{ $jawabanSiswa[$item->id]->poin_didapat }})
                    </div>
                    @endif
                </div>

                {{-- Footer dengan Navigasi --}}
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Info: Navigasi mundur tidak diizinkan --}}
                        <div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Jawaban tidak dapat diubah setelah pindah soal
                            </small>
                        </div>

                        {{-- Tombol Navigasi Dinamis --}}
                        @if($item->is_soal)
                            {{-- Jika BUKAN soal terakhir: Tombol "Soal Berikutnya" --}}
                            @php
                                $soalItems = $lessonFlow->items->filter(fn($i) => $i->is_soal);
                                $soalIndex = $soalItems->search(fn($i) => $i->id === $item->id);
                                $isLastSoal = ($soalIndex === $soalItems->count() - 1);
                            @endphp

                            @if(!$isLastSoal)
                                <button type="button"
                                        class="btn btn-primary btn-next-soal"
                                        data-item-id="{{ $item->id }}"
                                        data-next-index="{{ $index + 2 }}"
                                        onclick="nextSoal(this)">
                                    Soal Berikutnya <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            @else
                                {{-- Jika soal terakhir: Tombol "Kirim Jawaban" --}}
                                <button type="button"
                                        class="btn btn-success btn-submit-final"
                                        id="btn-submit-final"
                                        onclick="validateAndSubmitFinal()">
                                    <i class="bi bi-send me-1"></i>📤 Kirim Semua Jawaban
                                </button>
                            @endif
                        @else
                            {{-- Untuk video/gambar: Tombol Next biasa --}}
                            @if($index < $lessonFlow->items->count() - 1)
                            <button type="button" class="btn btn-primary btn-next"
                                    onclick="navigateItem({{ $index + 2 }})">
                                Lanjutkan <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- CSS Styles --}}
<style>
/* Gradient header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Cursor pointer */
.cursor-pointer {
    cursor: pointer;
}

/* Style untuk opsi soal */
.opsi-item {
    transition: all 0.2s;
}

.opsi-item:hover {
    background-color: #f8f9fa;
    border-left: 4px solid #0d6efd !important;
}

.opsi-item input[type="radio"]:checked ~ label {
    font-weight: bold;
}

/* Style untuk opsi gambar */
.opsi-gambar {
    transition: all 0.3s;
    border: 3px solid #dee2e6;
}

.opsi-gambar:hover {
    border-color: #0d6efd;
    transform: scale(1.02);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.opsi-gambar input[type="radio"]:checked + .card-header {
    background-color: #0d6efd !important;
    color: white;
}

.opsi-gambar input[type="radio"]:checked {
    border-color: #0d6efd;
}

/* =============================================
   ANIMASI TRANSISI ANTAR ITEM
   Menggunakan slide + fade untuk transisi smooth
   ============================================= */
.lesson-item {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.lesson-item.slide-out {
    animation: slideOutLeft 0.4s ease-in;
}

@keyframes slideOutLeft {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(-50px);
    }
}

/* =============================================
   TIMER COUNTDOWN STYLING
   Progress bar yang berubah warna sesuai waktu
   ============================================= */
.timer-display {
    font-size: 1.1rem;
    font-weight: bold;
    padding: 0.5rem 1rem;
    min-width: 80px;
    text-align: center;
}

.timer-progress-bar {
    height: 6px;
    background-color: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.timer-progress {
    height: 100%;
    background: linear-gradient(90deg, #28a745 0%, #28a745 100%);
    transition: width 1s linear, background 0.3s ease;
}

/* Warna timer berubah sesuai sisa waktu */
.timer-progress.warning {
    background: linear-gradient(90deg, #ffc107 0%, #ffc107 100%);
}

.timer-progress.danger {
    background: linear-gradient(90deg, #dc3545 0%, #dc3545 100%);
    animation: pulse 0.5s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.timer-display.warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.timer-display.danger {
    background-color: #dc3545 !important;
    color: #fff !important;
    animation: shake 0.3s infinite;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-5px);
    }
    75% {
        transform: translateX(5px);
    }
}

/* =============================================
   VIDEO LOADER OVERLAY
   Overlay yang muncul saat video selesai
   ============================================= */
.video-loader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.97);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-in;
}

.loader-content {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    animation: scaleIn 0.3s ease-out;
}

@keyframes scaleIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* =============================================
   PHASE 3: LOCKED ANSWER STYLING
   Style untuk jawaban yang sudah dikunci
   ============================================= */
input.locked {
    background-color: #e9ecef !important;
    cursor: not-allowed !important;
    opacity: 0.6;
}

.opsi-item:has(input.locked) {
    background-color: #f8f9fa;
    opacity: 0.7;
    cursor: not-allowed;
}

.opsi-gambar:has(input.locked) {
    opacity: 0.7;
    cursor: not-allowed;
    border-color: #dee2e6;
}

/* Badge locked animation */
.badge-locked {
    animation: fadeInBadge 0.5s ease-in;
}

@keyframes fadeInBadge {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Button submit final pulse animation */
.btn-submit-final {
    animation: pulseGlow 2s infinite;
}

@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
    }
}
</style>

{{-- JavaScript --}}
<script>
/**
 * =====================================================
 * YOUTUBE IFRAME API INTEGRATION
 * =====================================================
 * Fitur auto-play video dan deteksi video selesai
 * untuk otomatis pindah ke item berikutnya
 *
 * @author System
 * @created 2025-10-16
 * @phase Phase 1 - Auto-Play & Auto-Next
 * =====================================================
 */

// ============================================
// VARIABEL GLOBAL
// ============================================
const totalItems = {{ $lessonFlow->items->count() }};
const isVideoFirst = {{ $isVideoFirst ? 'true' : 'false' }};
const firstItemId = {{ $itemPertama->id ?? 'null' }};

let currentItemIndex = 1;
let activeTimer = null; // Variable untuk menyimpan timer yang aktif
let youtubePlayer = null; // Instance YouTube Player
let isYouTubeApiReady = false; // Flag YouTube API ready
let nextButtonDisabled = false; // Flag untuk disable tombol next saat video

// ============================================
// LOAD YOUTUBE IFRAME API
// Script ini hanya dimuat sekali untuk menghindari konflik
// ============================================
if (isVideoFirst) {
    // Cek apakah API sudah dimuat sebelumnya
    if (!window.YT) {
        // Load YouTube IFrame API script
        let tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        let firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        console.log('📺 YouTube IFrame API script loaded');
    } else {
        console.log('📺 YouTube IFrame API already loaded');
        isYouTubeApiReady = true;

        // Init player langsung jika API sudah ready
        setTimeout(() => {
            initYouTubePlayer(firstItemId);
        }, 500);
    }
}

/**
 * ============================================
 * CALLBACK: YouTube API Ready
 * Fungsi ini dipanggil otomatis oleh YouTube API
 * saat API sudah siap digunakan
 * ============================================
 */
function onYouTubeIframeAPIReady() {
    console.log('✅ YouTube IFrame API is ready!');
    isYouTubeApiReady = true;

    // Jika item pertama adalah video, initialize player
    if (isVideoFirst && firstItemId) {
        initYouTubePlayer(firstItemId);
    }
}

/**
 * ============================================
 * INIT YOUTUBE PLAYER
 * Membuat instance YouTube Player dengan event handlers
 * @param int itemId - ID dari lesson_item (video)
 * ============================================
 */
function initYouTubePlayer(itemId) {
    const iframe = document.getElementById('youtube-player-' + itemId);

    if (!iframe) {
        console.error('❌ YouTube iframe not found for item:', itemId);
        return;
    }

    // Pastikan YT object tersedia
    if (typeof YT === 'undefined' || !YT.Player) {
        console.error('❌ YouTube API not loaded yet');
        return;
    }

    console.log('🎬 Initializing YouTube Player for item:', itemId);

    // Destroy player lama jika ada
    if (youtubePlayer) {
        youtubePlayer.destroy();
        youtubePlayer = null;
    }

    // Buat player instance baru
    youtubePlayer = new YT.Player('youtube-player-' + itemId, {
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });

    // Disable tombol Next selama video belum selesai
    disableNextButton(true);
}

/**
 * ============================================
 * CALLBACK: Player Ready
 * Dipanggil saat player sudah siap
 * ============================================
 */
function onPlayerReady(event) {
    console.log('✅ YouTube Player ready');

    // Video akan auto-play karena sudah ada parameter autoplay=1 di URL
    // Player sudah ready dan video mulai diputar
}

/**
 * ============================================
 * CALLBACK: Player State Change
 * Dipanggil setiap kali status video berubah
 * @param object event - Event object dari YouTube API
 *
 * Status codes:
 * -1 = unstarted
 *  0 = ended
 *  1 = playing
 *  2 = paused
 *  3 = buffering
 *  5 = video cued
 * ============================================
 */
function onPlayerStateChange(event) {
    const states = {
        '-1': 'unstarted',
        '0': 'ended',
        '1': 'playing',
        '2': 'paused',
        '3': 'buffering',
        '5': 'cued'
    };

    console.log('📹 Player state changed:', states[event.data] || event.data);

    // Video mulai diputar
    if (event.data === YT.PlayerState.PLAYING) {
        console.log('▶️ Video started playing');
        disableNextButton(true);
    }

    // Video di-pause
    if (event.data === YT.PlayerState.PAUSED) {
        console.log('⏸️ Video paused');
    }

    // Video selesai diputar
    if (event.data === YT.PlayerState.ENDED) {
        console.log('🎬 Video ended! Starting auto-next sequence...');

        // Enable tombol next
        disableNextButton(false);

        // Tampilkan loader overlay
        showVideoLoader(firstItemId);

        // Tunggu 1.5 detik untuk transisi smooth
        setTimeout(() => {
            hideVideoLoader(firstItemId);

            // Pindah ke item berikutnya
            if (currentItemIndex < totalItems) {
                navigateItem(currentItemIndex + 1);
            } else {
                // Jika sudah item terakhir, redirect ke hasil
                window.location.href = '{{ route("siswa.lesson-interaktif.hasil", $lessonFlow->id) }}';
            }
        }, 1500);
    }
}

/**
 * ============================================
 * DISABLE/ENABLE TOMBOL NEXT
 * Nonaktifkan tombol navigasi selama video berjalan
 * @param bool disable - true = disable, false = enable
 * ============================================
 */
function disableNextButton(disable) {
    const nextButtons = document.querySelectorAll('.btn-next');

    nextButtons.forEach(btn => {
        if (disable) {
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.title = 'Tonton video sampai selesai';
        } else {
            btn.disabled = false;
            btn.classList.remove('disabled');
            btn.title = '';
        }
    });

    nextButtonDisabled = disable;
}

/**
 * ============================================
 * SHOW VIDEO LOADER
 * Tampilkan overlay "Bersiap ke soal berikutnya..."
 * @param int itemId - ID item video
 * ============================================
 */
function showVideoLoader(itemId) {
    const loader = document.getElementById('video-loader-' + itemId);
    if (loader) {
        loader.style.display = 'flex';
    }
}

/**
 * ============================================
 * HIDE VIDEO LOADER
 * Sembunyikan overlay loader
 * @param int itemId - ID item video
 * ============================================
 */
function hideVideoLoader(itemId) {
    const loader = document.getElementById('video-loader-' + itemId);
    if (loader) {
        loader.style.display = 'none';
    }
}

/**
 * ===============================================
 * NAVIGASI ANTAR ITEM DENGAN ANIMASI SLIDE
 * ===============================================
 * Fungsi untuk berpindah antar item pembelajaran
 * Dengan animasi slide-out dan slide-in yang smooth
 */
function navigateItem(itemIndex) {
    const items = document.querySelectorAll('.lesson-item');
    const currentItem = items[currentItemIndex - 1];
    const nextItem = items[itemIndex - 1];

    if (!nextItem) return;

    // Stop timer yang sedang berjalan
    if (activeTimer) {
        clearInterval(activeTimer);
        activeTimer = null;
    }

    // Animasi slide-out untuk item saat ini
    currentItem.classList.add('slide-out');

    // Setelah animasi selesai (400ms), ganti item
    setTimeout(() => {
        // Hide item saat ini
        currentItem.style.display = 'none';
        currentItem.classList.remove('slide-out');

        // Show item berikutnya dengan animasi slide-in
        nextItem.style.display = 'block';
        currentItemIndex = itemIndex;

        // Update progress bar
        updateProgress();

        // Start timer jika item adalah soal
        const itemTipe = nextItem.getAttribute('data-tipe');
        if (itemTipe.includes('soal')) {
            const itemId = nextItem.getAttribute('data-item-id');
            startTimer(itemId);
        }

        // Scroll ke atas dengan smooth
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 400);
}

/**
 * Update progress bar
 */
function updateProgress() {
    const percentage = (currentItemIndex / totalItems) * 100;
    document.getElementById('progress-bar').style.width = percentage + '%';
    document.getElementById('current-step').textContent = currentItemIndex;
}

/**
 * ===============================================
 * TIMER COUNTDOWN UNTUK SOAL
 * ===============================================
 * Timer akan berjalan mundur dari durasi yang ditentukan
 * Warna bar berubah: Hijau → Kuning → Merah
 * Saat waktu habis: disable form dan auto-next
 */
function startTimer(itemId) {
    const timerContainer = document.getElementById('timer-' + itemId);
    if (!timerContainer) return;

    // Ambil durasi dari attribute data-duration (dalam detik)
    let duration = parseInt(timerContainer.getAttribute('data-duration'));
    const originalDuration = duration;

    const timerText = timerContainer.querySelector('.timer-text');
    const timerDisplay = timerContainer.querySelector('.timer-display');
    const timerProgress = document.getElementById('timer-progress-' + itemId);
    const form = document.querySelector(`.form-jawaban[data-item-id="${itemId}"]`);

    // Inisialisasi tampilan timer
    timerText.textContent = duration;

    // Interval countdown setiap 1 detik
    activeTimer = setInterval(() => {
        duration--;
        timerText.textContent = duration;

        // Hitung persentase sisa waktu untuk progress bar
        const percentageLeft = (duration / originalDuration) * 100;
        timerProgress.style.width = percentageLeft + '%';

        // Ubah warna berdasarkan sisa waktu
        if (duration <= originalDuration * 0.3) {
            // 30% waktu tersisa = MERAH (bahaya!)
            timerProgress.classList.remove('warning');
            timerProgress.classList.add('danger');
            timerDisplay.classList.remove('warning');
            timerDisplay.classList.add('danger');
        } else if (duration <= originalDuration * 0.5) {
            // 50% waktu tersisa = KUNING (peringatan)
            timerProgress.classList.add('warning');
            timerDisplay.classList.add('warning');
        }

        // Jika waktu habis
        if (duration <= 0) {
            clearInterval(activeTimer);
            activeTimer = null;

            // Tampilkan notifikasi waktu habis
            const feedbackArea = form.closest('.card-body').querySelector('.feedback-area');
            feedbackArea.innerHTML = `
                <div class="alert alert-warning">
                    <h5><i class="bi bi-clock-fill me-2"></i>⏰ Waktu Habis!</h5>
                    <p class="mb-0">Waktu untuk menjawab soal ini telah habis. Sistem akan otomatis melanjutkan ke item berikutnya dalam 3 detik...</p>
                </div>
            `;
            feedbackArea.style.display = 'block';

            // Disable form (radio button dan tombol submit)
            const inputs = form.querySelectorAll('input[type="radio"], input[type="text"]');
            inputs.forEach(input => input.disabled = true);

            const submitBtn = form.querySelector('.btn-submit-jawaban');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-clock-fill me-2"></i>Waktu Habis';
            }

            // Auto-next ke item berikutnya setelah 3 detik
            setTimeout(() => {
                if (currentItemIndex < totalItems) {
                    navigateItem(currentItemIndex + 1);
                } else {
                    // Jika sudah item terakhir, redirect ke hasil
                    window.location.href = '{{ route("siswa.lesson-interaktif.hasil", $lessonFlow->id) }}';
                }
            }, 3000);
        }
    }, 1000);
}

/**
 * ===============================================
 * SUBMIT JAWABAN VIA AJAX
 * ===============================================
 * Fungsi untuk menyimpan jawaban siswa ke database
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle submit form jawaban
    document.querySelectorAll('.form-jawaban').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const itemId = this.getAttribute('data-item-id');
            const formData = new FormData(this);
            const jawaban = formData.get('jawaban');
            const submitBtn = this.querySelector('.btn-submit-jawaban');
            const feedbackArea = this.closest('.card-body').querySelector('.feedback-area');

            // Validasi jawaban tidak kosong
            if (!jawaban || jawaban.trim() === '') {
                alert('Silakan pilih atau isi jawaban terlebih dahulu!');
                return;
            }

            // Disable button saat loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            // Kirim jawaban via AJAX
            fetch('{{ route('siswa.lesson-interaktif.simpan-jawaban') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_lesson_item: itemId,
                    jawaban_siswa: jawaban
                })
            })
            .then(response => response.json())
            .then(data => {
                // Enable button kembali
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Kirim Jawaban';

                // Tampilkan feedback
                if (data.success) {
                    let feedbackHTML = '';

                    if (data.benar) {
                        // Jawaban Benar
                        feedbackHTML = `
                            <div class="alert alert-success">
                                <h5><i class="bi bi-check-circle me-2"></i>✅ ${data.message}</h5>
                                <p class="mb-0">Poin yang didapat: <strong>${data.poin}</strong></p>
                                ${data.penjelasan ? '<hr><p class="mb-0"><strong>Penjelasan:</strong> ' + data.penjelasan + '</p>' : ''}
                            </div>
                        `;
                    } else {
                        // Jawaban Salah
                        feedbackHTML = `
                            <div class="alert alert-danger">
                                <h5><i class="bi bi-x-circle me-2"></i>❌ ${data.message}</h5>
                                <p class="mb-0">Jawaban yang benar: <strong>${data.jawaban_benar}</strong></p>
                                ${data.penjelasan ? '<hr><p class="mb-0"><strong>Penjelasan:</strong> ' + data.penjelasan + '</p>' : ''}
                            </div>
                        `;
                    }

                    feedbackArea.innerHTML = feedbackHTML;
                    feedbackArea.style.display = 'block';

                    // Auto scroll ke feedback
                    feedbackArea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Kirim Jawaban';
                alert('Terjadi kesalahan. Silakan coba lagi!');
            });
        });
    });

    // Initial progress update
    updateProgress();

    // =====================================================
    // START TIMER UNTUK ITEM PERTAMA (jika item soal)
    // =====================================================
    const firstItem = document.querySelector('.lesson-item[style*="display: block"]');
    if (firstItem) {
        const itemTipe = firstItem.getAttribute('data-tipe');
        const itemId = firstItem.getAttribute('data-item-id');

        // Jika item pertama adalah soal, mulai timer
        if (itemTipe && itemTipe.includes('soal')) {
            startTimer(itemId);
        }
    }
});

// ✅ YouTube autoplay integration complete
// Fitur yang berhasil diimplementasikan:
// 1. Auto-play video YouTube di item pertama (dengan mute untuk browser policy)
// 2. Deteksi video selesai dengan YouTube IFrame API (onStateChange)
// 3. Disable tombol Next selama video berjalan
// 4. Loader overlay saat transisi dari video ke soal
// 5. Auto-next ke item berikutnya setelah video selesai
// 6. Animasi fade-in/out yang smooth
// 7. Kompatibel dengan timer dan progress bar yang sudah ada
// 8. Kompatibel dengan AJAX submit jawaban
console.log('✅ Lesson Interaktif Phase 1: YouTube Integration COMPLETE');

/**
 * =====================================================
 * PHASE 3: VALIDASI POPUP & TOMBOL KIRIM AKHIR
 * =====================================================
 * Implementasi validasi jawaban dan tombol kirim dinamis
 *
 * @author System
 * @created 2025-10-16
 * @phase Phase 3 - Validation & Final Submit
 * =====================================================
 */

// ============================================
// OBJECT UNTUK MENYIMPAN JAWABAN SISWA
// Key: item_id, Value: jawaban
// ============================================
let studentAnswers = {};

// Array untuk menyimpan ID soal yang sudah dijawab
let answeredQuestions = [];

// Total soal dalam lesson (untuk validasi)
const totalSoal = {{ $totalSoal }};

/**
 * ============================================
 * FUNGSI: NEXT SOAL
 * Pindah ke soal berikutnya dengan validasi jawaban
 * dan locking jawaban sebelumnya
 * @param HTMLElement button - Tombol yang diklik
 * ============================================
 */
function nextSoal(button) {
    const itemId = button.getAttribute('data-item-id');
    const nextIndex = parseInt(button.getAttribute('data-next-index'));

    // Cek apakah soal sudah dijawab
    const form = document.querySelector(`.form-jawaban[data-item-id="${itemId}"]`);
    const jawaban = new FormData(form).get('jawaban');

    if (!jawaban || jawaban.trim() === '') {
        // Soal belum dijawab - tampilkan peringatan
        Swal.fire({
            icon: 'warning',
            title: 'Soal Belum Dijawab!',
            text: 'Silakan jawab soal ini terlebih dahulu sebelum melanjutkan.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    // Simpan jawaban ke object
    studentAnswers[itemId] = jawaban;
    answeredQuestions.push(parseInt(itemId));

    // Lock jawaban (disable semua input di soal ini)
    lockAnswers(itemId);

    // Tampilkan animasi loading singkat
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    // Submit jawaban via AJAX
    submitAnswer(itemId, jawaban, function(success) {
        if (success) {
            // Tunggu sebentar untuk animasi, lalu pindah ke soal berikutnya
            setTimeout(() => {
                navigateItem(nextIndex);
            }, 500);
        } else {
            // Jika gagal, enable button kembali
            button.disabled = false;
            button.innerHTML = 'Soal Berikutnya <i class="bi bi-arrow-right ms-1"></i>';
        }
    });
}

/**
 * ============================================
 * FUNGSI: LOCK ANSWERS
 * Mengunci jawaban soal agar tidak bisa diubah
 * @param int itemId - ID dari lesson_item
 * ============================================
 */
function lockAnswers(itemId) {
    const form = document.querySelector(`.form-jawaban[data-item-id="${itemId}"]`);
    if (!form) return;

    // Disable semua input (radio, text)
    const inputs = form.querySelectorAll('input[type="radio"], input[type="text"]');
    inputs.forEach(input => {
        input.disabled = true;
        input.classList.add('locked');
    });

    // Disable submit button
    const submitBtn = form.querySelector('.btn-submit-jawaban');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        submitBtn.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Jawaban Terkunci';
    }

    // Tambahkan badge "Terkunci"
    const cardHeader = form.closest('.lesson-item').querySelector('.card-header');
    if (cardHeader && !cardHeader.querySelector('.badge-locked')) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary badge-locked ms-2';
        badge.innerHTML = '<i class="bi bi-lock-fill me-1"></i>Terkunci';
        cardHeader.querySelector('h5').appendChild(badge);
    }

    console.log('🔒 Jawaban soal ' + itemId + ' telah dikunci');
}

/**
 * ============================================
 * FUNGSI: SUBMIT ANSWER (AJAX)
 * Mengirim jawaban ke server via AJAX
 * @param int itemId - ID lesson_item
 * @param string jawaban - Jawaban siswa
 * @param function callback - Callback setelah submit
 * ============================================
 */
function submitAnswer(itemId, jawaban, callback) {
    fetch('{{ route('siswa.lesson-interaktif.simpan-jawaban') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_lesson_item: itemId,
            jawaban_siswa: jawaban
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Jawaban berhasil disimpan:', data);
            callback(true);
        } else {
            console.error('❌ Gagal menyimpan jawaban:', data);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: data.message || 'Terjadi kesalahan saat menyimpan jawaban.',
                confirmButtonText: 'OK'
            });
            callback(false);
        }
    })
    .catch(error => {
        console.error('❌ Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Koneksi Bermasalah',
            text: 'Tidak dapat menyimpan jawaban. Periksa koneksi internet Anda.',
            confirmButtonText: 'OK'
        });
        callback(false);
    });
}

/**
 * ============================================
 * FUNGSI: VALIDATE AND SUBMIT FINAL
 * Validasi semua soal sudah dijawab, lalu submit akhir
 * ============================================
 */
function validateAndSubmitFinal() {
    console.log('🔍 Memvalidasi jawaban sebelum submit final...');
    console.log('Total soal:', totalSoal);
    console.log('Soal yang sudah dijawab:', answeredQuestions.length);

    // Cek soal terakhir apakah sudah dijawab
    const lastItem = document.querySelector('.lesson-item[style*="display: block"]');
    const lastItemId = lastItem.getAttribute('data-item-id');
    const lastForm = lastItem.querySelector('.form-jawaban');

    if (lastForm) {
        const lastJawaban = new FormData(lastForm).get('jawaban');
        if (lastJawaban && lastJawaban.trim() !== '') {
            studentAnswers[lastItemId] = lastJawaban;
            if (!answeredQuestions.includes(parseInt(lastItemId))) {
                answeredQuestions.push(parseInt(lastItemId));
            }
        }
    }

    // Validasi: Cek apakah semua soal sudah dijawab
    const totalAnswered = answeredQuestions.length;

    if (totalAnswered < totalSoal) {
        // Masih ada soal yang belum dijawab
        const sisaSoal = totalSoal - totalAnswered;

        Swal.fire({
            icon: 'warning',
            title: 'Masih Ada Soal yang Belum Dijawab!',
            html: `
                <p>Anda telah menjawab <strong>${totalAnswered}</strong> dari <strong>${totalSoal}</strong> soal.</p>
                <p class="text-danger">Masih ada <strong>${sisaSoal}</strong> soal yang belum dijawab.</p>
                <p class="text-muted small">Silakan periksa kembali sebelum mengirim jawaban.</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Tetap Kirim',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // User tetap ingin mengirim meskipun belum lengkap
                showFinalConfirmation();
            }
        });
    } else {
        // Semua soal sudah dijawab
        showFinalConfirmation();
    }
}

/**
 * ============================================
 * FUNGSI: SHOW FINAL CONFIRMATION
 * Tampilkan popup konfirmasi akhir sebelum submit
 * ============================================
 */
function showFinalConfirmation() {
    Swal.fire({
        icon: 'question',
        title: 'Yakin Ingin Mengirim Semua Jawaban?',
        html: `
            <p>Setelah dikirim, jawaban <strong>tidak dapat diubah lagi</strong>.</p>
            <p class="text-muted small">Pastikan Anda sudah memeriksa semua jawaban dengan teliti.</p>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-send me-2"></i>Ya, Kirim Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return submitFinalAnswers();
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}

/**
 * ============================================
 * FUNGSI: SUBMIT FINAL ANSWERS
 * Mengirim semua jawaban akhir dan redirect ke hasil
 * ============================================
 */
function submitFinalAnswers() {
    console.log('📤 Mengirim semua jawaban...');

    // Stop timer jika ada
    if (activeTimer) {
        clearInterval(activeTimer);
        activeTimer = null;
    }

    // Update progress bar ke 100%
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
        progressBar.style.width = '100%';
    }

    // Submit jawaban terakhir jika belum
    const lastItem = document.querySelector('.lesson-item[style*="display: block"]');
    const lastItemId = lastItem.getAttribute('data-item-id');
    const lastForm = lastItem.querySelector('.form-jawaban');

    if (lastForm) {
        const lastJawaban = new FormData(lastForm).get('jawaban');

        if (lastJawaban && lastJawaban.trim() !== '') {
            // Submit jawaban terakhir
            return fetch('{{ route('siswa.lesson-interaktif.simpan-jawaban') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_lesson_item: lastItemId,
                    jawaban_siswa: lastJawaban
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Semua jawaban berhasil disimpan!');

                    // Tampilkan success message
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Jawaban Berhasil Dikirim!',
                        text: 'Terima kasih telah menyelesaikan lesson ini.',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect ke halaman hasil
                        window.location.href = '{{ route("siswa.lesson-interaktif.hasil", $lessonFlow->id) }}';
                    });
                } else {
                    throw new Error(data.message || 'Gagal menyimpan jawaban');
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: error.message || 'Gagal mengirim jawaban. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            });
        }
    }

    // Jika tidak ada jawaban baru, langsung redirect
    setTimeout(() => {
        window.location.href = '{{ route("siswa.lesson-interaktif.hasil", $lessonFlow->id) }}';
    }, 1000);
}

// ✅ Phase 3: Validasi Popup & Tombol Kirim Akhir complete
console.log('✅ Lesson Interaktif Phase 3: Validation & Final Submit COMPLETE');
</script>
@endsection
