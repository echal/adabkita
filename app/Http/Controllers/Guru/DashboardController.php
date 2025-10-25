<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Guru
     * Route: GET /guru/dashboard
     *
     * Halaman ini hanya bisa diakses oleh pengguna dengan role 'guru'
     */
    public function index()
    {
        // Menampilkan view dashboard guru
        return view('halaman_guru.dashboard');
    }
}
