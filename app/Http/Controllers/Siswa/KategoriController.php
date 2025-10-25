<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =====================================================
 * [NEW FEATURE] Kategori Pembelajaran Controller
 * =====================================================
 * Controller untuk mengelola navigasi 3-level:
 * Level 1: Kategori Pembelajaran
 * Level 2: Halaman Kelas (Daftar Materi per Kategori)
 * Level 3: Halaman Materi Interaktif (Fullscreen)
 *
 * @package App\Http\Controllers\Siswa
 * =====================================================
 */
class KategoriController extends Controller
{
    /**
     * Mapping kategori dengan metadata
     */
    private $kategoris = [
        'adab-berjalan' => [
            'slug' => 'adab-berjalan',
            'nama' => 'Adab Berjalan',
            'icon' => '🚶',
            'deskripsi' => 'Pelajari tata cara berjalan yang sopan dan beradab menurut ajaran Islam, termasuk cara berjalan yang baik di tempat umum.',
            'gradient' => 'from-blue-400 to-cyan-500',
            'jumlah_siswa' => 45,
            'total_durasi' => 240,
            'progress' => 60,
        ],
        'adab-berpakaian' => [
            'slug' => 'adab-berpakaian',
            'nama' => 'Adab Berpakaian',
            'icon' => '👔',
            'deskripsi' => 'Cara berpakaian yang sopan dan sesuai syariat Islam, serta nilai-nilai kesopanan dalam berpenampilan.',
            'gradient' => 'from-purple-400 to-pink-500',
            'jumlah_siswa' => 52,
            'total_durasi' => 300,
            'progress' => 75,
        ],
        'adab-makan-minum' => [
            'slug' => 'adab-makan-minum',
            'nama' => 'Adab Makan dan Minum',
            'icon' => '🍽️',
            'deskripsi' => 'Pelajari tata cara makan dan minum yang baik dan benar menurut ajaran Islam, mulai dari niat, doa, hingga cara yang sopan.',
            'gradient' => 'from-orange-400 to-red-500',
            'jumlah_siswa' => 60,
            'total_durasi' => 420,
            'progress' => 85,
        ],
        'adab-media-sosial' => [
            'slug' => 'adab-media-sosial',
            'nama' => 'Adab Bermedia Sosial',
            'icon' => '📱',
            'deskripsi' => 'Etika dan adab dalam menggunakan media sosial secara islami, menjaga perkataan dan perilaku di dunia maya.',
            'gradient' => 'from-green-400 to-emerald-600',
            'jumlah_siswa' => 48,
            'total_durasi' => 360,
            'progress' => 70,
        ],
    ];

    /**
     * [NEW FEATURE] Level 1 - Tampilkan Halaman Kategori Pembelajaran
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('siswa.kategori.index');
    }

    /**
     * [NEW FEATURE] Level 2 - Tampilkan Halaman Kelas (Daftar Materi per Kategori)
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // Validasi kategori
        if (!isset($this->kategoris[$slug])) {
            abort(404, 'Kategori tidak ditemukan');
        }

        $kategori = $this->kategoris[$slug];

        // Ambil materi berdasarkan kategori
        $materiList = Materi::where('kategori', $kategori['nama'])
            ->latest('tanggal_upload')
            ->get()
            ->map(function($materi) {
                // Tentukan tipe file
                if ($materi->file_materi) {
                    $ext = pathinfo($materi->file_materi, PATHINFO_EXTENSION);
                    $materi->tipe = strtolower($ext);
                } elseif ($materi->link_embed && str_contains($materi->link_embed, 'youtube')) {
                    $materi->tipe = 'video';
                } elseif ($materi->link_embed) {
                    $materi->tipe = 'pptx';
                } else {
                    $materi->tipe = 'interaktif';
                }

                // Set durasi default
                $materi->durasi = 30;

                // Set status dan progress (sementara random untuk demo)
                // Nanti bisa diambil dari database progress siswa
                $statuses = ['belum', 'berjalan', 'selesai'];
                $materi->status = $statuses[array_rand($statuses)];
                $materi->progress = $materi->status === 'berjalan' ? rand(10, 90) : ($materi->status === 'selesai' ? 100 : 0);

                // Guru info
                $materi->guru_nama = $materi->dibuat_oleh ?? 'Ustadz Ahmad';
                $materi->guru_foto = null; // Bisa diambil dari relasi user

                // Jumlah siswa selesai (sementara random)
                $materi->siswa_selesai = rand(10, 50);

                return $materi;
            });

        return view('siswa.kategori.show', compact('kategori', 'materiList'));
    }

    /**
     * [NEW FEATURE] Level 3 - Tampilkan Halaman Materi Interaktif (Fullscreen)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewMateri($id)
    {
        $materi = Materi::findOrFail($id);

        // Tentukan kategori dari metadata
        $kategoriSlug = null;
        $kategori = null;

        foreach ($this->kategoris as $slug => $data) {
            if ($data['nama'] === $materi->kategori) {
                $kategoriSlug = $slug;
                $kategori = $data;
                break;
            }
        }

        // Jika kategori tidak ditemukan, gunakan default
        if (!$kategori) {
            $kategori = [
                'slug' => 'umum',
                'nama' => $materi->kategori ?? 'Umum',
                'icon' => '📚',
            ];
        }

        // Tentukan tipe file
        if ($materi->file_materi) {
            $ext = pathinfo($materi->file_materi, PATHINFO_EXTENSION);
            $materi->tipe = strtolower($ext);
        } elseif ($materi->link_embed && str_contains($materi->link_embed, 'youtube')) {
            $materi->tipe = 'video';
        } elseif ($materi->link_embed) {
            $materi->tipe = 'pptx';
        } else {
            $materi->tipe = 'interaktif';
        }

        // Set status (nanti ambil dari database progress)
        $materi->status = 'belum'; // Default

        return view('siswa.materi.view', compact('materi', 'kategori'));
    }

    /**
     * [NEW FEATURE] Tandai Materi Selesai
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeMateri($id)
    {
        $materi = Materi::findOrFail($id);

        // TODO: Update progress di database
        // Sementara hanya redirect dengan pesan sukses

        // Tentukan kategori slug
        $kategoriSlug = null;
        foreach ($this->kategoris as $slug => $data) {
            if ($data['nama'] === $materi->kategori) {
                $kategoriSlug = $slug;
                break;
            }
        }

        return redirect()
            ->route('siswa.kategori.show', $kategoriSlug ?? 'umum')
            ->with('success', '🎉 Selamat! Kamu telah menyelesaikan materi "' . $materi->judul_materi . '"');
    }
}
