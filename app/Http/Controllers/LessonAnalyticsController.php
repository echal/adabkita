<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LessonFlow;
use App\Models\LessonProgress;
use App\Models\LessonJawaban;
use App\Models\User;

/**
 * =====================================================
 * LESSON ANALYTICS CONTROLLER
 * =====================================================
 * Controller untuk menampilkan analytics dan rekap nilai
 * pembelajaran interaktif untuk guru.
 *
 * Fitur:
 * - Grafik rata-rata nilai per lesson
 * - Tabel rekap nilai siswa
 * - Statistik performa siswa
 * - Filter berdasarkan lesson dan tanggal
 *
 * @package App\Http\Controllers
 * @author System
 * @created 2025-10-17
 * @phase Phase 5 - Analytics & Rekap Nilai Guru
 * =====================================================
 */
class LessonAnalyticsController extends Controller
{
    /**
     * =====================================================
     * HALAMAN UTAMA ANALYTICS
     * =====================================================
     * Menampilkan dashboard analytics dengan grafik dan
     * tabel rekap nilai siswa untuk semua lesson flow
     * yang dibuat oleh guru yang sedang login.
     *
     * Route: GET /guru/lesson-analytics
     * View: guru.lesson_analytics.index
     *
     * @return \Illuminate\View\View
     * =====================================================
     */
    public function index()
    {
        // ============================================
        // AMBIL DATA LESSON FLOW MILIK GURU LOGIN
        // ============================================
        // Hanya lesson dengan status 'published' yang ditampilkan
        $lessonFlows = LessonFlow::where('dibuat_oleh', Auth::id())
            ->where('status', 'published')
            ->with(['items' => function($query) {
                // Eager load items untuk perhitungan
                $query->orderBy('urutan', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // ============================================
        // HITUNG ANALYTICS PER LESSON
        // ============================================
        // Data untuk grafik rata-rata nilai
        $analytics = [];

        foreach ($lessonFlows as $lesson) {
            // Ambil semua progress yang sudah selesai untuk lesson ini
            $progressList = LessonProgress::where('id_lesson_flow', $lesson->id)
                ->where('status', 'selesai')
                ->with('siswa') // Eager load data siswa
                ->get();

            // Hitung total siswa yang sudah menyelesaikan
            $totalSiswa = $progressList->count();

            // Hitung rata-rata nilai
            $totalSkor = 0;
            foreach ($progressList as $progress) {
                // Hitung skor untuk setiap siswa
                $skor = $this->hitungSkorSiswa($lesson->id, $progress->id_siswa);
                $totalSkor += $skor;
            }

            $rataRata = $totalSiswa > 0 ? round($totalSkor / $totalSiswa, 2) : 0;

            // Hitung badge distribution (berapa siswa dapat gold, silver, bronze)
            $badgeDistribution = $this->hitungDistribusiBadge($lesson->id);

            $analytics[] = [
                'lesson_id' => $lesson->id,
                'lesson_judul' => $lesson->judul_materi,
                'total_siswa' => $totalSiswa,
                'rata_rata' => $rataRata,
                'gold_count' => $badgeDistribution['gold'],
                'silver_count' => $badgeDistribution['silver'],
                'bronze_count' => $badgeDistribution['bronze'],
            ];
        }

        // ============================================
        // AMBIL DATA REKAP NILAI SISWA
        // ============================================
        // Tabel detail nilai setiap siswa per lesson
        $rekap = [];

        foreach ($lessonFlows as $lesson) {
            // Ambil semua progress untuk lesson ini
            $progressList = LessonProgress::where('id_lesson_flow', $lesson->id)
                ->where('status', 'selesai')
                ->with('siswa')
                ->orderBy('waktu_selesai', 'desc')
                ->get();

            foreach ($progressList as $progress) {
                // Hitung detail untuk setiap siswa
                $skor = $this->hitungSkorSiswa($lesson->id, $progress->id_siswa);
                $badge = $this->tentukanBadge($skor);

                // Hitung statistik jawaban
                $jawabanStats = $this->hitungStatistikJawaban($lesson->id, $progress->id_siswa);

                $rekap[] = [
                    'siswa_id' => $progress->id_siswa,
                    'siswa_nama' => $progress->siswa->name,
                    'lesson_id' => $lesson->id,
                    'lesson_judul' => $lesson->judul_materi,
                    'skor' => $skor,
                    'badge' => $badge,
                    'badge_icon' => $this->getBadgeIcon($badge),
                    'total_soal' => $jawabanStats['total_soal'],
                    'total_benar' => $jawabanStats['total_benar'],
                    'total_salah' => $jawabanStats['total_salah'],
                    'durasi' => $this->formatDurasi($progress->durasi_detik),
                    'waktu_selesai' => $progress->waktu_selesai,
                ];
            }
        }

        // ============================================
        // HITUNG STATISTIK KESELURUHAN
        // ============================================
        $totalLessons = $lessonFlows->count();
        $totalSiswaAktif = collect($rekap)->pluck('siswa_id')->unique()->count();
        $rataRataKeseluruhan = collect($analytics)->avg('rata_rata');
        $totalPenyelesaian = collect($rekap)->count();

        // ============================================
        // KEMBALIKAN VIEW DENGAN DATA
        // ============================================
        return view('guru.lesson_analytics.index', [
            'lessonFlows' => $lessonFlows,
            'analytics' => $analytics,
            'rekap' => $rekap,
            'statistik' => [
                'total_lessons' => $totalLessons,
                'total_siswa_aktif' => $totalSiswaAktif,
                'rata_rata_keseluruhan' => round($rataRataKeseluruhan, 2),
                'total_penyelesaian' => $totalPenyelesaian,
            ],
        ]);
    }

    /**
     * =====================================================
     * HELPER METHOD: HITUNG SKOR SISWA
     * =====================================================
     * Menghitung skor siswa dalam persentase (0-100%)
     * berdasarkan jawaban benar dibagi total soal.
     *
     * @param int $lessonFlowId ID lesson flow
     * @param int $siswaId ID siswa
     * @return float Skor dalam persentase (0-100)
     * =====================================================
     */
    private function hitungSkorSiswa($lessonFlowId, $siswaId)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::find($lessonFlowId);

        // Hitung total soal dalam lesson ini
        $totalSoal = $lessonFlow->items()
            ->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian'])
            ->count();

        if ($totalSoal === 0) {
            return 0;
        }

        // Hitung total jawaban benar siswa
        $totalBenar = LessonJawaban::where('id_siswa', $siswaId)
            ->where('benar_salah', true)
            ->whereIn('id_lesson_item', $lessonFlow->items()->pluck('id'))
            ->count();

        // Hitung persentase
        $skor = ($totalBenar / $totalSoal) * 100;

        return round($skor, 2);
    }

    /**
     * =====================================================
     * HELPER METHOD: TENTUKAN BADGE
     * =====================================================
     * Menentukan badge (gold/silver/bronze) berdasarkan skor.
     *
     * @param float $skor Skor dalam persentase (0-100)
     * @return string Badge type: 'gold', 'silver', atau 'bronze'
     * =====================================================
     */
    private function tentukanBadge($skor)
    {
        if ($skor >= 90) {
            return 'gold';
        } elseif ($skor >= 75) {
            return 'silver';
        } else {
            return 'bronze';
        }
    }

    /**
     * =====================================================
     * HELPER METHOD: GET BADGE ICON
     * =====================================================
     * Mendapatkan emoji icon untuk badge.
     *
     * @param string $badge Badge type
     * @return string Emoji icon
     * =====================================================
     */
    private function getBadgeIcon($badge)
    {
        return match($badge) {
            'gold' => '🥇',
            'silver' => '🥈',
            'bronze' => '🥉',
            default => '🏅',
        };
    }

    /**
     * =====================================================
     * HELPER METHOD: HITUNG DISTRIBUSI BADGE
     * =====================================================
     * Menghitung berapa siswa yang mendapat gold, silver,
     * dan bronze badge untuk lesson tertentu.
     *
     * @param int $lessonFlowId ID lesson flow
     * @return array Distribusi badge
     * =====================================================
     */
    private function hitungDistribusiBadge($lessonFlowId)
    {
        $progressList = LessonProgress::where('id_lesson_flow', $lessonFlowId)
            ->where('status', 'selesai')
            ->get();

        $distribution = [
            'gold' => 0,
            'silver' => 0,
            'bronze' => 0,
        ];

        foreach ($progressList as $progress) {
            $skor = $this->hitungSkorSiswa($lessonFlowId, $progress->id_siswa);
            $badge = $this->tentukanBadge($skor);
            $distribution[$badge]++;
        }

        return $distribution;
    }

    /**
     * =====================================================
     * HELPER METHOD: HITUNG STATISTIK JAWABAN
     * =====================================================
     * Menghitung total soal, benar, dan salah untuk siswa
     * pada lesson tertentu.
     *
     * @param int $lessonFlowId ID lesson flow
     * @param int $siswaId ID siswa
     * @return array Statistik jawaban
     * =====================================================
     */
    private function hitungStatistikJawaban($lessonFlowId, $siswaId)
    {
        $lessonFlow = LessonFlow::find($lessonFlowId);

        $totalSoal = $lessonFlow->items()
            ->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian'])
            ->count();

        $jawabanSiswa = LessonJawaban::where('id_siswa', $siswaId)
            ->whereIn('id_lesson_item', $lessonFlow->items()->pluck('id'))
            ->get();

        $totalBenar = $jawabanSiswa->where('benar_salah', true)->count();
        $totalSalah = $jawabanSiswa->where('benar_salah', false)->count();

        return [
            'total_soal' => $totalSoal,
            'total_benar' => $totalBenar,
            'total_salah' => $totalSalah,
        ];
    }

    /**
     * =====================================================
     * HELPER METHOD: FORMAT DURASI
     * =====================================================
     * Mengkonversi durasi dalam detik ke format human-readable.
     *
     * @param int|null $durasiDetik Durasi dalam detik
     * @return string Durasi terformat (contoh: "15 menit 30 detik")
     * =====================================================
     */
    private function formatDurasi($durasiDetik)
    {
        if (!$durasiDetik) {
            return 'Tidak tersedia';
        }

        $menit = floor($durasiDetik / 60);
        $detik = $durasiDetik % 60;

        if ($menit > 0) {
            return "{$menit} menit {$detik} detik";
        } else {
            return "{$detik} detik";
        }
    }

    /**
     * =====================================================
     * HALAMAN DETAIL ANALYTICS PER LESSON
     * =====================================================
     * Menampilkan analytics detail untuk satu lesson flow
     * tertentu dengan grafik lebih mendalam.
     *
     * Route: GET /guru/lesson-analytics/{id}
     * View: guru.lesson_analytics.detail
     *
     * @param int $id ID lesson flow
     * @return \Illuminate\View\View
     * =====================================================
     */
    public function detail($id)
    {
        // Validasi: Pastikan lesson flow milik guru yang login
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->with('items')
            ->firstOrFail();

        // Ambil semua progress untuk lesson ini
        $progressList = LessonProgress::where('id_lesson_flow', $id)
            ->where('status', 'selesai')
            ->with('siswa')
            ->orderBy('waktu_selesai', 'desc')
            ->get();

        // Hitung detail analytics
        $detailData = [];
        foreach ($progressList as $progress) {
            $skor = $this->hitungSkorSiswa($id, $progress->id_siswa);
            $badge = $this->tentukanBadge($skor);
            $stats = $this->hitungStatistikJawaban($id, $progress->id_siswa);

            $detailData[] = [
                'siswa' => $progress->siswa,
                'skor' => $skor,
                'badge' => $badge,
                'badge_icon' => $this->getBadgeIcon($badge),
                'total_benar' => $stats['total_benar'],
                'total_salah' => $stats['total_salah'],
                'total_soal' => $stats['total_soal'],
                'durasi' => $this->formatDurasi($progress->durasi_detik),
                'waktu_selesai' => $progress->waktu_selesai,
            ];
        }

        // Hitung distribusi badge
        $badgeDistribution = $this->hitungDistribusiBadge($id);

        return view('guru.lesson_analytics.detail', [
            'lessonFlow' => $lessonFlow,
            'detailData' => $detailData,
            'badgeDistribution' => $badgeDistribution,
            'totalSiswa' => $progressList->count(),
            'rataRata' => collect($detailData)->avg('skor'),
        ]);
    }
}

// ✅ Phase 5: Analytics & Rekap Nilai Guru - Controller complete
