{{--
    View: Daftar Materi Pembelajaran

    Halaman ini menampilkan semua materi pembelajaran yang tersedia
    dalam bentuk tabel yang responsif menggunakan Bootstrap 5.

    Fitur:
    - Tabel daftar materi dengan informasi lengkap
    - Tombol tambah materi (hanya untuk Admin & Guru)
    - Tombol aksi (Edit, Hapus, Lihat Detail)
    - Pagination
    - Notifikasi success/error

    @created 2025-10-15
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Daftar Materi Pembelajaran')

@section('isi_halaman')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                {{-- Header Card --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-book-fill me-2"></i>Daftar Materi Pembelajaran
                    </h5>

                    {{-- Tombol Tambah Materi (hanya untuk Admin & Guru) --}}
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
                    <a href="{{ route('materi.create') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Materi
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Notifikasi Success --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Notifikasi Error --}}
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- Tabel Daftar Materi --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Judul Materi</th>
                                    <th width="25%">Deskripsi</th>
                                    <th width="10%">Kategori</th>
                                    <th width="10%">Dibuat Oleh</th>
                                    <th width="10%">Tanggal Upload</th>
                                    <th width="10%">File</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materi as $index => $item)
                                <tr>
                                    {{-- Nomor urut dengan pagination --}}
                                    <td>{{ $materi->firstItem() + $index }}</td>

                                    {{-- Judul Materi --}}
                                    <td>
                                        <strong>{{ $item->judul_materi }}</strong>
                                    </td>

                                    {{-- Deskripsi (dipotong jika terlalu panjang) --}}
                                    <td>
                                        {{ Str::limit($item->deskripsi, 80) }}
                                    </td>

                                    {{-- Kategori dengan badge --}}
                                    <td>
                                        <span class="badge bg-info">{{ $item->kategori }}</span>
                                    </td>

                                    {{-- Pembuat Materi --}}
                                    <td>{{ $item->dibuat_oleh }}</td>

                                    {{-- Tanggal Upload --}}
                                    <td>
                                        <small>{{ $item->tanggal_upload->format('d M Y') }}</small>
                                    </td>

                                    {{-- File Materi / Link Embed --}}
                                    <td>
                                        @if($item->hasEmbed())
                                            <span class="badge bg-success" title="Materi dengan Link Embed">
                                                <i class="bi bi-globe"></i> Embed
                                            </span>
                                        @elseif($item->isPowerPoint())
                                            <a href="{{ $item->url_file }}" target="_blank" class="btn btn-sm btn-outline-danger" title="File PowerPoint">
                                                <i class="bi bi-file-earmark-ppt"></i>
                                            </a>
                                        @elseif($item->isPDF())
                                            <a href="{{ $item->url_file }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="File PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        @elseif($item->file_materi)
                                            <a href="{{ $item->url_file }}" target="_blank" class="btn btn-sm btn-outline-primary" title="File {{ strtoupper($item->getFileExtension()) }}">
                                                <i class="bi bi-file-earmark"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Tombol Aksi --}}
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- Tombol Lihat Detail (semua role) --}}
                                            <a href="{{ route('materi.show', $item->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- Tombol Edit & Hapus (hanya Admin & Guru) --}}
                                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
                                            <a href="{{ route('materi.edit', $item->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('materi.destroy', $item->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada materi yang tersedia
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $materi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Custom CSS untuk mempercantik tampilan --}}
@push('styles')
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .table th {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.8em;
    }
</style>
@endpush

{{-- Custom JavaScript jika diperlukan --}}
@push('scripts')
<script>
    // Auto-hide alert setelah 5 detik
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
