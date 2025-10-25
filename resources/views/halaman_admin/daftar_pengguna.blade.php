{{--
=====================================================
File: daftar_pengguna.blade.php
Deskripsi: Halaman daftar pengguna untuk admin
Pembuat: Fasisal Kasim
Tujuan: Menampilkan data pengguna dan aksi CRUD
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul', 'Daftar Pengguna - Sistem Deep Learning')

@section('judul_halaman', 'Kelola Pengguna')

{{-- Menu Sidebar untuk Admin --}}
@section('menu_sidebar')
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link active" href="{{ route('admin.pengguna.index') }}">
            <i class="bi bi-people-fill"></i> Kelola Pengguna
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-book-fill"></i> Kelola Materi
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-clipboard-check"></i> Kelola Nilai
        </a>
        <a class="nav-link" href="#">
            <i class="bi bi-gear-fill"></i> Pengaturan
        </a>
    </nav>
@endsection

{{-- Isi Konten Halaman --}}
@section('isi_halaman')
    {{-- Header dengan Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Daftar Semua Pengguna</h4>
            <p class="text-muted mb-0">Kelola data Admin, Guru, dan Siswa</p>
        </div>

        {{-- Tombol Tambah Pengguna Baru --}}
        <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pengguna Baru
        </a>
    </div>

    {{-- Filter Berdasarkan Role --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pengguna.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="filter_role" class="form-label">Filter Berdasarkan Role</label>
                    <select name="role" id="filter_role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cari" class="form-label">Cari Nama/Email/Username</label>
                    <input type="text" name="cari" id="cari" class="form-control"
                           placeholder="Ketik untuk mencari..." value="{{ request('cari') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Pengguna --}}
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Data Pengguna
                <span class="badge bg-primary">{{ $daftar_pengguna->total() }} Pengguna</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 80px;">Foto</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIP/NIS</th>
                            <th>Kelas</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_pengguna as $index => $pengguna)
                            <tr>
                                {{-- Nomor Urut --}}
                                <td>{{ $daftar_pengguna->firstItem() + $index }}</td>

                                {{-- Foto Profil --}}
                                <td>
                                    @if($pengguna->foto)
                                        <img src="{{ asset('storage/foto_profil/' . $pengguna->foto) }}"
                                             alt="Foto {{ $pengguna->name }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nama Lengkap --}}
                                <td><strong>{{ $pengguna->name }}</strong></td>

                                {{-- Username --}}
                                <td>{{ $pengguna->username }}</td>

                                {{-- Email --}}
                                <td>{{ $pengguna->email }}</td>

                                {{-- Role dengan Badge Warna --}}
                                <td>
                                    @if($pengguna->role == 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($pengguna->role == 'guru')
                                        <span class="badge bg-success">Guru</span>
                                    @else
                                        <span class="badge bg-primary">Siswa</span>
                                    @endif
                                </td>

                                {{-- NIP/NIS --}}
                                <td>{{ $pengguna->nip_nis ?? '-' }}</td>

                                {{-- Kelas --}}
                                <td>{{ $pengguna->kelas ?? '-' }}</td>

                                {{-- Tombol Aksi --}}
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Lihat Detail --}}
                                        <a href="{{ route('admin.pengguna.show', $pengguna->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.pengguna.edit', $pengguna->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Tombol Hapus (dengan Modal) --}}
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapus{{ $pengguna->id }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi Hapus --}}
                            <div class="modal fade" id="modalHapus{{ $pengguna->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus pengguna:</p>
                                            <div class="alert alert-warning">
                                                <strong>Nama:</strong> {{ $pengguna->name }}<br>
                                                <strong>Username:</strong> {{ $pengguna->username }}<br>
                                                <strong>Role:</strong> {{ ucfirst($pengguna->role) }}
                                            </div>
                                            <p class="text-danger">
                                                <i class="bi bi-info-circle"></i>
                                                Data yang sudah dihapus tidak dapat dikembalikan!
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                            <form action="{{ route('admin.pengguna.destroy', $pengguna->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Ya, Hapus!
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <p>Tidak ada data pengguna</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $daftar_pengguna->firstItem() ?? 0 }} - {{ $daftar_pengguna->lastItem() ?? 0 }}
                    dari {{ $daftar_pengguna->total() }} pengguna
                </div>
                <div>
                    {{ $daftar_pengguna->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
