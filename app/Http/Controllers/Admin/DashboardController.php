<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Admin
     * Route: GET /admin/dashboard
     *
     * Halaman ini hanya bisa diakses oleh pengguna dengan role 'admin'
     */
    public function index()
    {
        // Menampilkan view dashboard admin
        return view('halaman_admin.dashboard');
    }
}
