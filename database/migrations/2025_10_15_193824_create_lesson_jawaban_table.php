<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * =====================================================
 * MIGRATION TABEL LESSON_JAWABAN
 * =====================================================
 * Migration ini digunakan untuk membuat tabel lesson_jawaban
 * yang menyimpan jawaban siswa untuk setiap lesson item
 *
 * Tabel ini merekam:
 * - Jawaban siswa untuk setiap soal
 * - Status benar/salah
 * - Poin yang didapat
 * - Waktu menjawab
 *
 * @author System
 * @created 2025-10-15
 * @package Database\Migrations
 * =====================================================
 */
return new class extends Migration
{
    /**
     * Menjalankan migrations - Membuat tabel lesson_jawaban
     *
     * Tabel ini menyimpan:
     * - Relasi ke lesson_item (soal yang dijawab)
     * - Relasi ke users (siswa yang menjawab)
     * - Jawaban siswa
     * - Status benar/salah
     * - Poin yang didapat
     * - Timestamps untuk tracking waktu menjawab
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('lesson_jawaban', function (Blueprint $table) {
            // Primary key otomatis increment
            $table->id();

            // Foreign key ke tabel lesson_item
            // Relasi: satu lesson_item bisa memiliki banyak jawaban (dari siswa berbeda)
            $table->unsignedBigInteger('id_lesson_item')->comment('ID lesson item (relasi ke lesson_item.id)');

            // Foreign key ke tabel users (siswa)
            // Relasi: satu siswa bisa menjawab banyak lesson_item
            $table->unsignedBigInteger('id_siswa')->comment('ID siswa (relasi ke users.id)');

            // Jawaban yang diberikan siswa
            // - Untuk soal PG/gambar: 'a', 'b', 'c', atau 'd'
            // - Untuk isian: teks jawaban yang diinput siswa
            $table->string('jawaban_siswa')->comment('Jawaban siswa: a/b/c/d atau teks jawaban');

            // Status benar atau salah
            // TRUE = benar, FALSE = salah
            $table->boolean('benar_salah')->default(false)->comment('Status jawaban: TRUE=benar, FALSE=salah');

            // Poin yang didapat siswa untuk jawaban ini
            // Akan diisi sesuai poin dari lesson_item jika benar, 0 jika salah
            $table->integer('poin_didapat')->default(0)->comment('Poin yang didapat (0 jika salah)');

            // Percobaan ke berapa (untuk fitur retry)
            // Jika siswa bisa mengulang, ini mencatat percobaan keberapa
            $table->integer('percobaan_ke')->default(1)->comment('Percobaan ke berapa (untuk fitur retry)');

            // Waktu mulai dan selesai menjawab (untuk tracking durasi)
            $table->timestamp('waktu_mulai')->nullable()->comment('Waktu mulai menjawab item ini');
            $table->timestamp('waktu_selesai')->nullable()->comment('Waktu selesai menjawab item ini');

            // Timestamps otomatis (created_at, updated_at)
            $table->timestamps();

            // Foreign key constraint ke tabel lesson_item
            // Jika lesson_item dihapus, semua jawaban untuk item tersebut ikut dihapus (cascade)
            $table->foreign('id_lesson_item')
                  ->references('id')
                  ->on('lesson_item')
                  ->onDelete('cascade')
                  ->comment('FK ke lesson_item - soal yang dijawab');

            // Foreign key constraint ke tabel users
            // Jika user (siswa) dihapus, semua jawabannya ikut dihapus (cascade)
            $table->foreign('id_siswa')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->comment('FK ke users - siswa yang menjawab');

            // Index untuk performa query
            $table->index('id_lesson_item', 'idx_jawaban_item');
            $table->index('id_siswa', 'idx_jawaban_siswa');
            $table->index('benar_salah', 'idx_jawaban_status');

            // Unique constraint: satu siswa hanya bisa punya satu jawaban untuk satu item di satu percobaan
            // Jika ingin retry, increment percobaan_ke
            $table->unique(['id_lesson_item', 'id_siswa', 'percobaan_ke'], 'unique_siswa_item_percobaan');
        });
    }

    /**
     * Reverse migrations - Menghapus tabel lesson_jawaban
     *
     * Method ini akan dijalankan saat rollback migration
     *
     * @return void
     */
    public function down(): void
    {
        // Drop tabel lesson_jawaban beserta semua constraint dan index
        Schema::dropIfExists('lesson_jawaban');
    }
};
