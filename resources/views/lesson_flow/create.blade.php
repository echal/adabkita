{{--
=====================================================
HALAMAN FORM BUAT LESSON FLOW BARU
=====================================================
Form untuk membuat lesson flow interaktif baru.
Setelah disimpan, guru akan diarahkan ke halaman edit
untuk menambah item pembelajaran.

@package Resources\Views\LessonFlow
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Buat Lesson Flow Baru')

@section('isi_halaman')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-plus-circle-fill me-2"></i>Buat Lesson Flow Baru
            </h3>
            <p class="text-muted">Isi informasi dasar lesson flow, lalu tambahkan item pembelajaran</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Informasi Dasar Lesson Flow</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lesson-flow.store') }}" method="POST">
                        @csrf

                        {{-- Judul Materi --}}
                        <div class="mb-3">
                            <label for="judul_materi" class="form-label fw-bold">
                                Judul Materi <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('judul_materi') is-invalid @enderror"
                                   id="judul_materi"
                                   name="judul_materi"
                                   value="{{ old('judul_materi') }}"
                                   placeholder="Contoh: Adab Bertamu dalam Islam"
                                   required>
                            @error('judul_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Judul pembelajaran yang akan ditampilkan ke siswa</small>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi Singkat</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi"
                                      name="deskripsi"
                                      rows="4"
                                      placeholder="Jelaskan tujuan dan isi pembelajaran ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Deskripsi opsional untuk memberikan gambaran kepada siswa</small>
                        </div>

                        {{-- Tanggal Mulai & Selesai (Opsional) --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai (Opsional)</label>
                                <input type="date"
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                       id="tanggal_mulai"
                                       name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai') }}">
                                @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai (Opsional)</label>
                                <input type="date"
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                       id="tanggal_selesai"
                                       name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Durasi Maksimal Pengerjaan --}}
                        <div class="mb-3">
                            <label for="durasi_menit" class="form-label fw-bold">
                                <i class="bi bi-clock-fill me-1 text-warning"></i>Durasi Maksimal Pengerjaan (Menit)
                            </label>
                            <input type="number"
                                   class="form-control @error('durasi_menit') is-invalid @enderror"
                                   id="durasi_menit"
                                   name="durasi_menit"
                                   value="{{ old('durasi_menit', 0) }}"
                                   min="0"
                                   max="1440"
                                   placeholder="0">
                            @error('durasi_menit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <strong>Masukkan 0 untuk tanpa batas waktu</strong> atau isi angka (dalam menit) untuk memberi batasan waktu.
                                <br>Contoh: 60 = 1 jam, 90 = 1,5 jam, 120 = 2 jam.
                                <br>Timer akan otomatis berjalan saat siswa memulai lesson dan waktu habis maka lesson otomatis berakhir.
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Setelah menyimpan, Anda akan diarahkan ke halaman edit untuk menambahkan item pembelajaran (video, gambar, soal).
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lesson-flow.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan & Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
