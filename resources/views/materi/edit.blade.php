{{--
    View: Form Edit Materi Pembelajaran

    Halaman ini digunakan untuk mengedit materi pembelajaran yang sudah ada.
    Hanya dapat diakses oleh Admin dan Guru.

    Fitur:
    - Form edit dengan data yang sudah ada
    - Upload file baru (opsional, menggantikan file lama)
    - Validasi form
    - Menampilkan file yang sudah ada

    @created 2025-10-15
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Edit Materi Pembelajaran')

@section('isi_halaman')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                {{-- Header Card --}}
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Materi Pembelajaran
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Tombol Kembali --}}
                    <div class="mb-3">
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Materi
                        </a>
                    </div>

                    {{-- Form Edit Materi --}}
                    <form action="{{ route('materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data" id="formEditMateri">
                        @csrf
                        @method('PUT')

                        {{-- Input Judul Materi --}}
                        <div class="mb-3">
                            <label for="judul_materi" class="form-label">
                                Judul Materi <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('judul_materi') is-invalid @enderror"
                                   id="judul_materi"
                                   name="judul_materi"
                                   value="{{ old('judul_materi', $materi->judul_materi) }}"
                                   placeholder="Contoh: Adab Ketika Makan dan Minum"
                                   required>
                            @error('judul_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Kategori --}}
                        <div class="mb-3">
                            <label for="kategori" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('kategori') is-invalid @enderror"
                                    id="kategori"
                                    name="kategori"
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Adab Berjalan" {{ old('kategori', $materi->kategori) == 'Adab Berjalan' ? 'selected' : '' }}>Adab Berjalan</option>
                                <option value="Adab Berpakaian" {{ old('kategori', $materi->kategori) == 'Adab Berpakaian' ? 'selected' : '' }}>Adab Berpakaian</option>
                                <option value="Adab Makan dan Minum" {{ old('kategori', $materi->kategori) == 'Adab Makan dan Minum' ? 'selected' : '' }}>Adab Makan dan Minum</option>
                                <option value="Adab Bermedia Sosial" {{ old('kategori', $materi->kategori) == 'Adab Bermedia Sosial' ? 'selected' : '' }}>Adab Bermedia Sosial</option>
                            </select>
                            @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi Singkat <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi"
                                      name="deskripsi"
                                      rows="3"
                                      placeholder="Masukkan deskripsi singkat tentang materi ini..."
                                      required>{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Isi Materi --}}
                        <div class="mb-3">
                            <label for="isi_materi" class="form-label">
                                Isi Materi Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('isi_materi') is-invalid @enderror"
                                      id="isi_materi"
                                      name="isi_materi"
                                      rows="10"
                                      placeholder="Tulis isi materi lengkap di sini..."
                                      required>{{ old('isi_materi', $materi->isi_materi) }}</textarea>
                            @error('isi_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- File yang sudah ada --}}
                        @if($materi->file_materi)
                        <div class="mb-3">
                            <label class="form-label">File Saat Ini</label>
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-check me-2"></i>
                                    <strong>{{ $materi->file_materi }}</strong>
                                </div>
                                <a href="{{ $materi->url_file }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>Lihat File
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Input File Materi Baru --}}
                        <div class="mb-3">
                            <label for="file_materi" class="form-label">
                                @if($materi->file_materi)
                                    Ganti File Pendukung (Opsional)
                                @else
                                    File Pendukung (Opsional)
                                @endif
                            </label>
                            <input type="file"
                                   class="form-control @error('file_materi') is-invalid @enderror"
                                   id="file_materi"
                                   name="file_materi"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png">
                            @error('file_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                @if($materi->file_materi)
                                    Biarkan kosong jika tidak ingin mengganti file. Format: PDF, DOC, DOCX, <strong class="text-danger">PPT, PPTX</strong>, JPG, JPEG, PNG (Maksimal 5MB)
                                @else
                                    Format yang didukung: PDF, DOC, DOCX, <strong class="text-danger">PPT, PPTX</strong>, JPG, JPEG, PNG (Maksimal 5MB)
                                @endif
                            </small>

                            {{-- Preview nama file baru yang dipilih --}}
                            <div id="filePreview" class="mt-2 d-none">
                                <div class="alert alert-warning">
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i>
                                    File baru dipilih: <strong id="fileName"></strong>
                                    @if($materi->file_materi)
                                        <br><small class="text-muted">File lama akan digantikan dengan file ini</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Divider ATAU --}}
                        <div class="text-center my-3">
                            <span class="badge bg-secondary py-2 px-4">ATAU</span>
                        </div>

                        {{-- Link Embed yang Sudah Ada --}}
                        @if($materi->link_embed)
                        <div class="mb-3">
                            <label class="form-label">Link Embed Saat Ini</label>
                            <div class="alert alert-success d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-globe me-2"></i>
                                    <strong>{{ Str::limit($materi->link_embed, 60) }}</strong>
                                </div>
                                <a href="{{ $materi->link_embed }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Link
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Input Link Embed (Google Slides / OneDrive) --}}
                        <div class="mb-4">
                            <label for="link_embed" class="form-label">
                                <i class="bi bi-globe text-info me-1"></i>
                                @if($materi->link_embed)
                                    Ganti Link Embed Materi Interaktif (Opsional)
                                @else
                                    Link Embed Materi Interaktif (Opsional)
                                @endif
                            </label>
                            <input type="url"
                                   class="form-control @error('link_embed') is-invalid @enderror"
                                   id="link_embed"
                                   name="link_embed"
                                   value="{{ old('link_embed', $materi->link_embed ?? '') }}"
                                   placeholder="https://docs.google.com/presentation/d/e/...">
                            @error('link_embed')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Paste link embed dari Google Slides atau OneDrive.</strong>
                                <br>
                                <span class="text-primary">Jika diisi, materi akan ditampilkan secara interaktif di halaman siswa.</span>
                                @if($materi->link_embed)
                                    <br><span class="text-warning">Kosongkan field ini jika ingin menghapus link embed.</span>
                                @endif
                            </small>

                            {{-- Accordion: Cara Mendapatkan Link Embed --}}
                            <div class="accordion mt-2" id="accordionEmbed">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmbed">
                                            <i class="bi bi-question-circle me-2"></i>
                                            <small>Cara mendapatkan Link Embed</small>
                                        </button>
                                    </h2>
                                    <div id="collapseEmbed" class="accordion-collapse collapse" data-bs-parent="#accordionEmbed">
                                        <div class="accordion-body small">
                                            <p class="mb-2"><strong>📊 Untuk Google Slides:</strong></p>
                                            <ol class="mb-3">
                                                <li>Buka file Google Slides</li>
                                                <li>Klik <strong>File → Share → Publish to web</strong></li>
                                                <li>Pilih tab <strong>"Embed"</strong></li>
                                                <li>Copy URL dari kode &lt;iframe src="..."&gt;</li>
                                                <li>Paste ke field di atas</li>
                                            </ol>

                                            <p class="mb-2"><strong>📝 Contoh Link Valid:</strong></p>
                                            <code class="d-block bg-light p-2 rounded">
                                                https://docs.google.com/presentation/d/e/2PACX-1vT.../embed?start=false
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Pembuat --}}
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-light border">
                                        <i class="bi bi-person me-2"></i>
                                        <strong>Dibuat oleh:</strong> {{ $materi->dibuat_oleh }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-light border">
                                        <i class="bi bi-calendar me-2"></i>
                                        <strong>Tanggal Upload:</strong> {{ $materi->tanggal_upload->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i>Update Materi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Custom CSS --}}
@push('styles')
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    textarea {
        resize: vertical;
    }
</style>
@endpush

{{-- Custom JavaScript --}}
@push('scripts')
<script>
    // Preview nama file baru yang dipilih
    document.getElementById('file_materi').addEventListener('change', function(e) {
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');

        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            filePreview.classList.remove('d-none');
        } else {
            filePreview.classList.add('d-none');
        }
    });

    // Konfirmasi sebelum submit
    document.getElementById('formEditMateri').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan materi ini?')) {
            e.preventDefault();
        }
    });
</script>
@endpush
