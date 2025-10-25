{{--
=====================================================
HALAMAN KELOLA LESSON FLOW & URUTAN ITEM
=====================================================
Halaman ini adalah halaman utama untuk mengelola lesson flow.
Guru dapat:
1. Edit informasi lesson flow (judul, deskripsi, status)
2. Menambah item pembelajaran (5 tipe: video, gambar, soal PG, soal gambar, isian)
3. Melihat daftar item yang sudah ditambahkan
4. Mengatur urutan item dengan drag & drop
5. Edit dan hapus item

Struktur Halaman:
- Section 1: Info & Edit Lesson Flow
- Section 2: Form Tambah Item (Tab untuk setiap tipe)
- Section 3: Daftar Item dengan Drag & Drop

@package Resources\Views\LessonFlow
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Kelola Lesson Flow: ' . $lessonFlow->judul_materi)

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- Alert Sukses/Error --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ===============================================
        SECTION 1: INFORMASI LESSON FLOW
        ===============================================
        Card ini menampilkan info lesson flow dan form edit
    --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Lesson Flow
                        </h5>
                        <span class="badge bg-light text-dark">{{ $lessonFlow->status_label }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('lesson-flow.update', $lessonFlow->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Judul Materi</label>
                                <input type="text" class="form-control" name="judul_materi"
                                       value="{{ old('judul_materi', $lessonFlow->judul_materi) }}" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="draft" {{ $lessonFlow->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $lessonFlow->status === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                    <option value="archived" {{ $lessonFlow->status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Jumlah Item</label>
                                <input type="text" class="form-control" value="{{ $lessonFlow->items->count() }} item" readonly>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi', $lessonFlow->deskripsi) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai', $lessonFlow->tanggal_mulai?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai', $lessonFlow->tanggal_selesai?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        {{-- Durasi Maksimal Pengerjaan --}}
                        <div class="col-12 mb-3">
                            <label for="durasi_menit" class="form-label fw-bold">
                                <i class="bi bi-clock-fill me-1 text-warning"></i>Durasi Maksimal Pengerjaan (Menit)
                            </label>
                            <input type="number"
                                   class="form-control"
                                   id="durasi_menit"
                                   name="durasi_menit"
                                   value="{{ old('durasi_menit', $lessonFlow->durasi_menit ?? 0) }}"
                                   min="0"
                                   max="1440">
                            <small class="text-muted">
                                <strong>0 = Tanpa batas waktu</strong>. Contoh: 60 menit = 1 jam, 90 menit = 1,5 jam.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lesson-flow.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===============================================
        SECTION 2: FORM TAMBAH ITEM PEMBELAJARAN
        ===============================================
        Tabs untuk menambahkan 5 tipe item berbeda:
        1. Video YouTube
        2. Gambar Ilustrasi
        3. Soal Pilihan Ganda
        4. Soal dengan Gambar
        5. Soal Isian Singkat
    --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Item Pembelajaran</h5>
                </div>
                <div class="card-body">
                    {{-- Nav Tabs --}}
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-video" type="button">
                                <i class="bi bi-youtube"></i> Video
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-gambar" type="button">
                                <i class="bi bi-image"></i> Gambar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-soal-pg" type="button">
                                <i class="bi bi-card-list"></i> Soal PG
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-soal-gambar" type="button">
                                <i class="bi bi-images"></i> Soal Gambar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-isian" type="button">
                                <i class="bi bi-pencil-square"></i> Isian
                            </button>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content">
                        {{-- ===== TAB 1: VIDEO YOUTUBE ===== --}}
                        <div class="tab-pane fade show active" id="tab-video">
                            <form action="{{ route('lesson-items.store', $lessonFlow->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipe_item" value="video">

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Cara menambah video:</strong> Salin link YouTube (contoh: https://www.youtube.com/watch?v=xxxxx) dan paste di bawah.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Link YouTube <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" name="konten"
                                           placeholder="https://www.youtube.com/watch?v=xxxxx" required>
                                    <small class="text-muted">Paste URL video YouTube di sini</small>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Video
                                </button>
                            </form>
                        </div>

                        {{-- ===== TAB 2: GAMBAR ILUSTRASI ===== --}}
                        <div class="tab-pane fade" id="tab-gambar">
                            <form action="{{ route('lesson-items.store', $lessonFlow->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipe_item" value="gambar">

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Cara menambah gambar:</strong> Upload file gambar atau masukkan URL gambar dari internet.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Gambar</label>
                                    <input type="file" class="form-control" name="gambar_file" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, GIF (Max 5MB)</small>
                                </div>

                                <div class="text-center my-2">
                                    <strong>ATAU</strong>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">URL Gambar</label>
                                    <input type="url" class="form-control" name="konten"
                                           placeholder="https://example.com/gambar.jpg">
                                    <small class="text-muted">Link gambar dari internet (jika tidak upload)</small>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Gambar
                                </button>
                            </form>
                        </div>

                        {{-- ===== TAB 3: SOAL PILIHAN GANDA ===== --}}
                        <div class="tab-pane fade" id="tab-soal-pg">
                            <form action="{{ route('lesson-items.store', $lessonFlow->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipe_item" value="soal_pg">

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Soal Pilihan Ganda:</strong> Buat soal dengan 4 opsi (A, B, C, D) dan tentukan jawaban yang benar.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="konten" rows="3"
                                              placeholder="Tuliskan pertanyaan di sini..." required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Opsi A <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="opsi_a" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Opsi B <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="opsi_b" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Opsi C <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="opsi_c" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Opsi D <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="opsi_d" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Jawaban Benar <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jawaban_benar" required>
                                            <option value="">-- Pilih Jawaban --</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Poin</label>
                                        <input type="number" class="form-control" name="poin" value="10" min="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penjelasan (Opsional)</label>
                                    <textarea class="form-control" name="penjelasan" rows="2"
                                              placeholder="Penjelasan akan ditampilkan setelah siswa menjawab"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Soal PG
                                </button>
                            </form>
                        </div>

                        {{-- ===== TAB 4: SOAL DENGAN GAMBAR ===== --}}
                        <div class="tab-pane fade" id="tab-soal-gambar">
                            <form action="{{ route('lesson-items.store', $lessonFlow->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipe_item" value="soal_gambar">

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Soal dengan Gambar:</strong> Buat soal dengan opsi berupa gambar (A, B, C, D).
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="konten" rows="3"
                                              placeholder="Tuliskan pertanyaan di sini..." required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Gambar Opsi A <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="gambar_opsi_a" accept="image/*" required>
                                        <input type="text" class="form-control mt-2" name="opsi_a" placeholder="Label Opsi A (opsional)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Gambar Opsi B <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="gambar_opsi_b" accept="image/*" required>
                                        <input type="text" class="form-control mt-2" name="opsi_b" placeholder="Label Opsi B (opsional)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Gambar Opsi C <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="gambar_opsi_c" accept="image/*" required>
                                        <input type="text" class="form-control mt-2" name="opsi_c" placeholder="Label Opsi C (opsional)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Gambar Opsi D <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="gambar_opsi_d" accept="image/*" required>
                                        <input type="text" class="form-control mt-2" name="opsi_d" placeholder="Label Opsi D (opsional)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Jawaban Benar <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jawaban_benar" required>
                                            <option value="">-- Pilih Jawaban --</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Poin</label>
                                        <input type="number" class="form-control" name="poin" value="10" min="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penjelasan (Opsional)</label>
                                    <textarea class="form-control" name="penjelasan" rows="2"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Soal Gambar
                                </button>
                            </form>
                        </div>

                        {{-- ===== TAB 5: SOAL ISIAN SINGKAT ===== --}}
                        <div class="tab-pane fade" id="tab-isian">
                            <form action="{{ route('lesson-items.store', $lessonFlow->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipe_item" value="isian">

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Soal Isian Singkat:</strong> Siswa akan mengetik jawaban dalam text box. Validasi case-insensitive.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="konten" rows="3"
                                              placeholder="Tuliskan pertanyaan di sini..." required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Jawaban Benar <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jawaban_benar"
                                               placeholder="Contoh: Assalamualaikum" required>
                                        <small class="text-muted">Huruf besar/kecil tidak berpengaruh</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Poin</label>
                                        <input type="number" class="form-control" name="poin" value="10" min="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penjelasan (Opsional)</label>
                                    <textarea class="form-control" name="penjelasan" rows="2"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Soal Isian
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===============================================
        SECTION 3: DAFTAR ITEM PEMBELAJARAN
        ===============================================
        Menampilkan semua item yang sudah ditambahkan.
        Fitur drag & drop untuk ubah urutan.
    --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ol me-2"></i>Daftar Item Pembelajaran ({{ $lessonFlow->items->count() }} item)
                    </h5>
                </div>
                <div class="card-body">
                    @if($lessonFlow->items->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-hand-index me-2"></i>
                        <strong>Tip:</strong> Seret & lepas card untuk mengubah urutan tampilan
                    </div>

                    <div id="sortable-items" class="row g-3">
                        @foreach($lessonFlow->items as $item)
                        <div class="col-12 sortable-item" data-id="{{ $item->id }}">
                            <div class="card border-start border-4 border-{{ $item->tipe_item === 'video' ? 'danger' : ($item->is_soal ? 'primary' : 'success') }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        {{-- Info Item --}}
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-grip-vertical text-muted me-2 handle" style="cursor: move;"></i>
                                                <span class="badge bg-{{ $item->tipe_item === 'video' ? 'danger' : ($item->is_soal ? 'primary' : 'success') }} me-2">
                                                    {{ $item->tipe_label }}
                                                </span>
                                                <strong class="me-2">Urutan: {{ $item->urutan }}</strong>
                                                @if($item->is_soal)
                                                <span class="badge bg-warning text-dark">{{ $item->poin }} poin</span>
                                                @endif
                                            </div>

                                            <div class="ms-4">
                                                @if($item->tipe_item === 'video')
                                                    <p class="mb-0 small text-muted">
                                                        <i class="bi bi-youtube text-danger me-1"></i>{{ $item->konten }}
                                                    </p>
                                                @elseif($item->tipe_item === 'gambar')
                                                    <p class="mb-0 small text-muted">
                                                        <i class="bi bi-image me-1"></i>Gambar Ilustrasi
                                                    </p>
                                                @else
                                                    <p class="mb-0">{{ Str::limit($item->konten, 100) }}</p>
                                                    @if($item->tipe_item === 'soal_pg' || $item->tipe_item === 'soal_gambar')
                                                        <p class="mb-0 small text-success mt-1">
                                                            <i class="bi bi-check-circle me-1"></i>Jawaban: {{ strtoupper($item->jawaban_benar) }}
                                                        </p>
                                                    @else
                                                        <p class="mb-0 small text-success mt-1">
                                                            <i class="bi bi-check-circle me-1"></i>Jawaban: {{ $item->jawaban_benar }}
                                                        </p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <div class="btn-group">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('lesson-items.edit', $item->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Item">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->tipe_label }}')"
                                                    title="Hapus Item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Hapus (Hidden) --}}
                        <form id="delete-form-{{ $item->id }}"
                              action="{{ route('lesson-items.destroy', $item->id) }}"
                              method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Belum ada item pembelajaran. Tambahkan item di atas!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===============================================
    JAVASCRIPT: DRAG & DROP SORTABLE
    ===============================================
    Menggunakan SortableJS untuk drag & drop
--}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Sortable untuk drag & drop
    var sortableElement = document.getElementById('sortable-items');

    if (sortableElement) {
        var sortable = Sortable.create(sortableElement, {
            animation: 150,
            handle: '.handle', // Hanya bisa drag dari icon grip
            ghostClass: 'bg-light',
            onEnd: function() {
                // Ambil urutan baru setelah drag
                var items = sortableElement.querySelectorAll('.sortable-item');
                var orders = {};

                items.forEach(function(item, index) {
                    var itemId = item.getAttribute('data-id');
                    orders[itemId] = index + 1;
                });

                // Kirim ke server via AJAX
                fetch('{{ route('lesson-flow.update-order', $lessonFlow->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload halaman untuk update urutan
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }

    // Auto-hide alerts
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Fungsi konfirmasi hapus item
function confirmDelete(itemId, tipeLabel) {
    if (confirm('Apakah Anda yakin ingin menghapus item "' + tipeLabel + '" ini?')) {
        document.getElementById('delete-form-' + itemId).submit();
    }
}
</script>

<style>
/* Style untuk drag & drop */
.sortable-item {
    cursor: move;
}

.handle {
    font-size: 1.5rem;
}

/* Highlight saat di-drag */
.sortable-ghost {
    opacity: 0.4;
    background: #f8f9fa;
}
</style>
@endsection
