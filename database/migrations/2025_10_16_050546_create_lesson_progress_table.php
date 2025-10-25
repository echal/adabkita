<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel untuk tracking progress siswa dalam mengikuti lesson flow
     * Menyimpan waktu mulai, waktu selesai, dan status penyelesaian
     */
    public function up(): void
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();

            // Foreign key ke lesson_flow
            $table->foreignId('id_lesson_flow')
                ->constrained('lesson_flow')
                ->onDelete('cascade')
                ->comment('ID lesson flow yang diikuti');

            // Foreign key ke users (siswa)
            $table->foreignId('id_siswa')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID siswa yang mengikuti lesson');

            // Waktu mulai mengerjakan lesson
            $table->timestamp('waktu_mulai')
                ->nullable()
                ->comment('Waktu siswa mulai mengerjakan lesson');

            // Waktu selesai mengerjakan lesson
            $table->timestamp('waktu_selesai')
                ->nullable()
                ->comment('Waktu siswa menyelesaikan lesson');

            // Status penyelesaian (mulai, sedang_dikerjakan, selesai, waktu_habis)
            $table->enum('status', ['mulai', 'sedang_dikerjakan', 'selesai', 'waktu_habis'])
                ->default('mulai')
                ->comment('Status progres lesson');

            // Durasi pengerjaan dalam detik (untuk analisis)
            $table->integer('durasi_detik')
                ->default(0)
                ->comment('Total durasi pengerjaan dalam detik');

            // Persentase penyelesaian (0-100)
            $table->decimal('persentase', 5, 2)
                ->default(0)
                ->comment('Persentase penyelesaian lesson (0-100)');

            // Item terakhir yang dilihat (untuk resume)
            $table->foreignId('item_terakhir')
                ->nullable()
                ->constrained('lesson_item')
                ->onDelete('set null')
                ->comment('ID item terakhir yang dilihat siswa');

            $table->timestamps();

            // Index untuk query performance
            $table->index(['id_siswa', 'id_lesson_flow']);
            $table->index('status');

            // Unique constraint: satu siswa hanya punya satu progress per lesson
            $table->unique(['id_lesson_flow', 'id_siswa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
