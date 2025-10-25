{{--
=====================================================
HALAMAN ANALYTICS & REKAP NILAI GURU
=====================================================
Halaman ini menampilkan analytics pembelajaran interaktif
untuk semua lesson flow yang dibuat oleh guru.

Fitur:
- Grafik rata-rata nilai per lesson (Chart.js)
- Tabel rekap detail nilai siswa
- Statistik keseluruhan (total lesson, siswa aktif, dll)
- Filter berdasarkan lesson

@package Resources\Views\Guru\LessonAnalytics
@author System
@created 2025-10-17
@phase Phase 5 - Analytics & Rekap Nilai Guru
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', '📊 Analytics & Rekap Nilai Pembelajaran')

@section('isi_halaman')
<div class="container-fluid px-4">
    {{-- ============================================ --}}
    {{-- HEADER & BREADCRUMB --}}
    {{-- ============================================ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-2">📊 Analytics Pembelajaran Interaktif</h3>
                    <p class="text-muted mb-0">
                        Pantau performa siswa dalam mengikuti lesson flow yang Anda buat.
                        Grafik dan tabel di bawah membantu Anda memahami kemajuan belajar siswa.
                    </p>
                </div>
                <div>
                    <a href="{{ route('lesson-flow.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Lesson Flow
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- STATISTIK KESELURUHAN (CARDS) --}}
    {{-- ============================================ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-primary">
                <div class="card-body">
                    <i class="bi bi-book-fill text-primary display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $statistik['total_lessons'] }}</h3>
                    <p class="text-muted mb-0">Total Lesson</p>
                    <small class="text-muted">Sudah dipublikasi</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-success">
                <div class="card-body">
                    <i class="bi bi-people-fill text-success display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $statistik['total_siswa_aktif'] }}</h3>
                    <p class="text-muted mb-0">Siswa Aktif</p>
                    <small class="text-muted">Telah menyelesaikan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-warning">
                <div class="card-body">
                    <i class="bi bi-graph-up-arrow text-warning display-4"></i>
                    <h3 class="fw-bold mt-2">{{ number_format($statistik['rata_rata_keseluruhan'], 2) }}%</h3>
                    <p class="text-muted mb-0">Rata-Rata Nilai</p>
                    <small class="text-muted">Keseluruhan lesson</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm h-100 border-info">
                <div class="card-body">
                    <i class="bi bi-clipboard-check-fill text-info display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $statistik['total_penyelesaian'] }}</h3>
                    <p class="text-muted mb-0">Total Penyelesaian</p>
                    <small class="text-muted">Lesson selesai dikerjakan</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- BAGIAN 1: GRAFIK RATA-RATA NILAI PER LESSON --}}
    {{-- ============================================ --}}
    @if(count($analytics) > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Grafik Rata-Rata Nilai per Lesson</h5>
            <span class="badge bg-light text-dark">{{ count($analytics) }} Lesson</span>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Grafik ini menampilkan rata-rata nilai siswa untuk setiap lesson flow.
                Semakin tinggi bar, semakin baik performa siswa pada lesson tersebut.
            </p>
            {{-- Canvas untuk Chart.js --}}
            <canvas id="chartNilai" height="80"></canvas>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- BAGIAN 1.5: GRAFIK DISTRIBUSI BADGE --}}
    {{-- ============================================ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Distribusi Badge per Lesson</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Grafik ini menampilkan distribusi badge (Gold, Silver, Bronze) untuk setiap lesson.
            </p>
            {{-- Canvas untuk Chart.js Badge Distribution --}}
            <canvas id="chartBadge" height="80"></canvas>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Belum ada data analytics.</strong><br>
        Anda belum memiliki lesson flow yang dipublikasi atau belum ada siswa yang menyelesaikan lesson.
    </div>
    @endif

    {{-- ============================================ --}}
    {{-- BAGIAN 2: TABEL REKAP NILAI SISWA --}}
    {{-- ============================================ --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Rekap Detail Nilai Siswa</h5>
            <span class="badge bg-light text-dark">{{ count($rekap) }} Data</span>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <i class="bi bi-info-circle me-1"></i>
                Tabel ini menampilkan detail nilai setiap siswa untuk semua lesson flow.
                Anda dapat melihat skor, badge, dan waktu penyelesaian.
            </p>

            @if(count($rekap) > 0)
            {{-- Filter / Search Box --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchTable" class="form-control" placeholder="🔍 Cari nama siswa atau lesson...">
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-success" onclick="exportToExcel()">
                        <i class="bi bi-file-excel me-2"></i>Export ke Excel
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="rekapTable">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th>Lesson Flow</th>
                            <th class="text-center">Skor</th>
                            <th class="text-center">Badge</th>
                            <th class="text-center">Benar/Total</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Waktu Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekap as $index => $r)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                <strong>{{ $r['siswa_nama'] }}</strong>
                            </td>
                            <td>{{ $r['lesson_judul'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $r['skor'] >= 90 ? 'success' : ($r['skor'] >= 75 ? 'info' : 'danger') }} fs-6">
                                    {{ number_format($r['skor'], 2) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span style="font-size: 24px;">{{ $r['badge_icon'] }}</span>
                                <br>
                                <small class="text-muted text-uppercase">{{ $r['badge'] }}</small>
                            </td>
                            <td class="text-center">
                                <strong class="text-success">{{ $r['total_benar'] }}</strong> /
                                <strong class="text-danger">{{ $r['total_salah'] }}</strong> /
                                <span class="text-muted">{{ $r['total_soal'] }}</span>
                            </td>
                            <td class="text-center">
                                <i class="bi bi-clock me-1"></i>
                                {{ $r['durasi'] }}
                            </td>
                            <td class="text-center">
                                <small>{{ \Carbon\Carbon::parse($r['waktu_selesai'])->format('d M Y, H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('guru.lesson-analytics.detail', $r['lesson_id']) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Lihat detail analytics lesson ini">
                                    <i class="bi bi-bar-chart"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info text-center">
                <i class="bi bi-inbox display-4"></i>
                <p class="mt-3 mb-0">Belum ada data rekap nilai. Siswa belum menyelesaikan lesson flow Anda.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SECTION: CHART.JS CDN & SCRIPTS --}}
{{-- ============================================ --}}
@push('js_tambahan')
{{-- Chart.js Library (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// ============================================
// DATA DARI CONTROLLER (BLADE TO JAVASCRIPT)
// ============================================
const analyticsData = @json($analytics);
const rekapData = @json($rekap);

// ============================================
// GRAFIK 1: RATA-RATA NILAI PER LESSON
// ============================================
@if(count($analytics) > 0)
const ctxNilai = document.getElementById('chartNilai');
if (ctxNilai) {
    new Chart(ctxNilai, {
        type: 'bar',
        data: {
            labels: analyticsData.map(item => item.lesson_judul),
            datasets: [{
                label: 'Rata-Rata Nilai (%)',
                data: analyticsData.map(item => item.rata_rata),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
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
                            return 'Rata-rata: ' + context.parsed.y.toFixed(2) + '%';
                        },
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            const data = analyticsData[index];
                            return [
                                'Total Siswa: ' + data.total_siswa,
                                'Gold: ' + data.gold_count,
                                'Silver: ' + data.silver_count,
                                'Bronze: ' + data.bronze_count
                            ];
                        }
                    }
                }
            }
        }
    });
}

// ============================================
// GRAFIK 2: DISTRIBUSI BADGE PER LESSON
// ============================================
const ctxBadge = document.getElementById('chartBadge');
if (ctxBadge) {
    new Chart(ctxBadge, {
        type: 'bar',
        data: {
            labels: analyticsData.map(item => item.lesson_judul),
            datasets: [
                {
                    label: '🥇 Gold',
                    data: analyticsData.map(item => item.gold_count),
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                },
                {
                    label: '🥈 Silver',
                    data: analyticsData.map(item => item.silver_count),
                    backgroundColor: 'rgba(108, 117, 125, 0.8)',
                    borderColor: 'rgba(108, 117, 125, 1)',
                    borderWidth: 1,
                },
                {
                    label: '🥉 Bronze',
                    data: analyticsData.map(item => item.bronze_count),
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' siswa';
                        }
                    }
                }
            }
        }
    });
}
@endif

// ============================================
// FITUR: SEARCH/FILTER TABEL
// ============================================
const searchInput = document.getElementById('searchTable');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const table = document.getElementById('rekapTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });
}

// ============================================
// FITUR: EXPORT TO EXCEL (SIMPLE)
// ============================================
function exportToExcel() {
    // Get table
    const table = document.getElementById('rekapTable');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Rekap Nilai"});

    // Generate filename with timestamp
    const filename = 'Rekap_Nilai_Lesson_' + new Date().toISOString().slice(0,10) + '.xlsx';

    // Download
    XLSX.writeFile(wb, filename);
}

// ✅ Phase 5: Analytics & Rekap Nilai Guru - View complete
</script>

{{-- SheetJS Library untuk Export Excel (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
@endpush

@endsection
