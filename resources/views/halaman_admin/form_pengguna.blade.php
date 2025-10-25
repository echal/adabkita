{{--
=====================================================
File: form_pengguna.blade.php
Deskripsi: Form untuk tambah dan edit pengguna
Pembuat: Fasisal Kasim
Tujuan: Form input data pengguna (bisa untuk tambah atau edit)
=====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul', ($pengguna->exists ? 'Edit' : 'Tambah') . ' Pengguna - Sistem Deep Learning')

@section('judul_halaman', ($pengguna->exists ? 'Edit' : 'Tambah') . ' Pengguna')

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
            <li class="breadcrumb-item active">{{ $pengguna->exists ? 'Edit' : 'Tambah' }} Pengguna</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi {{ $pengguna->exists ? 'bi-pencil' : 'bi-plus-circle' }}"></i>
                        Form {{ $pengguna->exists ? 'Edit' : 'Tambah' }} Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Form untuk Tambah/Edit --}}
                    <form action="{{ $pengguna->exists ? route('admin.pengguna.update', $pengguna->id) : route('admin.pengguna.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if($pengguna->exists)
                            @method('PUT')
                        @endif

                        {{-- Bagian 1: Data Diri --}}
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-person-badge"></i> Data Diri
                        </h6>

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $pengguna->name) }}"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Andi Wijaya, S.Pd</small>
                        </div>

                        {{-- Username --}}
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   id="username"
                                   name="username"
                                   value="{{ old('username', $pengguna->username) }}"
                                   placeholder="Masukkan username untuk login"
                                   required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Username digunakan untuk login (tanpa spasi)</small>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $pengguna->email) }}"
                                   placeholder="contoh@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">
                                Nomor Telepon
                            </label>
                            <input type="text"
                                   class="form-control @error('no_telepon') is-invalid @enderror"
                                   id="no_telepon"
                                   name="no_telepon"
                                   value="{{ old('no_telepon', $pengguna->no_telepon) }}"
                                   placeholder="08xx xxxx xxxx">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bagian 2: Akun & Role --}}
                        <h6 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="bi bi-shield-lock"></i> Akun & Role
                        </h6>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label for="role" class="form-label">
                                Role/Peran <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role"
                                    name="role"
                                    required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $pengguna->role) == 'admin' ? 'selected' : '' }}>
                                    Admin (Administrator)
                                </option>
                                <option value="guru" {{ old('role', $pengguna->role) == 'guru' ? 'selected' : '' }}>
                                    Guru (Pengajar)
                                </option>
                                <option value="siswa" {{ old('role', $pengguna->role) == 'siswa' ? 'selected' : '' }}>
                                    Siswa (Pelajar)
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                                @if(!$pengguna->exists)
                                    <span class="text-danger">*</span>
                                @else
                                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                                @endif
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   {{ $pengguna->exists ? '' : 'required' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password
                                @if(!$pengguna->exists)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Ketik ulang password"
                                   {{ $pengguna->exists ? '' : 'required' }}>
                            <small class="text-muted">Harus sama dengan password di atas</small>
                        </div>

                        {{-- Bagian 3: Data Tambahan (Kondisional berdasarkan Role) --}}
                        <h6 class="border-bottom pb-2 mb-3 mt-4">
                            <i class="bi bi-info-circle"></i> Data Tambahan
                        </h6>

                        {{-- NIP/NIS --}}
                        <div class="mb-3" id="field-nip-nis">
                            <label for="nip_nis" class="form-label" id="label-nip-nis">
                                NIP/NIS
                            </label>
                            <input type="text"
                                   class="form-control @error('nip_nis') is-invalid @enderror"
                                   id="nip_nis"
                                   name="nip_nis"
                                   value="{{ old('nip_nis', $pengguna->nip_nis) }}"
                                   placeholder="Masukkan NIP (Guru) atau NIS (Siswa)">
                            @error('nip_nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="help-nip-nis">NIP untuk Guru, NIS untuk Siswa</small>
                        </div>

                        {{-- Kelas/Mata Pelajaran --}}
                        <div class="mb-3" id="field-kelas">
                            <label for="kelas" class="form-label" id="label-kelas">
                                Kelas/Mata Pelajaran
                            </label>
                            <input type="text"
                                   class="form-control @error('kelas') is-invalid @enderror"
                                   id="kelas"
                                   name="kelas"
                                   value="{{ old('kelas', $pengguna->kelas) }}"
                                   placeholder="Contoh: XII IPA 1 atau Matematika">
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="help-kelas">Kelas untuk Siswa, Mata Pelajaran untuk Guru</small>
                        </div>

                        {{-- Upload Foto Profil --}}
                        <div class="mb-3">
                            <label for="foto" class="form-label">
                                Foto Profil
                            </label>
                            <input type="file"
                                   class="form-control @error('foto') is-invalid @enderror"
                                   id="foto"
                                   name="foto"
                                   accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, JPEG (Maks. 2MB)</small>

                            {{-- Preview Foto Lama (Jika Edit) --}}
                            @if($pengguna->exists && $pengguna->foto)
                                <div class="mt-2">
                                    <p class="mb-1"><strong>Foto Saat Ini:</strong></p>
                                    <img src="{{ asset('storage/foto_profil/' . $pengguna->foto) }}"
                                         alt="Foto {{ $pengguna->name }}"
                                         class="rounded"
                                         style="max-width: 150px;">
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                {{ $pengguna->exists ? 'Update' : 'Simpan' }} Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar Kanan: Panduan --}}
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-primary"></i> Panduan Pengisian
                    </h6>
                    <ul class="small">
                        <li><strong>Nama Lengkap:</strong> Isi dengan nama lengkap beserta gelar (jika ada)</li>
                        <li><strong>Username:</strong> Digunakan untuk login, tidak boleh ada spasi</li>
                        <li><strong>Email:</strong> Harus email yang valid dan belum terdaftar</li>
                        <li><strong>Password:</strong> Minimal 6 karakter
                            @if($pengguna->exists)
                                <br><span class="text-danger">Kosongkan jika tidak ingin mengubah password</span>
                            @endif
                        </li>
                        <li><strong>Role:</strong>
                            <ul>
                                <li>Admin: Akses penuh sistem</li>
                                <li>Guru: Kelola materi dan nilai</li>
                                <li>Siswa: Akses materi dan tugas</li>
                            </ul>
                        </li>
                        <li><strong>NIP/NIS:</strong> Nomor Induk Pegawai (Guru) atau Nomor Induk Siswa</li>
                        <li><strong>Kelas:</strong> Kelas untuk siswa (contoh: XII IPA 1) atau Mata Pelajaran untuk guru (contoh: Matematika)</li>
                        <li><strong>Foto:</strong> Opsional, ukuran maksimal 2MB</li>
                    </ul>
                </div>
            </div>

            @if($pengguna->exists)
                <div class="card bg-warning bg-opacity-10 mt-3">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle"></i> Perhatian
                        </h6>
                        <p class="small mb-0">
                            Pastikan data yang diubah sudah benar sebelum menyimpan.
                            Perubahan data akan langsung diterapkan di sistem.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

{{-- JavaScript untuk Dynamic Form berdasarkan Role --}}
@push('js_tambahan')
<script>
    // Fungsi untuk mengubah label berdasarkan role yang dipilih
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const labelNipNis = document.getElementById('label-nip-nis');
        const helpNipNis = document.getElementById('help-nip-nis');
        const labelKelas = document.getElementById('label-kelas');
        const helpKelas = document.getElementById('help-kelas');
        const inputNipNis = document.getElementById('nip_nis');
        const inputKelas = document.getElementById('kelas');

        // Reset placeholder
        if (role === 'guru') {
            labelNipNis.innerHTML = 'NIP (Nomor Induk Pegawai)';
            helpNipNis.textContent = 'Nomor Induk Pegawai Guru';
            inputNipNis.placeholder = 'Masukkan NIP';

            labelKelas.innerHTML = 'Mata Pelajaran yang Diampu';
            helpKelas.textContent = 'Contoh: Matematika, Bahasa Indonesia';
            inputKelas.placeholder = 'Contoh: Matematika';
        } else if (role === 'siswa') {
            labelNipNis.innerHTML = 'NIS (Nomor Induk Siswa)';
            helpNipNis.textContent = 'Nomor Induk Siswa';
            inputNipNis.placeholder = 'Masukkan NIS';

            labelKelas.innerHTML = 'Kelas';
            helpKelas.textContent = 'Contoh: XII IPA 1, X MIPA 2';
            inputKelas.placeholder = 'Contoh: XII IPA 1';
        } else {
            // Admin tidak perlu NIP/NIS dan Kelas
            labelNipNis.innerHTML = 'NIP/NIS';
            helpNipNis.textContent = 'Tidak diperlukan untuk Admin';
            inputNipNis.placeholder = 'Kosongkan untuk Admin';

            labelKelas.innerHTML = 'Kelas/Mata Pelajaran';
            helpKelas.textContent = 'Tidak diperlukan untuk Admin';
            inputKelas.placeholder = 'Kosongkan untuk Admin';
        }
    });

    // Trigger saat halaman load (untuk mode edit)
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
