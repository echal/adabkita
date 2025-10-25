{{--
    View: Form Tambah Materi Pembelajaran

    Halaman ini digunakan untuk menambah materi pembelajaran baru.
    Hanya dapat diakses oleh Admin dan Guru.

    Fitur:
    - Form input lengkap untuk materi
    - Upload file pendukung (PDF, DOC, gambar)
    - Validasi form
    - Preview file sebelum upload

    @created 2025-10-15
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Tambah Materi Pembelajaran')

@section('isi_halaman')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                {{-- Header Card --}}
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Materi Pembelajaran Baru
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Tombol Kembali --}}
                    <div class="mb-3">
                        <a href="{{ route('materi.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Materi
                        </a>
                    </div>

                    {{-- Form Tambah Materi --}}
                    <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data" id="formMateri">
                        @csrf

                        {{-- Input Judul Materi --}}
                        <div class="mb-3">
                            <label for="judul_materi" class="form-label">
                                Judul Materi <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('judul_materi') is-invalid @enderror"
                                   id="judul_materi"
                                   name="judul_materi"
                                   value="{{ old('judul_materi') }}"
                                   placeholder="Contoh: Adab Ketika Makan dan Minum"
                                   required>
                            @error('judul_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Masukkan judul materi yang jelas dan mudah dipahami
                            </small>
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
                                <option value="Adab Berjalan" {{ old('kategori') == 'Adab Berjalan' ? 'selected' : '' }}>Adab Berjalan</option>
                                <option value="Adab Berpakaian" {{ old('kategori') == 'Adab Berpakaian' ? 'selected' : '' }}>Adab Berpakaian</option>
                                <option value="Adab Makan dan Minum" {{ old('kategori') == 'Adab Makan dan Minum' ? 'selected' : '' }}>Adab Makan dan Minum</option>
                                <option value="Adab Bermedia Sosial" {{ old('kategori') == 'Adab Bermedia Sosial' ? 'selected' : '' }}>Adab Bermedia Sosial</option>
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
                                      required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Berikan gambaran umum tentang materi yang akan diajarkan (maksimal 200 kata)
                            </small>
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
                                      required>{{ old('isi_materi') }}</textarea>
                            @error('isi_materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Masukkan konten materi pembelajaran secara lengkap dan detail
                            </small>
                        </div>

                        {{-- Input File Materi --}}
                        <div class="mb-3">
                            <label for="file_materi" class="form-label">
                                File Pendukung (Opsional)
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
                                Format yang didukung: PDF, DOC, DOCX, <strong class="text-danger">PPT, PPTX</strong>, JPG, JPEG, PNG (Maksimal 5MB)
                            </small>

                            {{-- Preview nama file yang dipilih --}}
                            <div id="filePreview" class="mt-2 d-none">
                                <div class="alert alert-info">
                                    <i class="bi bi-file-earmark-check me-2"></i>
                                    File dipilih: <strong id="fileName"></strong>
                                </div>
                            </div>
                        </div>

                        {{-- Divider ATAU --}}
                        <div class="text-center my-3">
                            <span class="badge bg-secondary py-2 px-4">ATAU</span>
                        </div>

                        {{-- Input Link Embed (Google Slides / OneDrive) --}}
                        <div class="mb-4">
                            <label for="link_embed" class="form-label">
                                <i class="bi bi-globe text-info me-1"></i>
                                Link Embed Materi Interaktif (Opsional)
                            </label>
                            <input type="url"
                                   class="form-control @error('link_embed') is-invalid @enderror"
                                   id="link_embed"
                                   name="link_embed"
                                   value="{{ old('link_embed') }}"
                                   placeholder="https://docs.google.com/presentation/d/e/...">
                            @error('link_embed')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Paste link embed dari Google Slides atau OneDrive.</strong>
                                <br>
                                <span class="text-primary">Jika diisi, materi akan ditampilkan secara interaktif di halaman siswa.</span>
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

                        {{-- Info Pembuat (otomatis dari user login) --}}
                        <div class="mb-4">
                            <div class="alert alert-light border">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Dibuat oleh:</strong> {{ Auth::user()->name }}
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('materi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i>Simpan Materi
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
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    textarea {
        resize: vertical;
    }
</style>
@endpush

{{-- Custom JavaScript --}}
@push('scripts')
<script>
    // Preview nama file yang dipilih
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
    document.getElementById('formMateri').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul_materi').value;

        if (!confirm(`Apakah Anda yakin ingin menyimpan materi "${judul}"?`)) {
            e.preventDefault();
        }
    });
</script>
@endpush
