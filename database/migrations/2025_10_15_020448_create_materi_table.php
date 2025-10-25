<?php

/**
 * Migration untuk membuat tabel materi pembelajaran
 * Tabel ini menyimpan semua data materi adab islami dan kategori lainnya
 *
 * @created_by: System
 * @created_at: 2025-10-15
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel materi
     * Fungsi ini akan membuat tabel baru dengan semua kolom yang dibutuhkan
     */
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            // Kolom ID sebagai primary key
            $table->id();

            // Kolom untuk menyimpan judul materi
            $table->string('judul_materi');

            // Kolom untuk deskripsi singkat materi
            $table->text('deskripsi');

            // Kolom untuk isi lengkap materi
            $table->longText('isi_materi');

            // Kolom untuk menyimpan nama file yang diupload (opsional)
            $table->string('file_materi')->nullable();

            // Kolom untuk kategori materi (misalnya: Adab Islami)
            $table->string('kategori');

            // Kolom untuk menyimpan nama pembuat materi
            $table->string('dibuat_oleh');

            // Kolom untuk menyimpan tanggal upload materi
            $table->dateTime('tanggal_upload')->useCurrent();

            // Kolom timestamps otomatis (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration dengan menghapus tabel materi
     * Fungsi ini akan menghapus tabel jika migration di-rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
