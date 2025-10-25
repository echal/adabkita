<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =====================================================
 * MODEL LESSON FLOW
 * =====================================================
 * Model Eloquent untuk tabel lesson_flow
 *
 * Model ini mewakili alur pembelajaran interaktif yang
 * dibuat oleh guru. Setiap lesson flow berisi beberapa
 * lesson item (video, gambar, soal).
 *
 * Relasi:
 * - belongsTo User (pembuat/guru)
 * - hasMany LessonItem (item-item pembelajaran)
 *
 * @package App\Models
 * @author System
 * @created 2025-10-15
 * =====================================================
 */
class LessonFlow extends Model
{
    /**
     * Nama tabel di database
     *
     * Secara default Laravel menggunakan nama jamak (lesson_flows)
     * tapi kita override menjadi lesson_flow (sesuai migration)
     *
     * @var string
     */
    protected $table = 'lesson_flow';

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     *
     * Kolom-kolom ini aman untuk diisi menggunakan:
     * - LessonFlow::create($data)
     * - $lessonFlow->fill($data)
     * - $lessonFlow->update($data)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul_materi',      // Judul alur pembelajaran
        'deskripsi',         // Deskripsi alur pembelajaran
        'dibuat_oleh',       // ID guru pembuat (foreign key ke users)
        'status',            // Status: draft, published, archived
        'tanggal_mulai',     // Tanggal mulai pembelajaran
        'tanggal_selesai',   // Tanggal selesai pembelajaran
        'durasi_menit',      // Durasi maksimal pengerjaan (0 = unlimited)
    ];

    /**
     * Casting tipe data untuk attribute
     *
     * Mengkonversi tipe data dari database ke tipe data PHP:
     * - tanggal_mulai & tanggal_selesai → Carbon (datetime object)
     * - Memudahkan manipulasi tanggal
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',      // Cast ke Carbon\Carbon
        'tanggal_selesai' => 'date',    // Cast ke Carbon\Carbon
        'created_at' => 'datetime',     // Cast ke Carbon\Carbon
        'updated_at' => 'datetime',     // Cast ke Carbon\Carbon
    ];

    /**
     * =====================================================
     * RELASI ELOQUENT
     * =====================================================
     */

    /**
     * Relasi ke User (Guru pembuat)
     *
     * Tipe relasi: belongsTo (Many-to-One)
     * - Banyak lesson_flow → Satu user (guru)
     * - Foreign key: dibuat_oleh
     * - Owner key: id (di tabel users)
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * $guru = $lessonFlow->guru; // Ambil data guru pembuat
     * echo $guru->name; // Nama guru
     * ```
     *
     * @return BelongsTo
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id');
    }

    /**
     * Relasi ke LessonItem (Item-item pembelajaran)
     *
     * Tipe relasi: hasMany (One-to-Many)
     * - Satu lesson_flow → Banyak lesson_item
     * - Foreign key: id_lesson_flow (di tabel lesson_item)
     * - Owner key: id (di tabel lesson_flow)
     *
     * Otomatis diurutkan berdasarkan kolom 'urutan' (ascending)
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * $items = $lessonFlow->items; // Ambil semua item dalam flow ini
     * foreach ($items as $item) {
     *     echo $item->konten;
     * }
     * ```
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(LessonItem::class, 'id_lesson_flow', 'id')
                    ->orderBy('urutan', 'asc'); // Urutkan berdasarkan urutan
    }

    /**
     * =====================================================
     * ACCESSOR & MUTATOR
     * =====================================================
     */

    /**
     * Accessor: Get status dalam format yang lebih ramah
     *
     * Mengkonversi status dari database ke format yang lebih mudah dibaca
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * echo $lessonFlow->status_label; // "Draft" atau "Dipublikasi" atau "Diarsipkan"
     * ```
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Dipublikasi',
            'archived' => 'Diarsipkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Accessor: Cek apakah lesson flow sudah dipublikasi
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * if ($lessonFlow->is_published) {
     *     echo "Sudah dipublikasi";
     * }
     * ```
     *
     * @return bool
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Accessor: Hitung total poin maksimal dari semua soal
     *
     * Menghitung total poin yang bisa didapat siswa jika menjawab
     * semua soal dengan benar
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * echo "Total poin: " . $lessonFlow->total_poin; // Contoh: 100
     * ```
     *
     * @return int
     */
    public function getTotalPoinAttribute(): int
    {
        // Sum poin dari semua item yang merupakan soal (bukan video/gambar)
        return $this->items()
                    ->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian'])
                    ->sum('poin');
    }

    /**
     * Accessor: Hitung jumlah item dalam flow
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * echo "Jumlah item: " . $lessonFlow->jumlah_item; // Contoh: 15
     * ```
     *
     * @return int
     */
    public function getJumlahItemAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * =====================================================
     * QUERY SCOPES
     * =====================================================
     */

    /**
     * Scope: Filter lesson flow berdasarkan status
     *
     * Cara menggunakan:
     * ```php
     * // Ambil semua lesson flow yang sudah published
     * $published = LessonFlow::status('published')->get();
     *
     * // Ambil semua lesson flow yang masih draft
     * $drafts = LessonFlow::status('draft')->get();
     * ```
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status Status yang dicari (draft, published, archived)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter lesson flow berdasarkan guru pembuat
     *
     * Cara menggunakan:
     * ```php
     * // Ambil semua lesson flow yang dibuat oleh guru dengan ID 5
     * $myFlows = LessonFlow::dibuatOleh(5)->get();
     *
     * // Atau menggunakan auth user
     * $myFlows = LessonFlow::dibuatOleh(auth()->id())->get();
     * ```
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId ID guru pembuat
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDibuatOleh($query, int $userId)
    {
        return $query->where('dibuat_oleh', $userId);
    }

    /**
     * Scope: Ambil lesson flow yang sedang aktif (published dan dalam rentang tanggal)
     *
     * Cara menggunakan:
     * ```php
     * // Ambil semua lesson flow yang sedang aktif
     * $activeFlows = LessonFlow::aktif()->get();
     * ```
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAktif($query)
    {
        $today = now()->toDateString();

        return $query->where('status', 'published')
                     ->where(function($q) use ($today) {
                         $q->whereNull('tanggal_mulai')
                           ->orWhere('tanggal_mulai', '<=', $today);
                     })
                     ->where(function($q) use ($today) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', $today);
                     });
    }

    /**
     * =====================================================
     * HELPER METHODS
     * =====================================================
     */

    /**
     * Cek apakah user tertentu adalah pembuat lesson flow ini
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * if ($lessonFlow->isPembuat(auth()->id())) {
     *     echo "Anda pembuat lesson flow ini";
     * }
     * ```
     *
     * @param int $userId ID user yang akan dicek
     * @return bool
     */
    public function isPembuat(int $userId): bool
    {
        return $this->dibuat_oleh === $userId;
    }

    /**
     * Publikasikan lesson flow
     *
     * Mengubah status dari draft menjadi published
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * $lessonFlow->publikasikan();
     * ```
     *
     * @return bool
     */
    public function publikasikan(): bool
    {
        $this->status = 'published';
        return $this->save();
    }

    /**
     * Arsipkan lesson flow
     *
     * Mengubah status menjadi archived
     *
     * Cara menggunakan:
     * ```php
     * $lessonFlow = LessonFlow::find(1);
     * $lessonFlow->arsipkan();
     * ```
     *
     * @return bool
     */
    public function arsipkan(): bool
    {
        $this->status = 'archived';
        return $this->save();
    }
}
