<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model LessonProgress
 *
 * Model untuk tracking progress siswa dalam mengikuti lesson flow.
 * Menyimpan waktu mulai, waktu selesai, status penyelesaian, dan persentase progres.
 *
 * @package App\Models
 * @author System
 * @created 2025-10-16
 */
class LessonProgress extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     *
     * @var string
     */
    protected $table = 'lesson_progress';

    /**
     * Kolom yang dapat diisi mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'id_lesson_flow',
        'id_siswa',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'durasi_detik',
        'persentase',
        'item_terakhir',
    ];

    /**
     * Cast tipe data kolom
     *
     * @var array
     */
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'durasi_detik' => 'integer',
        'persentase' => 'decimal:2',
    ];

    /**
     * ========================================
     * RELASI KE MODEL LAIN
     * ========================================
     */

    /**
     * Relasi ke LessonFlow
     */
    public function lessonFlow()
    {
        return $this->belongsTo(LessonFlow::class, 'id_lesson_flow');
    }

    /**
     * Relasi ke User (Siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(User::class, 'id_siswa');
    }

    /**
     * Relasi ke LessonItem (Item Terakhir)
     */
    public function itemTerakhir()
    {
        return $this->belongsTo(LessonItem::class, 'item_terakhir');
    }

    /**
     * ========================================
     * ACCESSOR & HELPER METHODS
     * ========================================
     */

    /**
     * Accessor untuk label status
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'mulai' => 'Mulai',
            'sedang_dikerjakan' => 'Sedang Dikerjakan',
            'selesai' => 'Selesai',
            'waktu_habis' => 'Waktu Habis',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Update progress berdasarkan jawaban siswa
     */
    public function updateProgress()
    {
        $totalSoal = $this->lessonFlow->items()->soalSaja()->count();
        $soalDijawab = \App\Models\LessonJawaban::where('id_siswa', $this->id_siswa)
            ->whereIn('id_lesson_item', $this->lessonFlow->items()->soalSaja()->pluck('id'))
            ->count();

        $persentase = $totalSoal > 0 ? ($soalDijawab / $totalSoal) * 100 : 0;
        $status = $persentase >= 100 ? 'selesai' : 'sedang_dikerjakan';
        $durasi = $this->waktu_mulai ? now()->diffInSeconds($this->waktu_mulai) : 0;

        $this->update([
            'persentase' => round($persentase, 2),
            'status' => $status,
            'durasi_detik' => $durasi,
            'waktu_selesai' => $status === 'selesai' ? now() : null,
        ]);
    }

    /**
     * Cek apakah waktu sudah habis
     */
    public function isTimeout()
    {
        if ($this->lessonFlow->durasi_menit <= 0 || !$this->waktu_mulai) {
            return false;
        }

        $batasWaktu = $this->waktu_mulai->copy()->addMinutes($this->lessonFlow->durasi_menit);
        return now()->greaterThan($batasWaktu);
    }

    /**
     * Dapatkan sisa waktu dalam detik (null jika unlimited)
     */
    public function getSisaWaktuDetik()
    {
        if ($this->lessonFlow->durasi_menit <= 0) {
            return null;
        }

        if (!$this->waktu_mulai) {
            return $this->lessonFlow->durasi_menit * 60;
        }

        $batasWaktu = $this->waktu_mulai->copy()->addMinutes($this->lessonFlow->durasi_menit);
        $sisaDetik = now()->diffInSeconds($batasWaktu, false);

        return max(0, $sisaDetik);
    }

    /**
     * Tandai progress sebagai waktu habis
     *
     * Method ini dipanggil saat durasi lesson telah habis
     *
     * @return void
     */
    public function markAsTimeout()
    {
        $durasi = $this->waktu_mulai ? now()->diffInSeconds($this->waktu_mulai) : 0;

        $this->update([
            'status' => 'waktu_habis',
            'waktu_selesai' => now(),
            'durasi_detik' => $durasi,
        ]);
    }

    /**
     * Tandai progress sebagai selesai
     *
     * Method ini dipanggil saat siswa menyelesaikan semua item
     *
     * @return void
     */
    public function markAsCompleted()
    {
        $durasi = $this->waktu_mulai ? now()->diffInSeconds($this->waktu_mulai) : 0;

        $this->update([
            'status' => 'selesai',
            'persentase' => 100,
            'waktu_selesai' => now(),
            'durasi_detik' => $durasi,
        ]);
    }

    /**
     * Cek apakah progress sudah selesai
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'selesai';
    }

    /**
     * Cek apakah waktu sudah habis (status timeout)
     *
     * @return bool
     */
    public function isTimedOut()
    {
        return $this->status === 'waktu_habis';
    }
}
