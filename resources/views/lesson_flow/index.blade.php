{{--
=====================================================
HALAMAN DAFTAR LESSON FLOW
=====================================================
Halaman ini menampilkan daftar lesson flow yang
dibuat oleh guru yang sedang login.

Fitur:
- Melihat daftar lesson flow dengan card
- Tombol tambah lesson flow baru
- Tombol edit, lihat, dan hapus untuk setiap flow
- Badge status (Draft, Dipublikasi, Diarsipkan)
- Info jumlah item dalam flow

@package Resources\Views\LessonFlow
@author System
@created 2025-10-15
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Kelola Lesson Flow Interaktif')

@section('isi_halaman')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            {{-- Header dengan tombol tambah --}}
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-primary mb-1">
                        <i class="bi bi-diagram-3-fill me-2"></i>Kelola Lesson Flow Interaktif
                    </h3>
                    <p class="text-muted">Buat dan kelola alur pembelajaran interaktif dengan video, gambar, dan soal</p>
                </div>
                <div>
                    {{-- Tombol Buat Lesson Baru --}}
                    <a href="{{ route('lesson-flow.create') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Buat Lesson Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

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

    {{-- Daftar Lesson Flow dalam Grid Card --}}
    <div class="row g-4">
        @forelse($lessonFlows as $flow)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm hover-card">
                {{-- Header Card dengan Badge Status --}}
                <div class="card-header bg-light border-bottom-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title mb-0 text-truncate" style="max-width: 70%;" title="{{ $flow->judul_materi }}">
                            {{ $flow->judul_materi }}
                        </h5>
                        <span class="badge bg-{{ $flow->status === 'published' ? 'success' : ($flow->status === 'draft' ? 'warning' : 'secondary') }}">
                            {{ $flow->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Body Card --}}
                <div class="card-body">
                    {{-- Deskripsi --}}
                    <p class="card-text text-muted small mb-3" style="min-height: 60px;">
                        {{ Str::limit($flow->deskripsi, 100) ?: 'Tidak ada deskripsi' }}
                    </p>

                    {{-- Info Item --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            <i class="bi bi-list-ol me-1"></i>
                            <strong>{{ $flow->items_count }}</strong> item pembelajaran
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $flow->created_at->format('d M Y') }}
                        </div>
                    </div>

                    {{-- Tanggal Aktif (jika ada) --}}
                    @if($flow->tanggal_mulai || $flow->tanggal_selesai)
                    <div class="alert alert-info py-1 px-2 small mb-3">
                        <i class="bi bi-calendar-range me-1"></i>
                        @if($flow->tanggal_mulai && $flow->tanggal_selesai)
                            {{ $flow->tanggal_mulai->format('d M Y') }} - {{ $flow->tanggal_selesai->format('d M Y') }}
                        @elseif($flow->tanggal_mulai)
                            Mulai: {{ $flow->tanggal_mulai->format('d M Y') }}
                        @else
                            Selesai: {{ $flow->tanggal_selesai->format('d M Y') }}
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Footer Card dengan Tombol Aksi --}}
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Tombol Lihat --}}
                        <a href="{{ route('lesson-flow.show', $flow->id) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="Lihat Detail">
                            <i class="bi bi-eye"></i> Lihat
                        </a>

                        {{-- Tombol Edit --}}
                        <a href="{{ route('lesson-flow.edit', $flow->id) }}"
                           class="btn btn-sm btn-outline-success"
                           title="Edit & Kelola Item">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                        {{-- Tombol Publikasikan (jika masih draft) --}}
                        @if($flow->status === 'draft' && $flow->items_count > 0)
                        <form action="{{ route('lesson-flow.publish', $flow->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-sm btn-info text-white"
                                    title="Publikasikan">
                                <i class="bi bi-cloud-upload"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Tombol Hapus --}}
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $flow->id }}"
                                title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <div class="modal fade" id="deleteModal{{ $flow->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus lesson flow:</p>
                        <p class="fw-bold text-danger">{{ $flow->judul_materi }}</p>
                        <p class="small text-muted">Semua item pembelajaran dan jawaban siswa akan ikut terhapus!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('lesson-flow.destroy', $flow->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Jika belum ada lesson flow --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Belum Ada Lesson Flow</h4>
                    <p class="text-muted">Mulai buat lesson flow interaktif pertama Anda!</p>
                    <a href="{{ route('lesson-flow.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Buat Lesson Baru
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($lessonFlows->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $lessonFlows->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Custom CSS untuk hover effect --}}
<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

{{-- Auto-hide alert setelah 5 detik --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alerts setelah 5 detik
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection
