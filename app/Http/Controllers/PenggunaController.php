<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * =====================================================
 * CONTROLLER PENGGUNA (User Management)
 * =====================================================
 * Controller ini untuk mengelola data pengguna (CRUD)
 * - Admin dapat mengelola semua pengguna
 * - Fitur: Tambah, Lihat, Edit, Hapus, Filter, Upload Foto
 * =====================================================
 */
class PenggunaController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna dengan fitur filter dan pencarian
     * Halaman: Daftar Pengguna (untuk Admin)
     */
    public function index(Request $request)
    {
        // Query dasar: ambil semua pengguna
        $query = User::query();

        // Filter berdasarkan role (jika ada)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Pencarian berdasarkan nama, email, atau username
        if ($request->filled('cari')) {
            $kata_kunci = $request->cari;
            $query->where(function($q) use ($kata_kunci) {
                $q->where('name', 'like', '%' . $kata_kunci . '%')
                  ->orWhere('email', 'like', '%' . $kata_kunci . '%')
                  ->orWhere('username', 'like', '%' . $kata_kunci . '%');
            });
        }

        // Urutkan berdasarkan yang terbaru dan pagination
        $daftar_pengguna = $query->orderBy('created_at', 'desc')->paginate(10);

        // Tampilkan ke view
        return view('halaman_admin.daftar_pengguna', compact('daftar_pengguna'));
    }

    /**
     * Menampilkan form untuk menambah pengguna baru
     * Halaman: Tambah Pengguna (untuk Admin)
     */
    public function create()
    {
        // Buat objek User kosong untuk form
        $pengguna = new User();

        return view('halaman_admin.form_pengguna', compact('pengguna'));
    }

    /**
     * Menyimpan pengguna baru ke database
     * Proses: Submit Form Tambah Pengguna
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,guru,siswa',
            'nip_nis' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ], [
            // Pesan error dalam bahasa Indonesia
            'name.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Proses upload foto (jika ada)
        $nama_file_foto = null;
        if ($request->hasFile('foto')) {
            // Buat nama file unik: username_timestamp.extension
            $file = $request->file('foto');
            $nama_file_foto = $request->username . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan ke folder storage/app/public/foto_profil
            $file->storeAs('public/foto_profil', $nama_file_foto);
        }

        // Buat pengguna baru
        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nip_nis' => $request->nip_nis,
            'kelas' => $request->kelas,
            'no_telepon' => $request->no_telepon,
            'foto' => $nama_file_foto,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail pengguna tertentu
     * Halaman: Detail Pengguna
     */
    public function show($id)
    {
        // Cari pengguna berdasarkan ID
        $pengguna = User::findOrFail($id);

        return view('halaman_admin.detail_pengguna', compact('pengguna'));
    }

    /**
     * Menampilkan form untuk edit pengguna
     * Halaman: Edit Pengguna
     */
    public function edit($id)
    {
        // Cari pengguna berdasarkan ID
        $pengguna = User::findOrFail($id);

        return view('halaman_admin.form_pengguna', compact('pengguna'));
    }

    /**
     * Mengupdate data pengguna di database
     * Proses: Submit Form Edit Pengguna
     */
    public function update(Request $request, $id)
    {
        // Cari pengguna
        $pengguna = User::findOrFail($id);

        // Validasi input (username dan email harus unique kecuali milik user yang sedang diedit)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . '|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|unique:users,email,' . $id . '|max:255',
            'password' => 'nullable|string|min:6|confirmed', // Nullable untuk edit
            'role' => 'required|in:admin,guru,siswa',
            'nip_nis' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Proses upload foto baru (jika ada)
        $nama_file_foto = $pengguna->foto; // Gunakan foto lama sebagai default
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pengguna->foto) {
                Storage::delete('public/foto_profil/' . $pengguna->foto);
            }

            // Upload foto baru
            $file = $request->file('foto');
            $nama_file_foto = $request->username . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/foto_profil', $nama_file_foto);
        }

        // Update data pengguna
        $pengguna->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nip_nis' => $request->nip_nis,
            'kelas' => $request->kelas,
            'no_telepon' => $request->no_telepon,
            'foto' => $nama_file_foto,
        ]);

        // Jika ada password baru, update juga
        if ($request->filled('password')) {
            $pengguna->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diupdate!');
    }

    /**
     * Menghapus pengguna dari database
     * Proses: Hapus Pengguna
     */
    public function destroy($id)
    {
        // Cari pengguna
        $pengguna = User::findOrFail($id);

        // Hapus foto jika ada
        if ($pengguna->foto) {
            Storage::delete('public/foto_profil/' . $pengguna->foto);
        }

        // Hapus data pengguna
        $pengguna->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
