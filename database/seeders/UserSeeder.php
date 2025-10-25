<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Menjalankan seeder untuk membuat data user awal
     * Membuat 3 user dengan role berbeda: Admin, Guru, dan Siswa
     */
    public function run(): void
    {
        // Membuat akun Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@deeplearning.com',
            'password' => Hash::make('admin123'), // Password: admin123
            'role' => 'admin',
        ]);

        // Membuat akun Guru
        User::create([
            'name' => 'Guru Pengajar',
            'username' => 'guru',
            'email' => 'guru@deeplearning.com',
            'password' => Hash::make('guru123'), // Password: guru123
            'role' => 'guru',
        ]);

        // Membuat akun Siswa
        User::create([
            'name' => 'Siswa Pelajar',
            'username' => 'siswa',
            'email' => 'siswa@deeplearning.com',
            'password' => Hash::make('siswa123'), // Password: siswa123
            'role' => 'siswa',
        ]);
    }
}
