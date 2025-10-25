<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom tambahan ke tabel users
     * Kolom: NIP/NIS, Kelas, Foto Profil, Nomor Telepon
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // NIP untuk Guru, NIS untuk Siswa (nullable karena admin tidak punya)
            $table->string('nip_nis')->nullable()->after('email');

            // Kelas (khusus untuk Siswa, untuk Guru bisa digunakan sebagai mata pelajaran)
            $table->string('kelas')->nullable()->after('nip_nis');

            // Foto profil (menyimpan path/nama file)
            $table->string('foto')->nullable()->after('kelas');

            // Nomor telepon
            $table->string('no_telepon')->nullable()->after('foto');
        });
    }

    /**
     * Rollback: Menghapus kolom yang ditambahkan
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip_nis', 'kelas', 'foto', 'no_telepon']);
        });
    }
};
