<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom link_embed pada tabel materi
     * untuk menyimpan link embed dari Google Slides, OneDrive, dll
     */
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            // Kolom untuk menyimpan link embed (Google Slides, OneDrive, dll)
            // Nullable karena tidak semua materi menggunakan embed
            $table->text('link_embed')->nullable()->after('file_materi')
                ->comment('Link embed untuk Google Slides atau Office Online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn('link_embed');
        });
    }
};
