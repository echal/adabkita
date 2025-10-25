<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LessonFlow;
use App\Models\LessonProgress;
use App\Models\LessonJawaban;
use App\Models\Materi; // {{-- [NEW FEATURE] Integrasi Materi Interaktif --}}
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * =====================================================
 * DASHBOARD CONTROLLER SISWA
 * =====================================================
 * Controller untuk menampilkan dashboard siswa dengan data dinamis
 * dari database (Lesson Flow, Progress, Statistik)
 *
 * @package App\Http\Controllers\Siswa
 * @author System
 * @created 2025-10-25
 * =====================================================
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Siswa
     * Route: GET /siswa/dashboard
     *
     * Halaman ini hanya bisa diakses oleh pengguna dengan role 'siswa'
     * Menampilkan:
     * - Statistik pembelajaran (total pelajaran, selesai, rata-rata nilai, dll)
     * - Daftar pelajaran yang tersedia
     * - Kategori pembelajaran
     * - Sertifikat yang diperoleh
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil ID siswa yang sedang login
        $idSiswa = Auth::id();

        // ========================================
        // 1. STATISTIK SINGKAT DASHBOARD
        // ========================================

        // Total pelajaran yang tersedia (yang sudah published)
        $totalPelajaran = LessonFlow::where('status', 'published')->count();

        // Total pelajaran yang sudah diselesaikan oleh siswa
        $pelajaranSelesai = LessonProgress::where('id_siswa', $idSiswa)
            ->where('status', 'selesai')
            ->count();

        // Total tugas/soal yang sudah dikumpulkan
        $tugasDikumpulkan = LessonJawaban::where('id_siswa', $idSiswa)
            ->distinct('id_lesson_item')
            ->count('id_lesson_item');

        // Rata-rata nilai siswa (dari lesson progress yang selesai)
        $rataRataNilai = LessonProgress::where('id_siswa', $idSiswa)
            ->where('status', 'selesai')
            ->avg('persentase');
        $rataRataNilai = $rataRataNilai ? round($rataRataNilai) : 0;

        // Peringkat siswa (berdasarkan rata-rata persentase)
        // Hitung peringkat siswa di antara semua siswa
        $peringkat = $this->hitungPeringkatSiswa($idSiswa);

        // ========================================
        // 2. DAFTAR PELAJARAN (LESSON FLOW)
        // [NEW FEATURE] - Search & Pagination
        // ========================================

        // Query builder untuk pelajaran
        $query = LessonFlow::where('status', 'published')
            ->with(['items', 'guru']); // Eager load items dan guru

        // [NEW FEATURE] Filter berdasarkan search keyword
        if ($request->has('search') && !empty($request->search)) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('judul_materi', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        // [NEW FEATURE] Pagination - 8 pelajaran per halaman
        $pelajaranPaginated = $query->orderBy('created_at', 'desc')
            ->paginate(8)
            ->appends($request->only('search')); // Pertahankan search query di pagination

        // Transform data pelajaran dengan progress siswa
        $pelajaranList = $pelajaranPaginated->map(function($lesson) use ($idSiswa) {
            // Ambil progress siswa untuk lesson ini
            $progress = LessonProgress::where('id_lesson_flow', $lesson->id)
                ->where('id_siswa', $idSiswa)
                ->first();

            // Tentukan status badge
            $badge = 'Belum Dimulai';
            $badgeClass = 'bg-gray-500';
            $progressPersentase = 0;
            $progressId = null;

            if ($progress) {
                $progressPersentase = $progress->persentase ?? 0;
                $progressId = $progress->id;

                if ($progress->status === 'selesai') {
                    $badge = 'Selesai';
                    $badgeClass = 'bg-green-500';
                } elseif ($progress->status === 'sedang_dikerjakan') {
                    $badge = 'Sedang Dipelajari';
                    $badgeClass = 'bg-blue-500';
                } elseif ($progress->status === 'mulai') {
                    $badge = 'Baru!';
                    $badgeClass = 'bg-red-500';
                }
            }

            // Hitung jumlah materi/item
            $jumlahMateri = $lesson->items->count();

            // Estimasi durasi (dari durasi_menit di lesson_flow)
            $estimasiDurasi = $lesson->durasi_menit > 0 ? $lesson->durasi_menit : 30; // default 30 menit

            return [
                'id' => $lesson->id,
                'judul' => $lesson->judul_materi,
                'deskripsi' => $lesson->deskripsi,
                'badge' => $badge,
                'badge_class' => $badgeClass,
                'progress' => round($progressPersentase),
                'progress_id' => $progressId, // [NEW FEATURE] ID untuk download sertifikat
                'jumlah_materi' => $jumlahMateri,
                'durasi' => $estimasiDurasi,
                'icon' => $this->getIconForLesson($lesson->judul_materi), // Icon emoji berdasarkan judul
                'dapat_sertifikat' => $progress && $progress->status === 'selesai' && $progressPersentase >= 80, // [NEW FEATURE] Flag sertifikat
            ];
        });

        // ========================================
        // 3. KATEGORI PEMBELAJARAN
        // ========================================

        // Daftar kategori hardcoded (bisa diganti dengan database jika ada tabel kategori)
        $kategoriList = [
            ['nama' => 'Adab Makan', 'icon' => '🥣', 'jumlah' => 8, 'warna' => 'from-orange-50 to-orange-100'],
            ['nama' => 'Adab Minum', 'icon' => '💧', 'jumlah' => 5, 'warna' => 'from-blue-50 to-blue-100'],
            ['nama' => 'Adab di Masjid', 'icon' => '🕌', 'jumlah' => 10, 'warna' => 'from-green-50 to-green-100'],
            ['nama' => 'Adab kepada Orang Tua', 'icon' => '👪', 'jumlah' => 9, 'warna' => 'from-purple-50 to-purple-100'],
            ['nama' => 'Adab Belajar', 'icon' => '📚', 'jumlah' => 12, 'warna' => 'from-yellow-50 to-yellow-100'],
            ['nama' => 'Adab Bergaul', 'icon' => '🤝', 'jumlah' => 7, 'warna' => 'from-pink-50 to-pink-100'],
            ['nama' => 'Adab Berpakaian', 'icon' => '👔', 'jumlah' => 7, 'warna' => 'from-indigo-50 to-indigo-100'],
            ['nama' => 'Adab Tidur', 'icon' => '🌙', 'jumlah' => 6, 'warna' => 'from-gray-50 to-gray-100'],
        ];

        // ========================================
        // 4. SERTIFIKAT YANG DIPEROLEH
        // ========================================

        // Ambil sertifikat dari lesson progress yang status selesai
        $sertifikatList = LessonProgress::where('id_siswa', $idSiswa)
            ->where('status', 'selesai')
            ->where('persentase', '>=', 80) // Minimal 80% untuk dapat sertifikat
            ->with('lessonFlow')
            ->orderBy('waktu_selesai', 'desc')
            ->get()
            ->map(function($progress) {
                return [
                    'id' => $progress->id,
                    'judul' => 'Sertifikat ' . $progress->lessonFlow->judul_materi,
                    'tanggal_selesai' => $progress->waktu_selesai->format('d F Y'),
                    'nilai' => round($progress->persentase),
                ];
            });

        // ========================================
        // 5. DATA GRAFIK PROGRESS MINGGUAN
        // [NEW FEATURE] - Chart.js Progress Data
        // ========================================

        // Ambil data progress 8 minggu terakhir
        $progressData = $this->getProgressChartData($idSiswa);

        // ========================================
        // 6. MATERI INTERAKTIF (NEW FEATURE)
        // {{-- [NEW FEATURE] Integrasi Materi Interaktif --}}
        // Data untuk section Materi Interaktif dari tabel 'materi'
        // ========================================

        // Ambil data dari tabel 'materi' (Materi Pembelajaran)
        // yang sudah ada di menu /materi
        $materiList = Materi::latest('tanggal_upload')
            ->limit(6) // Batasi 6 materi untuk performa
            ->get()
            ->map(function($materi) use ($idSiswa) {
                // Tambahkan icon berdasarkan judul materi
                $materi->icon = $this->getIconForLesson($materi->judul_materi);

                // Tambahkan durasi default (bisa diambil dari estimasi atau default 30 menit)
                $materi->durasi = 30; // Default 30 menit

                // [FIX] Set jumlah soal default karena belum ada relasi ke lesson_flow
                $materi->jumlah_soal = 10; // Default 10 soal

                // Progress, status, dan jumlah_siswa_selesai sudah otomatis dari accessor
                // $materi->progress (dari getProgressAttribute) - sementara return 0
                // $materi->status (dari getStatusAttribute) - otomatis 'belum_mulai'
                // $materi->jumlah_siswa_selesai (dari getJumlahSiswaSelesaiAttribute) - sementara return 0

                // Gunakan judul_materi sebagai judul
                $materi->judul = $materi->judul_materi;

                return $materi;
            });

        // ========================================
        // 7. KIRIM DATA KE VIEW
        // ========================================

        return view('halaman_siswa.dashboard', compact(
            'totalPelajaran',
            'pelajaranSelesai',
            'tugasDikumpulkan',
            'rataRataNilai',
            'peringkat',
            'pelajaranList',
            'pelajaranPaginated', // [NEW FEATURE] Pagination object
            'kategoriList',
            'sertifikatList',
            'progressData', // [NEW FEATURE] Data untuk chart
            'materiList' // [NEW FEATURE] Data materi interaktif
        ));
    }

    /**
     * [NEW FEATURE] Get Data Progress untuk Chart
     *
     * Mengambil data progress mingguan siswa untuk ditampilkan di chart
     *
     * @param int $idSiswa ID siswa
     * @return array Data chart (labels dan values)
     */
    private function getProgressChartData($idSiswa)
    {
        // Ambil data progress 8 minggu terakhir
        $weeks = [];
        $values = [];

        for ($i = 7; $i >= 0; $i--) {
            // Hitung tanggal awal dan akhir minggu
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            // Label minggu (format: "12-18 Jan")
            $weekLabel = $startOfWeek->format('d') . '-' . $endOfWeek->format('d M');

            // Rata-rata nilai pada minggu tersebut
            $avgNilai = LessonProgress::where('id_siswa', $idSiswa)
                ->where('status', 'selesai')
                ->whereBetween('waktu_selesai', [$startOfWeek, $endOfWeek])
                ->avg('persentase');

            $weeks[] = $weekLabel;
            $values[] = $avgNilai ? round($avgNilai) : 0;
        }

        return [
            'labels' => $weeks,
            'values' => $values,
        ];
    }

    /**
     * [NEW FEATURE] AJAX Search Pelajaran
     *
     * Endpoint untuk search pelajaran via AJAX
     * Return JSON untuk update course list secara real-time
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPelajaran(Request $request)
    {
        $idSiswa = Auth::id();
        $keyword = $request->get('keyword', '');

        // Query pelajaran dengan search
        $query = LessonFlow::where('status', 'published')
            ->with(['items', 'guru']);

        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('judul_materi', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $pelajaran = $query->orderBy('created_at', 'desc')
            ->limit(8) // Limit untuk AJAX
            ->get()
            ->map(function($lesson) use ($idSiswa) {
                $progress = LessonProgress::where('id_lesson_flow', $lesson->id)
                    ->where('id_siswa', $idSiswa)
                    ->first();

                $badge = 'Belum Dimulai';
                $badgeClass = 'bg-gray-500';
                $progressPersentase = 0;
                $progressId = null;

                if ($progress) {
                    $progressPersentase = $progress->persentase ?? 0;
                    $progressId = $progress->id;

                    if ($progress->status === 'selesai') {
                        $badge = 'Selesai';
                        $badgeClass = 'bg-green-500';
                    } elseif ($progress->status === 'sedang_dikerjakan') {
                        $badge = 'Sedang Dipelajari';
                        $badgeClass = 'bg-blue-500';
                    } elseif ($progress->status === 'mulai') {
                        $badge = 'Baru!';
                        $badgeClass = 'bg-red-500';
                    }
                }

                return [
                    'id' => $lesson->id,
                    'judul' => $lesson->judul_materi,
                    'deskripsi' => $lesson->deskripsi,
                    'badge' => $badge,
                    'badge_class' => $badgeClass,
                    'progress' => round($progressPersentase),
                    'progress_id' => $progressId,
                    'jumlah_materi' => $lesson->items->count(),
                    'durasi' => $lesson->durasi_menit > 0 ? $lesson->durasi_menit : 30,
                    'icon' => $this->getIconForLesson($lesson->judul_materi),
                    'dapat_sertifikat' => $progress && $progress->status === 'selesai' && $progressPersentase >= 80,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pelajaran,
            'count' => $pelajaran->count(),
        ]);
    }

    /**
     * Hitung peringkat siswa berdasarkan rata-rata nilai
     *
     * @param int $idSiswa ID siswa yang akan dihitung peringkatnya
     * @return int Peringkat siswa (1 = tertinggi)
     */
    private function hitungPeringkatSiswa($idSiswa)
    {
        // Hitung rata-rata persentase setiap siswa
        $siswaRanking = LessonProgress::select('id_siswa', DB::raw('AVG(persentase) as rata_rata'))
            ->where('status', 'selesai')
            ->groupBy('id_siswa')
            ->orderBy('rata_rata', 'desc')
            ->pluck('id_siswa')
            ->toArray();

        // Cari posisi siswa saat ini
        $posisi = array_search($idSiswa, $siswaRanking);

        // Jika tidak ditemukan (belum ada progress), return unranked
        return $posisi !== false ? $posisi + 1 : 0;
    }

    /**
     * Dapatkan icon emoji berdasarkan judul pelajaran
     *
     * @param string $judul Judul pelajaran
     * @return string Icon emoji yang sesuai
     */
    private function getIconForLesson($judul)
    {
        // Mapping kata kunci ke emoji
        $keywords = [
            'makan' => '🍽️',
            'minum' => '💧',
            'masjid' => '🕌',
            'orang tua' => '👪',
            'belajar' => '📚',
            'bergaul' => '🤝',
            'pakaian' => '👔',
            'tidur' => '🌙',
            'sholat' => '🕌',
            'puasa' => '🌙',
            'zakat' => '💰',
        ];

        $judulLower = strtolower($judul);

        foreach ($keywords as $keyword => $icon) {
            if (str_contains($judulLower, $keyword)) {
                return $icon;
            }
        }

        // Default icon jika tidak ada yang cocok
        return '📖';
    }
}
