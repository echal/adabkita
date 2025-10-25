<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom durasi_menit pada tabel lesson_flow
     * untuk mengatur waktu maksimal pengerjaan lesson
     */
    public function up(): void
    {
        Schema::table('lesson_flow', function (Blueprint $table) {
            // Durasi maksimal pengerjaan lesson dalam menit (0 = unlimited)
            $table->integer('durasi_menit')->default(0)
                ->comment('Durasi maksimal pengerjaan dalam menit. 0 = tanpa batas waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_flow', function (Blueprint $table) {
            $table->dropColumn('durasi_menit');
        });
    }
};
