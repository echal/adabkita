{{--
=====================================================
HALAMAN REKAP NILAI SISWA
=====================================================
Halaman ini menampilkan rekap nilai semua siswa yang
telah mengerjakan lesson flow yang dibuat oleh guru.

Fitur:
- Tabel dengan DataTables (sort, search, pagination)
- Kolom: Nama Siswa, Judul Lesson, Nilai, Status
- Tombol detail untuk melihat jawaban lengkap
- Filter interaktif dan export data

@package Resources\Views\RekapNilai
@author System
@created 2025-10-16
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Rekap Nilai Siswa')

{{-- Include DataTables CSS --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
    /* Custom styling untuk tabel */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .badge-status {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .progress-bar-custom {
        height: 25px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Styling untuk card header */
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
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
                        <i class="bi bi-clipboard-data-fill me-2"></i>Rekap Nilai Siswa
                    </h3>
                    <p class="text-muted">Pantau progres dan nilai siswa yang mengerjakan lesson interaktif Anda</p>
                </div>
                <div>
                    <a href="{{ route('lesson-flow.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Lesson Flow
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Tabel Rekap --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>Daftar Rekap Nilai
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Info jika belum ada data --}}
                    @if($rekapData->isEmpty())
                    <div class="alert alert-info text-center py-5">
                        <i class="bi bi-info-circle display-4 d-block mb-3"></i>
                        <h5>Belum Ada Data Rekap Nilai</h5>
                        <p class="text-muted mb-0">
                            Siswa belum ada yang mengerjakan lesson flow Anda.
                            Pastikan lesson sudah dipublikasikan dan siswa mengakses lesson interaktif.
                        </p>
                    </div>
                    @else
                    {{-- Tabel DataTables --}}
                    <div class="table-responsive">
                        <table id="tabelRekapNilai" class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">No</th>
                                    <th style="width: 20%;">Nama Siswa</th>
                                    <th style="width: 25%;">Judul Lesson</th>
                                    <th class="text-center" style="width: 10%;">Nilai</th>
                                    <th class="text-center" style="width: 12%;">Progres</th>
                                    <th class="text-center" style="width: 12%;">Status</th>
                                    <th class="text-center" style="width: 16%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapData as $index => $data)
                                <tr>
                                    {{-- Nomor --}}
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    {{-- Nama Siswa --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ strtoupper(substr($data['nama_siswa'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $data['nama_siswa'] }}</div>
                                                <small class="text-muted">{{ $data['email_siswa'] }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Judul Lesson --}}
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $data['judul_lesson'] }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($data['terakhir_dikerjakan'])->format('d M Y, H:i') }}
                                        </small>
                                    </td>

                                    {{-- Nilai --}}
                                    <td class="text-center">
                                        <div class="fw-bold text-primary fs-5">{{ $data['total_poin'] }}</div>
                                        <small class="text-muted">dari {{ $data['max_poin'] }}</small>
                                    </td>

                                    {{-- Progres dengan Progress Bar --}}
                                    <td>
                                        <div class="progress progress-bar-custom">
                                            <div class="progress-bar
                                                @if($data['persentase'] >= 80) bg-success
                                                @elseif($data['persentase'] >= 50) bg-warning
                                                @else bg-danger
                                                @endif"
                                                 role="progressbar"
                                                 style="width: {{ $data['persentase'] }}%"
                                                 aria-valuenow="{{ $data['persentase'] }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ number_format($data['persentase'], 1) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted d-block text-center mt-1">
                                            {{ $data['total_jawaban'] }}/{{ $data['total_soal'] }} soal
                                        </small>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if($data['status'] === 'Selesai')
                                            <span class="badge bg-success badge-status">
                                                <i class="bi bi-check-circle me-1"></i>Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-warning badge-status">
                                                <i class="bi bi-hourglass-split me-1"></i>Belum Selesai
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        <a href="{{ route('rekap-nilai-siswa.detail', ['idSiswa' => $data['id_siswa'], 'idLessonFlow' => $data['id_lesson_flow']]) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Lihat Detail Jawaban">
                                            <i class="bi bi-eye me-1"></i>Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    @if(!$rekapData->isEmpty())
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $rekapData->count() }}</h3>
                    <p class="text-muted mb-0">Total Rekap</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $rekapData->where('status', 'Selesai')->count() }}</h3>
                    <p class="text-muted mb-0">Lesson Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-hourglass-split text-warning display-4"></i>
                    <h3 class="fw-bold mt-2">{{ $rekapData->where('status', 'Belum Selesai')->count() }}</h3>
                    <p class="text-muted mb-0">Belum Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <i class="bi bi-graph-up text-info display-4"></i>
                    <h3 class="fw-bold mt-2">{{ number_format($rekapData->avg('persentase'), 1) }}%</h3>
                    <p class="text-muted mb-0">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

{{-- Include DataTables JS --}}
@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#tabelRekapNilai').DataTable({
        // Bahasa Indonesia
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Tidak ada data yang ditemukan',
            emptyTable: 'Tidak ada data di tabel',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        },

        // Pengaturan tampilan
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
        order: [[0, 'asc']], // Urutkan berdasarkan nomor

        // Tombol export
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12 col-md-4"B>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',

        buttons: [
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel me-1"></i> Export Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 5] // Kolom yang di-export
                }
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i> Export PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 5]
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i> Cetak',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 5]
                }
            }
        ],

        // Responsif
        responsive: true,

        // Styling zebra
        stripeClasses: ['odd-row', 'even-row']
    });
});
</script>
@endpush
