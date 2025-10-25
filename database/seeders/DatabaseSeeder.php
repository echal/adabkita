<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan semua seeder untuk mengisi database
     * Memanggil UserSeeder untuk membuat data user awal
     */
    public function run(): void
    {
        // Memanggil UserSeeder untuk membuat data user Admin, Guru, dan Siswa
        $this->call([
            UserSeeder::class,
        ]);
    }
}
