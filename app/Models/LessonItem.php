<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =====================================================
 * MODEL LESSON ITEM
 * =====================================================
 * Model Eloquent untuk tabel lesson_item
 *
 * Model ini mewakili item-item dalam alur pembelajaran:
 * - Video YouTube
 * - Gambar ilustrasi
 * - Soal Pilihan Ganda
 * - Soal dengan Gambar
 * - Soal Isian Singkat
 *
 * Relasi:
 * - belongsTo LessonFlow (parent)
 * - hasMany LessonJawaban (jawaban siswa)
 *
 * @package App\Models
 * @author System
 * @created 2025-10-15
 * =====================================================
 */
class LessonItem extends Model
{
    /**
     * Nama tabel di database
     *
     * Override nama tabel karena Laravel default menggunakan nama jamak
     *
     * @var string
     */
    protected $table = 'lesson_item';

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_lesson_flow',    // Foreign key ke lesson_flow
        'tipe_item',         // video, gambar, soal_pg, soal_gambar, isian
        'konten',            // URL video/gambar atau teks soal
        'opsi_a',            // Opsi A untuk soal PG
        'opsi_b',            // Opsi B untuk soal PG
        'opsi_c',            // Opsi C untuk soal PG
        'opsi_d',            // Opsi D untuk soal PG
        'gambar_opsi_a',     // Path gambar opsi A
        'gambar_opsi_b',     // Path gambar opsi B
        'gambar_opsi_c',     // Path gambar opsi C
        'gambar_opsi_d',     // Path gambar opsi D
        'jawaban_benar',     // Jawaban benar
        'poin',              // Poin untuk soal
        'waktu_per_soal',    // Durasi waktu per soal dalam detik (default 30)
        'penjelasan',        // Penjelasan jawaban
        'urutan',            // Urutan tampilan
    ];

    /**
     * Casting tipe data
     *
     * @var array<string, string>
     */
    protected $casts = [
        'poin' => 'integer',
        'waktu_per_soal' => 'integer',
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * =====================================================
     * RELASI ELOQUENT
     * =====================================================
     */

    /**
     * Relasi ke LessonFlow (Parent)
     *
     * Tipe: belongsTo (Many-to-One)
     * Banyak lesson_item → Satu lesson_flow
     *
     * @return BelongsTo
     */
    public function lessonFlow(): BelongsTo
    {
        return $this->belongsTo(LessonFlow::class, 'id_lesson_flow', 'id');
    }

    /**
     * Relasi ke LessonJawaban
     *
     * Tipe: hasMany (One-to-Many)
     * Satu lesson_item → Banyak jawaban (dari siswa berbeda)
     *
     * @return HasMany
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(LessonJawaban::class, 'id_lesson_item', 'id');
    }

    /**
     * =====================================================
     * ACCESSOR
     * =====================================================
     */

    /**
     * Accessor: Cek apakah item ini adalah soal
     *
     * @return bool
     */
    public function getIsSoalAttribute(): bool
    {
        return in_array($this->tipe_item, ['soal_pg', 'soal_gambar', 'isian']);
    }

    /**
     * Accessor: Cek apakah item ini adalah video
     *
     * @return bool
     */
    public function getIsVideoAttribute(): bool
    {
        return $this->tipe_item === 'video';
    }

    /**
     * Accessor: Cek apakah item ini adalah gambar
     *
     * @return bool
     */
    public function getIsGambarAttribute(): bool
    {
        return $this->tipe_item === 'gambar';
    }

    /**
     * Accessor: Get label tipe item
     *
     * @return string
     */
    public function getTipeLabelAttribute(): string
    {
        return match($this->tipe_item) {
            'video' => 'Video YouTube',
            'gambar' => 'Gambar Ilustrasi',
            'soal_pg' => 'Soal Pilihan Ganda',
            'soal_gambar' => 'Soal dengan Gambar',
            'isian' => 'Soal Isian Singkat',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Accessor: Extract YouTube video ID dari URL
     *
     * Contoh:
     * - https://www.youtube.com/watch?v=xxxxx → xxxxx
     * - https://youtu.be/xxxxx → xxxxx
     *
     * @return string|null
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->tipe_item !== 'video') {
            return null;
        }

        $url = $this->konten;

        // Pattern untuk berbagai format URL YouTube
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Accessor: Get embed URL untuk YouTube
     *
     * @return string|null
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $videoId = $this->youtube_id;

        if (!$videoId) {
            return null;
        }

        return "https://www.youtube.com/embed/{$videoId}";
    }

    /**
     * Accessor: Get URL gambar konten (untuk tipe gambar)
     *
     * @return string|null
     */
    public function getGambarUrlAttribute(): ?string
    {
        if ($this->tipe_item !== 'gambar') {
            return null;
        }

        // Jika sudah URL lengkap, return langsung
        if (filter_var($this->konten, FILTER_VALIDATE_URL)) {
            return $this->konten;
        }

        // Jika path file, buat URL ke storage
        return asset('storage/lesson_gambar/' . $this->konten);
    }

    /**
     * =====================================================
     * QUERY SCOPES
     * =====================================================
     */

    /**
     * Scope: Filter berdasarkan tipe item
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tipe
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipe($query, string $tipe)
    {
        return $query->where('tipe_item', $tipe);
    }

    /**
     * Scope: Hanya ambil soal (bukan video/gambar)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSoalSaja($query)
    {
        return $query->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian']);
    }

    /**
     * Scope: Hanya ambil konten (video/gambar, bukan soal)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKontenSaja($query)
    {
        return $query->whereIn('tipe_item', ['video', 'gambar']);
    }

    /**
     * =====================================================
     * HELPER METHODS
     * =====================================================
     */

    /**
     * Validasi jawaban siswa
     *
     * @param string $jawabanSiswa Jawaban yang diberikan siswa
     * @return bool TRUE jika benar, FALSE jika salah
     */
    public function cekJawaban(string $jawabanSiswa): bool
    {
        // Untuk soal PG dan soal gambar, cek exact match (case insensitive)
        if (in_array($this->tipe_item, ['soal_pg', 'soal_gambar'])) {
            return strtolower(trim($jawabanSiswa)) === strtolower(trim($this->jawaban_benar));
        }

        // Untuk isian, cek case insensitive dan trim whitespace
        if ($this->tipe_item === 'isian') {
            return strtolower(trim($jawabanSiswa)) === strtolower(trim($this->jawaban_benar));
        }

        return false;
    }

    /**
     * Get array opsi jawaban (untuk soal PG)
     *
     * @return array<string, string> ['a' => 'Opsi A', 'b' => 'Opsi B', ...]
     */
    public function getOpsiArray(): array
    {
        if (!in_array($this->tipe_item, ['soal_pg', 'soal_gambar'])) {
            return [];
        }

        $opsi = [];

        if ($this->opsi_a) $opsi['a'] = $this->opsi_a;
        if ($this->opsi_b) $opsi['b'] = $this->opsi_b;
        if ($this->opsi_c) $opsi['c'] = $this->opsi_c;
        if ($this->opsi_d) $opsi['d'] = $this->opsi_d;

        return $opsi;
    }

    /**
     * Get array gambar opsi (untuk soal gambar)
     *
     * @return array<string, string> ['a' => 'path/image.jpg', ...]
     */
    public function getGambarOpsiArray(): array
    {
        if ($this->tipe_item !== 'soal_gambar') {
            return [];
        }

        $gambar = [];

        if ($this->gambar_opsi_a) $gambar['a'] = asset('storage/lesson_gambar/' . $this->gambar_opsi_a);
        if ($this->gambar_opsi_b) $gambar['b'] = asset('storage/lesson_gambar/' . $this->gambar_opsi_b);
        if ($this->gambar_opsi_c) $gambar['c'] = asset('storage/lesson_gambar/' . $this->gambar_opsi_c);
        if ($this->gambar_opsi_d) $gambar['d'] = asset('storage/lesson_gambar/' . $this->gambar_opsi_d);

        return $gambar;
    }
}
