<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * =====================================================
 * MIGRATION TABEL LESSON_ITEM
 * =====================================================
 * Migration ini digunakan untuk membuat tabel lesson_item
 * yang menyimpan item-item pembelajaran dalam alur
 *
 * Setiap lesson_item adalah elemen dari lesson_flow:
 * - Video YouTube
 * - Gambar ilustrasi
 * - Soal Pilihan Ganda
 * - Soal dengan Gambar
 * - Soal Isian Singkat
 *
 * @author System
 * @created 2025-10-15
 * @package Database\Migrations
 * =====================================================
 */
return new class extends Migration
{
    /**
     * Menjalankan migrations - Membuat tabel lesson_item
     *
     * Tabel ini menyimpan elemen-elemen pembelajaran:
     * - Relasi ke lesson_flow (parent)
     * - Tipe item (video, gambar, soal_pg, soal_gambar, isian)
     * - Konten (URL video, gambar, pertanyaan)
     * - Opsi jawaban untuk soal pilihan ganda
     * - Jawaban benar untuk validasi
     * - Urutan tampilan
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('lesson_item', function (Blueprint $table) {
            // Primary key otomatis increment
            $table->id();

            // Foreign key ke tabel lesson_flow (parent)
            // Relasi: satu lesson_flow memiliki banyak lesson_item
            $table->unsignedBigInteger('id_lesson_flow')->comment('ID lesson flow (relasi ke lesson_flow.id)');

            // Tipe item pembelajaran
            // - video: Video YouTube (iframe)
            // - gambar: Gambar ilustrasi
            // - soal_pg: Soal Pilihan Ganda (A, B, C, D)
            // - soal_gambar: Soal dengan gambar (pilihan bergambar)
            // - isian: Soal Isian Singkat
            $table->enum('tipe_item', ['video', 'gambar', 'soal_pg', 'soal_gambar', 'isian'])
                  ->comment('Tipe item: video, gambar, soal_pg, soal_gambar, isian');

            // Konten utama item
            // - Untuk video: URL YouTube (contoh: https://www.youtube.com/watch?v=xxxxx)
            // - Untuk gambar: Path file gambar atau URL
            // - Untuk soal: Teks pertanyaan
            $table->text('konten')->comment('Konten utama: URL video/gambar atau teks soal');

            // Opsi jawaban untuk soal pilihan ganda
            // Nullable karena hanya digunakan untuk tipe soal_pg dan soal_gambar
            $table->string('opsi_a', 500)->nullable()->comment('Opsi jawaban A (untuk soal PG)');
            $table->string('opsi_b', 500)->nullable()->comment('Opsi jawaban B (untuk soal PG)');
            $table->string('opsi_c', 500)->nullable()->comment('Opsi jawaban C (untuk soal PG)');
            $table->string('opsi_d', 500)->nullable()->comment('Opsi jawaban D (untuk soal PG)');

            // Gambar untuk opsi (khusus soal_gambar)
            // Menyimpan path file gambar untuk setiap opsi
            $table->string('gambar_opsi_a')->nullable()->comment('Path gambar opsi A (untuk soal_gambar)');
            $table->string('gambar_opsi_b')->nullable()->comment('Path gambar opsi B (untuk soal_gambar)');
            $table->string('gambar_opsi_c')->nullable()->comment('Path gambar opsi C (untuk soal_gambar)');
            $table->string('gambar_opsi_d')->nullable()->comment('Path gambar opsi D (untuk soal_gambar)');

            // Jawaban benar
            // - Untuk soal_pg/soal_gambar: 'a', 'b', 'c', atau 'd'
            // - Untuk isian: teks jawaban yang benar (case insensitive)
            // Nullable untuk tipe video dan gambar yang bukan soal
            $table->string('jawaban_benar')->nullable()->comment('Jawaban benar: a/b/c/d atau teks jawaban');

            // Poin/skor untuk soal (default 10)
            $table->integer('poin')->default(10)->comment('Poin yang didapat jika benar');

            // Penjelasan jawaban (opsional)
            // Ditampilkan setelah siswa menjawab
            $table->text('penjelasan')->nullable()->comment('Penjelasan jawaban (ditampilkan setelah dijawab)');

            // Urutan tampilan dalam lesson flow
            // Semakin kecil, semakin awal ditampilkan
            $table->integer('urutan')->default(0)->comment('Urutan tampilan item dalam flow');

            // Timestamps otomatis (created_at, updated_at)
            $table->timestamps();

            // Foreign key constraint ke tabel lesson_flow
            // Jika lesson_flow dihapus, semua item-nya ikut dihapus (cascade)
            $table->foreign('id_lesson_flow')
                  ->references('id')
                  ->on('lesson_flow')
                  ->onDelete('cascade')
                  ->comment('FK ke lesson_flow - parent lesson');

            // Index untuk performa query
            $table->index('id_lesson_flow', 'idx_lesson_item_flow');
            $table->index('tipe_item', 'idx_lesson_item_tipe');
            $table->index('urutan', 'idx_lesson_item_urutan');
        });
    }

    /**
     * Reverse migrations - Menghapus tabel lesson_item
     *
     * Method ini akan dijalankan saat rollback migration
     *
     * @return void
     */
    public function down(): void
    {
        // Drop tabel lesson_item beserta semua constraint dan index
        Schema::dropIfExists('lesson_item');
    }
};
