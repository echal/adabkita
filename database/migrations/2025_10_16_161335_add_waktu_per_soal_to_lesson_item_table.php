<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration - menambahkan kolom waktu_per_soal
     * Kolom ini menyimpan durasi waktu pengerjaan per soal dalam detik
     * Default 30 detik jika guru tidak mengisi
     */
    public function up(): void
    {
        Schema::table('lesson_item', function (Blueprint $table) {
            $table->integer('waktu_per_soal')->default(30)->after('poin')
                ->comment('Durasi waktu pengerjaan per soal dalam detik (default 30 detik)');
        });
    }

    /**
     * Membatalkan migration - menghapus kolom waktu_per_soal
     */
    public function down(): void
    {
        Schema::table('lesson_item', function (Blueprint $table) {
            $table->dropColumn('waktu_per_soal');
        });
    }
};
