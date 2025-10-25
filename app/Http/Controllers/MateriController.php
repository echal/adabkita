<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * MateriController
 *
 * Controller ini mengelola semua operasi CRUD untuk materi pembelajaran
 * seperti Adab Islami dan kategori lainnya.
 *
 * Fitur:
 * - Menampilkan daftar materi
 * - Menambah materi baru dengan upload file
 * - Mengedit materi yang sudah ada
 * - Menghapus materi
 * - Melihat detail materi
 *
 * @package App\Http\Controllers
 * @author System
 * @created 2025-10-15
 */
class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua materi pembelajaran
     *
     * Fungsi ini akan menampilkan halaman index dengan daftar materi
     * yang diurutkan dari yang terbaru
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua materi dari database, urutkan dari yang terbaru
        $materi = Materi::terbaru()->paginate(10);

        // Kirim data materi ke view index
        return view('materi.index', compact('materi'));
    }

    /**
     * Menampilkan form untuk membuat materi baru
     *
     * Fungsi ini hanya bisa diakses oleh Admin dan Guru
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Tampilkan form tambah materi
        return view('materi.create');
    }

    /**
     * Menyimpan materi baru ke database
     *
     * Fungsi ini memproses data dari form tambah materi
     * dan menyimpannya ke database, termasuk upload file jika ada
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari user
        $validatedData = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'isi_materi' => 'required|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120', // Max 5MB
            'link_embed' => 'nullable|url|max:2000',
            'kategori' => 'required|string|max:255',
        ], [
            // Pesan error dalam bahasa Indonesia
            'judul_materi.required' => 'Judul materi wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'isi_materi.required' => 'Isi materi wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'file_materi.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, JPG, JPEG, atau PNG',
            'file_materi.max' => 'Ukuran file maksimal 5MB',
            'link_embed.url' => 'Link embed harus berupa URL yang valid',
            'link_embed.max' => 'Link embed maksimal 2000 karakter',
        ]);

        // Siapkan data untuk disimpan
        $data = [
            'judul_materi' => $validatedData['judul_materi'],
            'deskripsi' => $validatedData['deskripsi'],
            'isi_materi' => $validatedData['isi_materi'],
            'kategori' => $validatedData['kategori'],
            'link_embed' => $validatedData['link_embed'] ?? null,
            'dibuat_oleh' => Auth::user()->name, // Ambil nama user yang login
            'tanggal_upload' => now(), // Tanggal sekarang
        ];

        // Proses upload file jika ada
        if ($request->hasFile('file_materi')) {
            // Simpan file ke folder storage/app/public/materi
            $file = $request->file('file_materi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/materi', $fileName);

            // Tambahkan nama file ke data
            $data['file_materi'] = $fileName;
        }

        // Simpan data ke database
        Materi::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail materi tertentu
     *
     * Fungsi ini bisa diakses oleh semua role (Admin, Guru, Siswa)
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Cari materi berdasarkan ID, jika tidak ada tampilkan error 404
        $materi = Materi::findOrFail($id);

        // Tampilkan halaman detail materi
        return view('materi.show', compact('materi'));
    }

    /**
     * Menampilkan form untuk edit materi
     *
     * Fungsi ini hanya bisa diakses oleh Admin dan Guru
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Cari materi berdasarkan ID
        $materi = Materi::findOrFail($id);

        // Tampilkan form edit dengan data materi yang dipilih
        return view('materi.edit', compact('materi'));
    }

    /**
     * Memperbarui materi yang sudah ada di database
     *
     * Fungsi ini memproses data dari form edit materi
     * dan memperbarui data di database
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari user
        $validatedData = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'isi_materi' => 'required|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:5120',
            'link_embed' => 'nullable|url|max:2000',
            'kategori' => 'required|string|max:255',
        ], [
            // Pesan error dalam bahasa Indonesia
            'judul_materi.required' => 'Judul materi wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'isi_materi.required' => 'Isi materi wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'file_materi.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, JPG, JPEG, atau PNG',
            'file_materi.max' => 'Ukuran file maksimal 5MB',
            'link_embed.url' => 'Link embed harus berupa URL yang valid',
            'link_embed.max' => 'Link embed maksimal 2000 karakter',
        ]);

        // Cari materi yang akan diupdate
        $materi = Materi::findOrFail($id);

        // Siapkan data untuk diupdate
        $data = [
            'judul_materi' => $validatedData['judul_materi'],
            'deskripsi' => $validatedData['deskripsi'],
            'isi_materi' => $validatedData['isi_materi'],
            'kategori' => $validatedData['kategori'],
            'link_embed' => $validatedData['link_embed'] ?? null,
        ];

        // Proses upload file baru jika ada
        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($materi->file_materi) {
                Storage::delete('public/materi/' . $materi->file_materi);
            }

            // Simpan file baru
            $file = $request->file('file_materi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/materi', $fileName);

            // Tambahkan nama file ke data
            $data['file_materi'] = $fileName;
        }

        // Update data di database
        $materi->update($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Menghapus materi dari database
     *
     * Fungsi ini akan menghapus materi beserta file yang terkait
     * Hanya bisa diakses oleh Admin dan Guru
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Cari materi yang akan dihapus
        $materi = Materi::findOrFail($id);

        // Hapus file terkait jika ada
        if ($materi->file_materi) {
            Storage::delete('public/materi/' . $materi->file_materi);
        }

        // Hapus data dari database
        $materi->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}
