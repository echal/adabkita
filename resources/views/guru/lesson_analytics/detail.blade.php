{{--
=====================================================
HALAMAN DETAIL ANALYTICS PER LESSON
=====================================================
Halaman ini menampilkan analytics detail untuk satu
lesson flow tertentu dengan grafik lebih mendalam.

Fitur:
- Grafik distribusi skor siswa
- Grafik distribusi badge
- Tabel detail per siswa
- Statistik lesson

@package Resources\Views\Guru\LessonAnalytics
@author System
@created 2025-10-17
@phase Phase 5 - Analytics & Rekap Nilai Guru
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', '📊 Detail Analytics - ' . $lessonFlow->judul_materi)

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- ============================================ --}}
    {{-- HEADER & BREADCRUMB --}}
    {{-- ============================================ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-2">📊 Detail Analytics</h3>
                    <h5 class="text-primary mb-2">{{ $lessonFlow->judul_materi }}</h5>
                    <p class="text-muted mb-0">
                        {{ $lessonFlow->deskripsi }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('guru.lesson-analytics.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- STATISTIK LESSON (CARDS) --}}
    {{-- ============================================ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-success">
                <div class="card-body">
                    <i class="bi bi-people-fill text-success display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $totalSiswa }}</h3>
                    <p class="text-muted mb-0">Total Siswa</p>
                    <small class="text-muted">Telah menyelesaikan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-warning">
                <div class="card-body">
                    <i class="bi bi-graph-up text-warning display-4"></i>
                    <h3 class="fw-bold mt-2">{{ number_format($rataRata, 2) }}%</h3>
                    <p class="text-muted mb-0">Rata-Rata Skor</p>
                    <small class="text-muted">Lesson ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow-sm h-100 border-warning">
                <div class="card-body">
                    <div style="font-size: 48px;">🥇</div>
                    <h3 class="fw-bold mt-2">{{ $badgeDistribution['gold'] }}</h3>
                    <p class="text-muted mb-0">Gold Badge</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow-sm h-100 border-secondary">
                <div class="card-body">
                    <div style="font-size: 48px;">🥈</div>
                    <h3 class="fw-bold mt-2">{{ $badgeDistribution['silver'] }}</h3>
                    <p class="text-muted mb-0">Silver Badge</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card text-center shadow-sm h-100 border-danger">
                <div class="card-body">
                    <div style="font-size: 48px;">🥉</div>
                    <h3 class="fw-bold mt-2">{{ $badgeDistribution['bronze'] }}</h3>
                    <p class="text-muted mb-0">Bronze Badge</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- GRAFIK DISTRIBUSI BADGE (PIE CHART) --}}
    {{-- ============================================ --}}
    @if($totalSiswa > 0)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart-fill me-2"></i>Distribusi Badge</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartBadgePie" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Distribusi Skor Siswa</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSkorDistribution" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- TABEL DETAIL SISWA --}}
    {{-- ============================================ --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Detail Hasil Siswa</h5>
            <span class="badge bg-light text-dark">{{ $totalSiswa }} Siswa</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Badge</th>
                            <th class="text-center">Benar</th>
                            <th class="text-center">Salah</th>
                            <th class="text-center">Total Soal</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailData as $index => $data)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                <strong>{{ $data['siswa']->name }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $data['skor'] >= 90 ? 'success' : ($data['skor'] >= 75 ? 'info' : 'danger') }} fs-6">
                                    {{ number_format($data['skor'], 2) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span style="font-size: 32px;">{{ $data['badge_icon'] }}</span>
                                <br>
                                <small class="text-muted text-uppercase">{{ $data['badge'] }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $data['total_benar'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $data['total_salah'] }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ $data['total_soal'] }}</strong>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-clock me-1"></i>
                                {{ $data['durasi'] }}
                            </td>
                            <td class="text-center">
                                <small>{{ \Carbon\Carbon::parse($data['waktu_selesai'])->format('d M Y, H:i') }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info text-center">
        <i class="bi bi-inbox display-4"></i>
        <p class="mt-3 mb-0">Belum ada siswa yang menyelesaikan lesson ini.</p>
    </div>
    @endif
</div>

{{-- ============================================ --}}
{{-- SECTION: CHART.JS SCRIPTS --}}
{{-- ============================================ --}}
@push('js_tambahan')
{{-- Chart.js Library (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// ============================================
// DATA DARI CONTROLLER
// ============================================
const detailData = @json($detailData);
const badgeDistribution = @json($badgeDistribution);

@if($totalSiswa > 0)
// ============================================
// GRAFIK 1: PIE CHART DISTRIBUSI BADGE
// ============================================
const ctxPie = document.getElementById('chartBadgePie');
if (ctxPie) {
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['🥇 Gold', '🥈 Silver', '🥉 Bronze'],
            datasets: [{
                data: [
                    badgeDistribution.gold,
                    badgeDistribution.silver,
                    badgeDistribution.bronze
                ],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(108, 117, 125, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(108, 117, 125, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = badgeDistribution.gold + badgeDistribution.silver + badgeDistribution.bronze;
                            const percentage = ((context.parsed / total) * 100).toFixed(2);
                            return context.label + ': ' + context.parsed + ' siswa (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

// ============================================
// GRAFIK 2: HISTOGRAM DISTRIBUSI SKOR
// ============================================
const ctxSkor = document.getElementById('chartSkorDistribution');
if (ctxSkor) {
    // Kelompokkan skor ke dalam range
    const skorRanges = {
        '90-100': 0,
        '75-89': 0,
        '60-74': 0,
        '0-59': 0
    };

    detailData.forEach(data => {
        const skor = data.skor;
        if (skor >= 90) {
            skorRanges['90-100']++;
        } else if (skor >= 75) {
            skorRanges['75-89']++;
        } else if (skor >= 60) {
            skorRanges['60-74']++;
        } else {
            skorRanges['0-59']++;
        }
    });

    new Chart(ctxSkor, {
        type: 'bar',
        data: {
            labels: ['90-100%', '75-89%', '60-74%', '0-59%'],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [
                    skorRanges['90-100'],
                    skorRanges['75-89'],
                    skorRanges['60-74'],
                    skorRanges['0-59']
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Siswa: ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
}
@endif

// ✅ Phase 5: Analytics Detail - View complete
</script>
@endpush

@endsection
