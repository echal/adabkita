<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // {{-- [NEW FEATURE] Integrasi Materi Interaktif --}}

/**
 * Model Materi
 *
 * Model ini digunakan untuk mengelola data materi pembelajaran
 * seperti materi Adab Islami dan kategori lainnya.
 *
 * @package App\Models
 * @author System
 * @created 2025-10-15
 */
class Materi extends Model
{
    /**
     * Nama tabel yang digunakan oleh model ini
     * Secara default Laravel akan menggunakan nama 'materis' (plural),
     * jadi kita set manual ke 'materi'
     */
    protected $table = 'materi';

    /**
     * Kolom-kolom yang boleh diisi secara mass assignment
     * Mass assignment adalah proses mengisi banyak kolom sekaligus
     * misalnya saat create atau update
     */
    protected $fillable = [
        'judul_materi',
        'deskripsi',
        'isi_materi',
        'file_materi',
        'link_embed',        // Link embed untuk Google Slides/Office Online
        'kategori',
        'dibuat_oleh',
        'tanggal_upload',
    ];

    /**
     * Kolom-kolom yang harus di-cast ke tipe data tertentu
     * Ini memastikan tanggal_upload otomatis menjadi object Carbon
     * sehingga mudah untuk format tanggal
     */
    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan URL file materi
     * Fungsi ini akan otomatis dipanggil saat mengakses $materi->url_file
     *
     * @return string|null URL lengkap ke file atau null jika tidak ada file
     */
    public function getUrlFileAttribute()
    {
        if ($this->file_materi) {
            return asset('storage/materi/' . $this->file_materi);
        }
        return null;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     * Gunakan: Materi::kategori('Adab Islami')->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $kategori
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk mendapatkan materi terbaru
     * Gunakan: Materi::terbaru()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('tanggal_upload', 'desc');
    }

    /**
     * =====================================================
     * HELPER METHODS UNTUK TAMPILAN MATERI
     * =====================================================
     */

    /**
     * Cek apakah materi menggunakan link embed
     *
     * @return bool
     */
    public function hasEmbed()
    {
        return !empty($this->link_embed);
    }

    /**
     * Cek apakah file adalah PowerPoint (.ppt atau .pptx)
     *
     * @return bool
     */
    public function isPowerPoint()
    {
        if (!$this->file_materi) {
            return false;
        }

        $ekstensi = pathinfo($this->file_materi, PATHINFO_EXTENSION);
        return in_array(strtolower($ekstensi), ['ppt', 'pptx']);
    }

    /**
     * Cek apakah file adalah PDF
     *
     * @return bool
     */
    public function isPDF()
    {
        if (!$this->file_materi) {
            return false;
        }

        $ekstensi = pathinfo($this->file_materi, PATHINFO_EXTENSION);
        return strtolower($ekstensi) === 'pdf';
    }

    /**
     * Dapatkan URL untuk Office Viewer (preview PPT online)
     *
     * @return string
     */
    public function getOfficeViewerUrl()
    {
        if (!$this->isPowerPoint()) {
            return '';
        }

        $fileUrl = urlencode($this->url_file);
        return "https://view.officeapps.live.com/op/embed.aspx?src={$fileUrl}";
    }

    /**
     * Cek apakah Office Viewer bisa digunakan
     * Office Viewer hanya bisa mengakses URL publik, tidak bisa localhost
     *
     * @return bool
     */
    public function canUseOfficeViewer()
    {
        // Jika bukan PowerPoint, return false
        if (!$this->isPowerPoint()) {
            return false;
        }

        // Jika tidak ada file, return false
        if (!$this->url_file) {
            return false;
        }

        // Cek apakah environment production (bukan localhost)
        // Office Viewer memerlukan URL yang bisa diakses dari internet
        $isProduction = config('app.env') === 'production';

        // Cek apakah URL mengandung localhost atau 127.0.0.1
        $urlFile = $this->url_file;
        $isLocalhost = str_contains($urlFile, 'localhost') ||
                       str_contains($urlFile, '127.0.0.1') ||
                       str_contains($urlFile, '::1');

        // Office Viewer hanya bisa digunakan jika production dan bukan localhost
        return $isProduction && !$isLocalhost;
    }

    /**
     * Dapatkan extension file
     *
     * @return string
     */
    public function getFileExtension()
    {
        if (!$this->file_materi) {
            return '';
        }

        return strtolower(pathinfo($this->file_materi, PATHINFO_EXTENSION));
    }

    /**
     * =====================================================
     * [NEW FEATURE] Integrasi Materi Interaktif
     * RELASI ELOQUENT
     * =====================================================
     */

    /**
     * Relasi ke User (Guru yang membuat materi)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'name');
    }

    /**
     * =====================================================
     * [NEW FEATURE] Integrasi Materi Interaktif
     * ACCESSOR METHODS
     * =====================================================
     */

    /**
     * Accessor untuk mendapatkan progress siswa untuk materi ini
     * Sementara return 0 karena belum ada relasi langsung ke lesson_flow
     *
     * @return int Progress dalam persentase (0-100)
     */
    public function getProgressAttribute()
    {
        // [FIX] Sementara return 0 karena kolom materi_id belum ada di lesson_flows
        // Nanti bisa dikembangkan dengan menambahkan kolom materi_id atau relasi lain
        return 0;
    }

    /**
     * Accessor untuk mendapatkan status materi (belum_mulai, sedang_belajar, selesai)
     *
     * @return string Status materi
     */
    public function getStatusAttribute()
    {
        $progress = $this->progress;

        if ($progress >= 100) {
            return 'selesai';
        } elseif ($progress > 0) {
            return 'sedang_belajar';
        } else {
            return 'belum_mulai';
        }
    }

    /**
     * Accessor untuk mendapatkan jumlah siswa yang sudah menyelesaikan materi
     * Sementara return 0 karena belum ada relasi langsung ke lesson_flow
     *
     * @return int Jumlah siswa
     */
    public function getJumlahSiswaSelesaiAttribute()
    {
        // [FIX] Sementara return 0 karena kolom materi_id belum ada di lesson_flows
        // Nanti bisa dikembangkan dengan menambahkan kolom materi_id atau relasi lain
        return 0;
    }

    /**
     * Accessor untuk mendapatkan URL foto guru
     *
     * @return string|null
     */
    public function getGuruFotoUrlAttribute()
    {
        // Ambil guru dari relasi atau dari name
        $guruName = $this->dibuat_oleh;

        if (!$guruName) {
            return null;
        }

        $guru = User::where('name', $guruName)->first();

        if (!$guru || !$guru->foto) {
            return null;
        }

        return asset('storage/foto_profil/' . $guru->foto);
    }
}
