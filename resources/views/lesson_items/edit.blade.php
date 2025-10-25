{{--
    =====================================================
    HALAMAN EDIT LESSON ITEM
    =====================================================
    Halaman untuk mengedit item dalam lesson flow interaktif.
    Mendukung berbagai tipe: video, gambar, soal PG, soal gambar, isian

    Route: GET /lesson-items/{id}/edit
    Role: admin, guru
    =====================================================
--}}

@extends('layouts.template_dashboard')

@section('judul_halaman', 'Edit Lesson Item')

@section('styles')
<style>
    /* Custom styles untuk form edit */
    .form-section {
        background: #ffffff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: block;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
    }

    .preview-image {
        max-width: 300px;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .badge-type {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection

@section('isi_halaman')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route(Auth::user()->role . '.dashboard') }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('lesson-flow.index') }}">
                    <i class="bi bi-collection-play"></i> Lesson Flow
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('lesson-flow.edit', $lessonFlow->id) }}">
                    {{ $lessonFlow->judul_lesson }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Edit Item
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">
                <i class="bi bi-pencil-square text-primary"></i>
                Edit Lesson Item
            </h2>
            <p class="text-muted mb-0">
                Ubah konten item dalam lesson flow: <strong>{{ $lessonFlow->judul_lesson }}</strong>
            </p>
        </div>
        <a href="{{ route('lesson-flow.edit', $lessonFlow->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Edit --}}
    <form action="{{ route('lesson-items.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="formEditItem">
        @csrf
        @method('PUT')

        <div class="form-section">
            {{-- Tipe Item (Readonly/Disabled - tidak bisa diubah) --}}
            <div class="mb-4">
                <label class="form-label">Tipe Item</label>
                <div>
                    @php
                        $badgeClass = [
                            'video' => 'bg-danger',
                            'gambar' => 'bg-info',
                            'soal_pg' => 'bg-success',
                            'soal_gambar' => 'bg-warning',
                            'isian' => 'bg-primary'
                        ][$item->tipe_item] ?? 'bg-secondary';

                        $tipeLabel = [
                            'video' => '📹 Video YouTube',
                            'gambar' => '🖼️ Gambar',
                            'soal_pg' => '✅ Soal Pilihan Ganda',
                            'soal_gambar' => '🖼️ Soal Gambar',
                            'isian' => '📝 Soal Isian'
                        ][$item->tipe_item] ?? $item->tipe_item;
                    @endphp
                    <span class="badge badge-type {{ $badgeClass }}">{{ $tipeLabel }}</span>
                    <input type="hidden" name="tipe_item" value="{{ $item->tipe_item }}">
                </div>
                <small class="text-muted">Tipe item tidak dapat diubah setelah dibuat</small>
            </div>

            {{-- Form Video YouTube --}}
            @if($item->tipe_item === 'video')
                <div class="mb-4">
                    <label for="konten" class="form-label">
                        <i class="bi bi-youtube text-danger"></i> Link YouTube <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control @error('konten') is-invalid @enderror"
                           id="konten" name="konten" value="{{ old('konten', $item->konten) }}"
                           placeholder="https://www.youtube.com/watch?v=..." required>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Masukkan URL lengkap dari YouTube</small>
                </div>

            {{-- Form Gambar --}}
            @elseif($item->tipe_item === 'gambar')
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-image"></i> Gambar Saat Ini
                    </label>
                    @if($item->konten)
                        @if(filter_var($item->konten, FILTER_VALIDATE_URL))
                            <img src="{{ $item->konten }}" alt="Gambar" class="preview-image d-block">
                        @else
                            <img src="{{ asset('storage/lesson_gambar/' . $item->konten) }}" alt="Gambar" class="preview-image d-block">
                        @endif
                    @endif
                </div>

                <div class="mb-4">
                    <label for="konten" class="form-label">
                        <i class="bi bi-link-45deg"></i> URL Gambar Baru (Opsional)
                    </label>
                    <input type="url" class="form-control @error('konten') is-invalid @enderror"
                           id="konten" name="konten" value="{{ old('konten') }}"
                           placeholder="https://example.com/gambar.jpg">
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar_file" class="form-label">
                        <i class="bi bi-upload"></i> Atau Upload Gambar Baru (Opsional)
                    </label>
                    <input type="file" class="form-control @error('gambar_file') is-invalid @enderror"
                           id="gambar_file" name="gambar_file" accept="image/*">
                    @error('gambar_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 5MB</small>
                </div>

            {{-- Form Soal Pilihan Ganda --}}
            @elseif($item->tipe_item === 'soal_pg')
                <div class="mb-4">
                    <label for="konten" class="form-label">
                        <i class="bi bi-question-circle"></i> Pertanyaan <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('konten') is-invalid @enderror"
                              id="konten" name="konten" rows="4" required>{{ old('konten', $item->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="opsi_a" class="form-label">Opsi A <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('opsi_a') is-invalid @enderror"
                               id="opsi_a" name="opsi_a" value="{{ old('opsi_a', $item->opsi_a) }}" required>
                        @error('opsi_a')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="opsi_b" class="form-label">Opsi B <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('opsi_b') is-invalid @enderror"
                               id="opsi_b" name="opsi_b" value="{{ old('opsi_b', $item->opsi_b) }}" required>
                        @error('opsi_b')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="opsi_c" class="form-label">Opsi C <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('opsi_c') is-invalid @enderror"
                               id="opsi_c" name="opsi_c" value="{{ old('opsi_c', $item->opsi_c) }}" required>
                        @error('opsi_c')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="opsi_d" class="form-label">Opsi D <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('opsi_d') is-invalid @enderror"
                               id="opsi_d" name="opsi_d" value="{{ old('opsi_d', $item->opsi_d) }}" required>
                        @error('opsi_d')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="jawaban_benar" class="form-label">
                        <i class="bi bi-check-circle"></i> Jawaban Benar <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('jawaban_benar') is-invalid @enderror"
                            id="jawaban_benar" name="jawaban_benar" required>
                        <option value="">-- Pilih Jawaban Benar --</option>
                        <option value="a" {{ old('jawaban_benar', $item->jawaban_benar) === 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ old('jawaban_benar', $item->jawaban_benar) === 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ old('jawaban_benar', $item->jawaban_benar) === 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ old('jawaban_benar', $item->jawaban_benar) === 'd' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('jawaban_benar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="poin" class="form-label">
                            <i class="bi bi-star"></i> Poin
                        </label>
                        <input type="number" class="form-control @error('poin') is-invalid @enderror"
                               id="poin" name="poin" value="{{ old('poin', $item->poin ?? 10) }}" min="1">
                        @error('poin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="penjelasan" class="form-label">
                        <i class="bi bi-info-circle"></i> Penjelasan (Opsional)
                    </label>
                    <textarea class="form-control @error('penjelasan') is-invalid @enderror"
                              id="penjelasan" name="penjelasan" rows="3">{{ old('penjelasan', $item->penjelasan) }}</textarea>
                    @error('penjelasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Penjelasan akan ditampilkan setelah siswa menjawab</small>
                </div>

            {{-- Form Soal Gambar --}}
            @elseif($item->tipe_item === 'soal_gambar')
                <div class="mb-4">
                    <label for="konten" class="form-label">
                        <i class="bi bi-question-circle"></i> Pertanyaan <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('konten') is-invalid @enderror"
                              id="konten" name="konten" rows="4" required>{{ old('konten', $item->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    @foreach(['a', 'b', 'c', 'd'] as $opsi)
                        <div class="col-md-6 mb-3">
                            <label for="gambar_opsi_{{ $opsi }}" class="form-label">
                                Gambar Opsi {{ strtoupper($opsi) }}
                            </label>
                            @php $fieldName = 'gambar_opsi_' . $opsi; @endphp
                            @if($item->$fieldName)
                                <img src="{{ asset('storage/lesson_gambar/' . $item->$fieldName) }}"
                                     alt="Opsi {{ strtoupper($opsi) }}" class="preview-image d-block mb-2">
                            @endif
                            <input type="file" class="form-control @error('gambar_opsi_'.$opsi) is-invalid @enderror"
                                   id="gambar_opsi_{{ $opsi }}" name="gambar_opsi_{{ $opsi }}" accept="image/*">
                            @error('gambar_opsi_'.$opsi)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload gambar baru jika ingin mengganti</small>
                        </div>
                    @endforeach
                </div>

                <div class="mb-4">
                    <label for="jawaban_benar" class="form-label">
                        <i class="bi bi-check-circle"></i> Jawaban Benar <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('jawaban_benar') is-invalid @enderror"
                            id="jawaban_benar" name="jawaban_benar" required>
                        <option value="">-- Pilih Jawaban Benar --</option>
                        <option value="a" {{ old('jawaban_benar', $item->jawaban_benar) === 'a' ? 'selected' : '' }}>A</option>
                        <option value="b" {{ old('jawaban_benar', $item->jawaban_benar) === 'b' ? 'selected' : '' }}>B</option>
                        <option value="c" {{ old('jawaban_benar', $item->jawaban_benar) === 'c' ? 'selected' : '' }}>C</option>
                        <option value="d" {{ old('jawaban_benar', $item->jawaban_benar) === 'd' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('jawaban_benar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="poin" class="form-label">
                            <i class="bi bi-star"></i> Poin
                        </label>
                        <input type="number" class="form-control @error('poin') is-invalid @enderror"
                               id="poin" name="poin" value="{{ old('poin', $item->poin ?? 10) }}" min="1">
                        @error('poin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="penjelasan" class="form-label">
                        <i class="bi bi-info-circle"></i> Penjelasan (Opsional)
                    </label>
                    <textarea class="form-control @error('penjelasan') is-invalid @enderror"
                              id="penjelasan" name="penjelasan" rows="3">{{ old('penjelasan', $item->penjelasan) }}</textarea>
                    @error('penjelasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            {{-- Form Soal Isian --}}
            @elseif($item->tipe_item === 'isian')
                <div class="mb-4">
                    <label for="konten" class="form-label">
                        <i class="bi bi-question-circle"></i> Pertanyaan <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('konten') is-invalid @enderror"
                              id="konten" name="konten" rows="4" required>{{ old('konten', $item->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jawaban_benar" class="form-label">
                        <i class="bi bi-check-circle"></i> Jawaban Benar <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('jawaban_benar') is-invalid @enderror"
                           id="jawaban_benar" name="jawaban_benar" value="{{ old('jawaban_benar', $item->jawaban_benar) }}" required>
                    @error('jawaban_benar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Jawaban tidak case-sensitive</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="poin" class="form-label">
                            <i class="bi bi-star"></i> Poin
                        </label>
                        <input type="number" class="form-control @error('poin') is-invalid @enderror"
                               id="poin" name="poin" value="{{ old('poin', $item->poin ?? 10) }}" min="1">
                        @error('poin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="penjelasan" class="form-label">
                        <i class="bi bi-info-circle"></i> Penjelasan (Opsional)
                    </label>
                    <textarea class="form-control @error('penjelasan') is-invalid @enderror"
                              id="penjelasan" name="penjelasan" rows="3">{{ old('penjelasan', $item->penjelasan) }}</textarea>
                    @error('penjelasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2 justify-content-end mb-5">
            <a href="{{ route('lesson-flow.edit', $lessonFlow->id) }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
    // Preview gambar sebelum upload
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Cari preview image terdekat
                    let preview = input.parentElement.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image d-block';
                        input.parentElement.insertBefore(preview, input);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
