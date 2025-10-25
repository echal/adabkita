<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * =====================================================
 * MODEL LESSON JAWABAN
 * =====================================================
 * Model Eloquent untuk tabel lesson_jawaban
 *
 * Model ini menyimpan jawaban siswa untuk setiap lesson item.
 * Digunakan untuk tracking progress dan scoring siswa.
 *
 * Relasi:
 * - belongsTo LessonItem (soal yang dijawab)
 * - belongsTo User (siswa yang menjawab)
 *
 * @package App\Models
 * @author System
 * @created 2025-10-15
 * =====================================================
 */
class LessonJawaban extends Model
{
    /**
     * Nama tabel di database
     *
     * @var string
     */
    protected $table = 'lesson_jawaban';

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_lesson_item',    // Foreign key ke lesson_item
        'id_siswa',          // Foreign key ke users (siswa)
        'jawaban_siswa',     // Jawaban yang diberikan siswa
        'benar_salah',       // Status benar (TRUE) atau salah (FALSE)
        'poin_didapat',      // Poin yang didapat siswa
        'percobaan_ke',      // Percobaan keberapa (untuk retry)
        'waktu_mulai',       // Waktu mulai menjawab
        'waktu_selesai',     // Waktu selesai menjawab
    ];

    /**
     * Casting tipe data
     *
     * @var array<string, string>
     */
    protected $casts = [
        'benar_salah' => 'boolean',
        'poin_didapat' => 'integer',
        'percobaan_ke' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * =====================================================
     * RELASI ELOQUENT
     * =====================================================
     */

    /**
     * Relasi ke LessonItem (Soal yang dijawab)
     *
     * Tipe: belongsTo (Many-to-One)
     * Banyak jawaban → Satu lesson_item
     *
     * @return BelongsTo
     */
    public function lessonItem(): BelongsTo
    {
        return $this->belongsTo(LessonItem::class, 'id_lesson_item', 'id');
    }

    /**
     * Relasi ke User (Siswa yang menjawab)
     *
     * Tipe: belongsTo (Many-to-One)
     * Banyak jawaban → Satu user (siswa)
     *
     * @return BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_siswa', 'id');
    }

    /**
     * =====================================================
     * ACCESSOR
     * =====================================================
     */

    /**
     * Accessor: Get label status jawaban
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->benar_salah ? 'Benar' : 'Salah';
    }

    /**
     * Accessor: Get badge class untuk status
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->benar_salah ? 'success' : 'danger';
    }

    /**
     * Accessor: Get icon untuk status
     *
     * @return string
     */
    public function getStatusIconAttribute(): string
    {
        return $this->benar_salah ? 'check-circle' : 'x-circle';
    }

    /**
     * Accessor: Hitung durasi menjawab (dalam detik)
     *
     * @return int|null
     */
    public function getDurasiAttribute(): ?int
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return null;
        }

        return $this->waktu_selesai->diffInSeconds($this->waktu_mulai);
    }

    /**
     * Accessor: Durasi dalam format human-readable
     *
     * @return string|null
     */
    public function getDurasiFormatAttribute(): ?string
    {
        $durasi = $this->durasi;

        if (!$durasi) {
            return null;
        }

        if ($durasi < 60) {
            return $durasi . ' detik';
        }

        $menit = floor($durasi / 60);
        $detik = $durasi % 60;

        return $menit . ' menit ' . $detik . ' detik';
    }

    /**
     * =====================================================
     * QUERY SCOPES
     * =====================================================
     */

    /**
     * Scope: Filter jawaban berdasarkan siswa
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $siswaId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSiswa($query, int $siswaId)
    {
        return $query->where('id_siswa', $siswaId);
    }

    /**
     * Scope: Filter jawaban berdasarkan lesson item
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLessonItem($query, int $itemId)
    {
        return $query->where('id_lesson_item', $itemId);
    }

    /**
     * Scope: Hanya jawaban yang benar
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBenar($query)
    {
        return $query->where('benar_salah', true);
    }

    /**
     * Scope: Hanya jawaban yang salah
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSalah($query)
    {
        return $query->where('benar_salah', false);
    }

    /**
     * Scope: Hanya percobaan pertama
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePercobaanPertama($query)
    {
        return $query->where('percobaan_ke', 1);
    }

    /**
     * =====================================================
     * STATIC METHODS
     * =====================================================
     */

    /**
     * Hitung total poin siswa untuk lesson flow tertentu
     *
     * @param int $siswaId ID siswa
     * @param int $lessonFlowId ID lesson flow
     * @return int Total poin
     */
    public static function hitungTotalPoin(int $siswaId, int $lessonFlowId): int
    {
        return static::query()
            ->siswa($siswaId)
            ->whereHas('lessonItem', function($q) use ($lessonFlowId) {
                $q->where('id_lesson_flow', $lessonFlowId);
            })
            ->sum('poin_didapat');
    }

    /**
     * Hitung jumlah jawaban benar siswa untuk lesson flow tertentu
     *
     * @param int $siswaId ID siswa
     * @param int $lessonFlowId ID lesson flow
     * @return int Jumlah jawaban benar
     */
    public static function hitungJumlahBenar(int $siswaId, int $lessonFlowId): int
    {
        return static::query()
            ->siswa($siswaId)
            ->benar()
            ->whereHas('lessonItem', function($q) use ($lessonFlowId) {
                $q->where('id_lesson_flow', $lessonFlowId);
            })
            ->count();
    }

    /**
     * Hitung jumlah jawaban salah siswa untuk lesson flow tertentu
     *
     * @param int $siswaId ID siswa
     * @param int $lessonFlowId ID lesson flow
     * @return int Jumlah jawaban salah
     */
    public static function hitungJumlahSalah(int $siswaId, int $lessonFlowId): int
    {
        return static::query()
            ->siswa($siswaId)
            ->salah()
            ->whereHas('lessonItem', function($q) use ($lessonFlowId) {
                $q->where('id_lesson_flow', $lessonFlowId);
            })
            ->count();
    }

    /**
     * Hitung persentase kebenaran siswa untuk lesson flow tertentu
     *
     * @param int $siswaId ID siswa
     * @param int $lessonFlowId ID lesson flow
     * @return float Persentase (0-100)
     */
    public static function hitungPersentase(int $siswaId, int $lessonFlowId): float
    {
        $totalJawaban = static::query()
            ->siswa($siswaId)
            ->whereHas('lessonItem', function($q) use ($lessonFlowId) {
                $q->where('id_lesson_flow', $lessonFlowId);
            })
            ->count();

        if ($totalJawaban === 0) {
            return 0;
        }

        $jawabanBenar = static::hitungJumlahBenar($siswaId, $lessonFlowId);

        return round(($jawabanBenar / $totalJawaban) * 100, 2);
    }

    /**
     * Cek apakah siswa sudah menjawab item tertentu
     *
     * @param int $siswaId ID siswa
     * @param int $itemId ID lesson item
     * @return bool
     */
    public static function sudahDijawab(int $siswaId, int $itemId): bool
    {
        return static::query()
            ->siswa($siswaId)
            ->lessonItem($itemId)
            ->exists();
    }

    /**
     * Ambil jawaban siswa untuk item tertentu (percobaan terakhir)
     *
     * @param int $siswaId ID siswa
     * @param int $itemId ID lesson item
     * @return self|null
     */
    public static function ambilJawabanTerakhir(int $siswaId, int $itemId): ?self
    {
        return static::query()
            ->siswa($siswaId)
            ->lessonItem($itemId)
            ->orderBy('percobaan_ke', 'desc')
            ->first();
    }
}
