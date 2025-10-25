<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * =====================================================
 * MIGRATION TABEL LESSON_FLOW
 * =====================================================
 * Migration ini digunakan untuk membuat tabel lesson_flow
 * yang menyimpan alur utama pembelajaran interaktif
 *
 * Tabel ini berisi informasi dasar tentang alur pembelajaran
 * yang dibuat oleh guru untuk siswa.
 *
 * @author System
 * @created 2025-10-15
 * @package Database\Migrations
 * =====================================================
 */
return new class extends Migration
{
    /**
     * Menjalankan migrations - Membuat tabel lesson_flow
     *
     * Tabel ini menyimpan:
     * - Judul materi pembelajaran
     * - Deskripsi alur pembelajaran
     * - ID guru yang membuat (relasi ke tabel users)
     * - Status publikasi
     * - Timestamps otomatis
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('lesson_flow', function (Blueprint $table) {
            // Primary key otomatis increment
            $table->id();

            // Judul materi pembelajaran
            // Contoh: "Adab Bertamu dalam Islam"
            $table->string('judul_materi', 255)->comment('Judul alur pembelajaran');

            // Deskripsi lengkap alur pembelajaran
            // Menjelaskan tujuan dan isi pembelajaran
            $table->text('deskripsi')->nullable()->comment('Deskripsi alur pembelajaran');

            // ID guru yang membuat lesson flow ini
            // Relasi foreign key ke tabel users dengan role 'guru'
            $table->unsignedBigInteger('dibuat_oleh')->comment('ID guru pembuat (relasi ke users.id)');

            // Status publikasi: draft, published, archived
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft')
                  ->comment('Status publikasi: draft, published, archived');

            // Tanggal mulai dan selesai (opsional untuk jadwal)
            $table->date('tanggal_mulai')->nullable()->comment('Tanggal mulai pembelajaran');
            $table->date('tanggal_selesai')->nullable()->comment('Tanggal selesai pembelajaran');

            // Timestamps otomatis (created_at, updated_at)
            $table->timestamps();

            // Foreign key constraint ke tabel users
            // Jika guru dihapus, lesson flow akan dihapus juga (cascade)
            $table->foreign('dibuat_oleh')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->comment('FK ke users - guru pembuat');

            // Index untuk performa query
            $table->index('dibuat_oleh', 'idx_lesson_flow_dibuat_oleh');
            $table->index('status', 'idx_lesson_flow_status');
        });
    }

    /**
     * Reverse migrations - Menghapus tabel lesson_flow
     *
     * Method ini akan dijalankan saat rollback migration
     *
     * @return void
     */
    public function down(): void
    {
        // Drop tabel lesson_flow beserta semua constraint dan index
        Schema::dropIfExists('lesson_flow');
    }
};
