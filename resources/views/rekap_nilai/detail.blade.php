{{--
=====================================================
HALAMAN DETAIL JAWABAN SISWA
=====================================================
Halaman ini menampilkan detail lengkap jawaban siswa
untuk satu lesson flow tertentu.

Fitur:
- Statistik nilai dan progres siswa
- Daftar semua soal dengan jawaban siswa
- Penanda benar/salah untuk setiap jawaban
- Penjelasan untuk setiap soal
- Tombol kembali ke rekap

@package Resources\Views\RekapNilai
@author System
@created 2025-10-16
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Detail Jawaban Siswa')

@push('styles')
<style>
    /* Custom styling */
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .soal-card {
        transition: all 0.3s ease;
        border-left: 4px solid #e0e0e0;
    }

    .soal-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .soal-card.benar {
        border-left-color: #28a745;
        background-color: #f0f9f4;
    }

    .soal-card.salah {
        border-left-color: #dc3545;
        background-color: #fef0f0;
    }

    .soal-card.belum-dijawab {
        border-left-color: #ffc107;
        background-color: #fff9e6;
    }

    .jawaban-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }

    .opsi-list {
        list-style: none;
        padding: 0;
    }

    .opsi-item {
        padding: 10px 15px;
        margin: 5px 0;
        border-radius: 5px;
        background-color: #fff;
        border: 2px solid #e0e0e0;
    }

    .opsi-item.dipilih {
        background-color: #e3f2fd;
        border-color: #2196f3;
        font-weight: 600;
    }

    .opsi-item.benar {
        background-color: #e8f5e9;
        border-color: #4caf50;
        font-weight: 600;
    }

    .gambar-soal {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="bi bi-person-fill-check me-2"></i>Detail Jawaban Siswa
                    </h3>
                    <p class="text-muted mb-0">
                        Siswa: <strong>{{ $siswa->nama_lengkap }}</strong> |
                        Lesson: <strong>{{ $lessonFlow->judul_materi }}</strong>
                    </p>
                </div>
                <div>
                    <a href="{{ route('rekap-nilai-siswa.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Rekap
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
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
                    <i class="bi bi-percent text-info display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $persentase }}%</h3>
                    <p class="text-muted mb-0">Persentase</p>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar
                            @if($persentase >= 80) bg-success
                            @elseif($persentase >= 50) bg-warning
                            @else bg-danger
                            @endif"
                             style="width: {{ $persentase }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success display-4"></i>
                    <h3 class="fw-bold mt-2 text-success">{{ $totalBenar }}</h3>
                    <p class="text-muted mb-0">Jawaban Benar</p>
                    <small class="text-muted">dari {{ $totalSoal }} soal</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-x-circle-fill text-danger display-4"></i>
                    <h3 class="fw-bold mt-2 text-danger">{{ $totalSalah }}</h3>
                    <p class="text-muted mb-0">Jawaban Salah</p>
                    <small class="text-muted">Belum dijawab: {{ $totalSoal - $totalJawaban }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Siswa dan Lesson --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-badge me-2 text-primary"></i>Informasi Siswa
                    </h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%"><strong>Nama Lengkap</strong></td>
                            <td>: {{ $siswa->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: {{ $siswa->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Username</strong></td>
                            <td>: {{ $siswa->username }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-book me-2 text-success"></i>Informasi Lesson
                    </h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%"><strong>Judul Lesson</strong></td>
                            <td>: {{ $lessonFlow->judul_materi }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Item</strong></td>
                            <td>: {{ $lessonFlow->items->count() }} item pembelajaran</td>
                        </tr>
                        <tr>
                            <td><strong>Total Soal</strong></td>
                            <td>: {{ $totalSoal }} soal</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Jawaban --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>Detail Jawaban Per Soal
                    </h5>
                </div>

                <div class="card-body">
                    @php $nomorSoal = 1; @endphp
                    @foreach($lessonFlow->items as $item)
                        @if($item->tipe_item === 'soal_pg' || $item->tipe_item === 'soal_gambar' || $item->tipe_item === 'soal_isian')
                            @php
                                $jawaban = $jawabanSiswa->get($item->id);
                                $statusClass = 'belum-dijawab';
                                if($jawaban) {
                                    $statusClass = $jawaban->benar_salah ? 'benar' : 'salah';
                                }
                            @endphp

                            <div class="soal-card card mb-3 {{ $statusClass }}">
                                <div class="card-body">
                                    {{-- Header Soal --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0">
                                            <span class="badge bg-secondary me-2">Soal {{ $nomorSoal }}</span>
                                            @if($item->tipe_item === 'soal_pg')
                                                <span class="badge bg-info">Pilihan Ganda</span>
                                            @elseif($item->tipe_item === 'soal_gambar')
                                                <span class="badge bg-primary">Pilihan Gambar</span>
                                            @else
                                                <span class="badge bg-warning">Isian</span>
                                            @endif
                                        </h5>

                                        <div>
                                            @if($jawaban)
                                                @if($jawaban->benar_salah)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle me-1"></i>BENAR
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="bi bi-x-circle me-1"></i>SALAH
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning fs-6">
                                                    <i class="bi bi-hourglass-split me-1"></i>BELUM DIJAWAB
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Pertanyaan --}}
                                    <div class="mb-3">
                                        <strong>Pertanyaan:</strong>
                                        <p class="mb-0 mt-1">{{ $item->konten_soal }}</p>
                                    </div>

                                    {{-- Gambar Soal (jika ada) --}}
                                    @if($item->tipe_item === 'soal_gambar' && $item->url_gambar)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $item->url_gambar) }}"
                                             alt="Gambar Soal"
                                             class="gambar-soal">
                                    </div>
                                    @endif

                                    {{-- Opsi Soal (untuk PG dan Gambar) --}}
                                    @if($item->tipe_item === 'soal_pg' || $item->tipe_item === 'soal_gambar')
                                    <div class="mb-3">
                                        <strong>Opsi Jawaban:</strong>
                                        <ul class="opsi-list mt-2">
                                            @php
                                                $opsiLabels = ['A', 'B', 'C', 'D', 'E'];
                                                $opsiList = [
                                                    $item->opsi_a,
                                                    $item->opsi_b,
                                                    $item->opsi_c,
                                                    $item->opsi_d,
                                                    $item->opsi_e,
                                                ];
                                            @endphp

                                            @foreach($opsiList as $index => $opsi)
                                                @if($opsi)
                                                    @php
                                                        $isBenar = strtoupper($item->jawaban_benar) === $opsiLabels[$index];
                                                        $isDipilih = $jawaban && strtoupper($jawaban->jawaban_siswa) === $opsiLabels[$index];
                                                        $classOpsi = '';
                                                        if($isBenar) $classOpsi = 'benar';
                                                        elseif($isDipilih) $classOpsi = 'dipilih';
                                                    @endphp

                                                    <li class="opsi-item {{ $classOpsi }}">
                                                        <strong>{{ $opsiLabels[$index] }}.</strong> {{ $opsi }}
                                                        @if($isBenar)
                                                            <i class="bi bi-check-circle-fill text-success float-end"></i>
                                                        @endif
                                                        @if($isDipilih && !$isBenar)
                                                            <i class="bi bi-x-circle-fill text-danger float-end"></i>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    {{-- Jawaban Box --}}
                                    <div class="jawaban-box">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Jawaban Siswa:</strong>
                                                <p class="mb-0 mt-1 fw-bold
                                                    @if($jawaban)
                                                        {{ $jawaban->benar_salah ? 'text-success' : 'text-danger' }}
                                                    @else
                                                        text-muted
                                                    @endif">
                                                    {{ $jawaban ? $jawaban->jawaban_siswa : 'Belum dijawab' }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Jawaban Benar:</strong>
                                                <p class="mb-0 mt-1 fw-bold text-success">
                                                    {{ $item->jawaban_benar }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Poin:</strong>
                                                <p class="mb-0 mt-1 fw-bold text-primary">
                                                    {{ $jawaban ? $jawaban->poin_didapat : 0 }} / {{ $item->poin }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Penjelasan --}}
                                    @if($item->penjelasan)
                                    <div class="alert alert-info mt-3 mb-0">
                                        <strong><i class="bi bi-info-circle me-2"></i>Penjelasan:</strong>
                                        <p class="mb-0 mt-1">{{ $item->penjelasan }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @php $nomorSoal++; @endphp
                        @endif
                    @endforeach

                    @if($nomorSoal === 1)
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Belum ada soal dalam lesson flow ini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="row mt-4 mb-4">
        <div class="col-12 text-center">
            <a href="{{ route('rekap-nilai-siswa.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Rekap Nilai
            </a>
        </div>
    </div>
</div>
@endsection
