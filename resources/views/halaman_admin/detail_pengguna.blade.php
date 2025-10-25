{{--
=====================================================
File: detail_pengguna.blade.php
Deskripsi: Halaman detail/profil pengguna
Pembuat: Fasisal Kasim
Tujuan: Menampilkan informasi lengkap pengguna
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul', 'Detail Pengguna - ' . $pengguna->name)

@section('judul_halaman', 'Detail Pengguna')

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
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pengguna.index') }}">Kelola Pengguna</a></li>
            <li class="breadcrumb-item active">Detail Pengguna</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Kolom Kiri: Foto & Info Singkat --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    {{-- Foto Profil --}}
                    @if($pengguna->foto)
                        <img src="{{ asset('storage/foto_profil/' . $pengguna->foto) }}"
                             alt="Foto {{ $pengguna->name }}"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person-fill" style="font-size: 80px;"></i>
                        </div>
                    @endif

                    {{-- Nama --}}
                    <h4 class="mb-1">{{ $pengguna->name }}</h4>

                    {{-- Role Badge --}}
                    @if($pengguna->role == 'admin')
                        <span class="badge bg-danger fs-6">Administrator</span>
                    @elseif($pengguna->role == 'guru')
                        <span class="badge bg-success fs-6">Guru</span>
                    @else
                        <span class="badge bg-primary fs-6">Siswa</span>
                    @endif

                    {{-- Username --}}
                    <p class="text-muted mt-2 mb-0">
                        <i class="bi bi-at"></i> {{ $pengguna->username }}
                    </p>

                    <hr>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pengguna.edit', $pengguna->id) }}"
                           class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Data
                        </a>
                        <a href="{{ route('admin.pengguna.index') }}"
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Info Akun --}}
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Akun
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><i class="bi bi-calendar-plus text-primary"></i> Terdaftar</td>
                            <td class="text-end">{{ $pengguna->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-calendar-check text-success"></i> Update Terakhir</td>
                            <td class="text-end">{{ $pengguna->updated_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-clock text-info"></i> Jam</td>
                            <td class="text-end">{{ $pengguna->updated_at->format('H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Data Lengkap --}}
        <div class="col-md-8">
            {{-- Data Pribadi --}}
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge text-primary"></i> Data Pribadi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">
                            <i class="bi bi-person"></i> Nama Lengkap
                        </div>
                        <div class="col-md-8">
                            <strong>{{ $pengguna->name }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">
                            <i class="bi bi-envelope"></i> Email
                        </div>
                        <div class="col-md-8">
                            <strong>{{ $pengguna->email }}</strong>
                            <a href="mailto:{{ $pengguna->email }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="bi bi-send"></i> Kirim Email
                            </a>
                        </div>
                    </div>

                    @if($pengguna->no_telepon)
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                <i class="bi bi-telephone"></i> Nomor Telepon
                            </div>
                            <div class="col-md-8">
                                <strong>{{ $pengguna->no_telepon }}</strong>
                                <a href="tel:{{ $pengguna->no_telepon }}" class="btn btn-sm btn-outline-success ms-2">
                                    <i class="bi bi-telephone"></i> Hubungi
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">
                            <i class="bi bi-shield-check"></i> Role/Peran
                        </div>
                        <div class="col-md-8">
                            <strong>{{ ucfirst($pengguna->role) }}</strong>
                            @if($pengguna->role == 'admin')
                                <span class="badge bg-danger">Akses Penuh</span>
                            @elseif($pengguna->role == 'guru')
                                <span class="badge bg-success">Pengajar</span>
                            @else
                                <span class="badge bg-primary">Pelajar</span>
                            @endif
                        </div>
                    </div>

                    @if($pengguna->nip_nis)
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                <i class="bi bi-card-text"></i>
                                {{ $pengguna->role == 'guru' ? 'NIP' : 'NIS' }}
                            </div>
                            <div class="col-md-8">
                                <strong>{{ $pengguna->nip_nis }}</strong>
                            </div>
                        </div>
                    @endif

                    @if($pengguna->kelas)
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">
                                <i class="bi bi-bookmark"></i>
                                {{ $pengguna->role == 'guru' ? 'Mata Pelajaran' : 'Kelas' }}
                            </div>
                            <div class="col-md-8">
                                <strong>{{ $pengguna->kelas }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Statistik (Untuk Guru dan Siswa) --}}
            @if($pengguna->role == 'guru')
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart text-success"></i> Statistik Guru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-primary mb-0">0</h3>
                                    <small class="text-muted">Materi Dibuat</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-success mb-0">0</h3>
                                    <small class="text-muted">Siswa Aktif</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-warning mb-0">0</h3>
                                    <small class="text-muted">Tugas Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle"></i>
                            Statistik akan muncul setelah guru mulai membuat materi dan mengelola siswa.
                        </div>
                    </div>
                </div>
            @elseif($pengguna->role == 'siswa')
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up text-primary"></i> Statistik Siswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-primary mb-0">0</h3>
                                    <small class="text-muted">Materi Dipelajari</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-success mb-0">0</h3>
                                    <small class="text-muted">Tugas Selesai</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-warning mb-0">-</h3>
                                    <small class="text-muted">Nilai Rata-rata</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle"></i>
                            Statistik akan muncul setelah siswa mulai mengerjakan tugas dan mendapat nilai.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
