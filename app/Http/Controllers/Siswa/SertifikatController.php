<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use App\Models\LessonFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * =====================================================
 * CONTROLLER SERTIFIKAT SISWA
 * =====================================================
 * Controller untuk generate dan download sertifikat PDF
 *
 * [NEW FEATURE] - Certificate PDF Generator
 * Fitur ini memungkinkan siswa untuk:
 * - Generate sertifikat dalam format PDF
 * - Download sertifikat yang telah diperoleh
 * - Sertifikat hanya tersedia untuk nilai >= 80%
 *
 * @package App\Http\Controllers\Siswa
 * @author System
 * @created 2025-10-25
 * =====================================================
 */
class SertifikatController extends Controller
{
    /**
     * [NEW FEATURE] Download Sertifikat PDF
     *
     * Generate dan download sertifikat dalam format PDF
     * untuk pelajaran yang sudah diselesaikan dengan nilai >= 80%
     *
     * @param int $idProgress ID lesson progress
     * @return \Illuminate\Http\Response PDF download
     */
    public function download($idProgress)
    {
        // Ambil ID siswa yang sedang login
        $idSiswa = Auth::id();

        // ========================================
        // VALIDASI PROGRESS & NILAI
        // ========================================

        // Ambil data progress dengan relasi ke lesson flow dan siswa
        $progress = LessonProgress::with(['lessonFlow', 'siswa'])
            ->where('id', $idProgress)
            ->where('id_siswa', $idSiswa) // Pastikan progress milik siswa yang login
            ->where('status', 'selesai')   // Harus sudah selesai
            ->where('persentase', '>=', 80) // Minimal 80% untuk sertifikat
            ->firstOrFail(); // Throw 404 jika tidak ditemukan

        // ========================================
        // PREPARE DATA UNTUK SERTIFIKAT
        // ========================================

        $data = [
            // Data Siswa
            'nama_siswa' => $progress->siswa->name,
            'email_siswa' => $progress->siswa->email,

            // Data Pelajaran
            'judul_pelajaran' => $progress->lessonFlow->judul_materi,
            'deskripsi_pelajaran' => $progress->lessonFlow->deskripsi,

            // Data Progress & Nilai
            'nilai_akhir' => round($progress->persentase),
            'tanggal_selesai' => $progress->waktu_selesai->format('d F Y'),
            'waktu_selesai' => $progress->waktu_selesai,
            'durasi_belajar' => $this->formatDurasi($progress->durasi_detik),

            // Data Guru Pembuat
            'nama_guru' => $progress->lessonFlow->guru->name ?? 'Administrator',

            // Nomor Sertifikat (Format: CERT-{tahun}-{id progress dengan padding})
            'nomor_sertifikat' => 'CERT-' . date('Y') . '-' . str_pad($progress->id, 6, '0', STR_PAD_LEFT),

            // Tanggal Generate
            'tanggal_generate' => now()->format('d F Y'),
        ];

        // ========================================
        // GENERATE PDF
        // ========================================

        // Load view sertifikat dan convert ke PDF
        // Paper: A4 Landscape untuk sertifikat
        $pdf = Pdf::loadView('siswa.sertifikat_pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        // Nama file: Sertifikat_{Nama Siswa}_{Nama Pelajaran}.pdf
        $namaFile = 'Sertifikat_' .
                    str_replace(' ', '_', $progress->siswa->name) . '_' .
                    str_replace(' ', '_', $progress->lessonFlow->judul_materi) . '.pdf';

        // Return PDF sebagai download
        return $pdf->download($namaFile);
    }

    /**
     * [NEW FEATURE] Preview Sertifikat (tanpa download)
     *
     * Menampilkan preview sertifikat di browser
     * tanpa langsung download
     *
     * @param int $idProgress ID lesson progress
     * @return \Illuminate\Http\Response PDF stream
     */
    public function preview($idProgress)
    {
        $idSiswa = Auth::id();

        // Ambil data progress
        $progress = LessonProgress::with(['lessonFlow', 'siswa'])
            ->where('id', $idProgress)
            ->where('id_siswa', $idSiswa)
            ->where('status', 'selesai')
            ->where('persentase', '>=', 80)
            ->firstOrFail();

        // Prepare data
        $data = [
            'nama_siswa' => $progress->siswa->name,
            'email_siswa' => $progress->siswa->email,
            'judul_pelajaran' => $progress->lessonFlow->judul_materi,
            'deskripsi_pelajaran' => $progress->lessonFlow->deskripsi,
            'nilai_akhir' => round($progress->persentase),
            'tanggal_selesai' => $progress->waktu_selesai->format('d F Y'),
            'waktu_selesai' => $progress->waktu_selesai,
            'durasi_belajar' => $this->formatDurasi($progress->durasi_detik),
            'nama_guru' => $progress->lessonFlow->guru->name ?? 'Administrator',
            'nomor_sertifikat' => 'CERT-' . date('Y') . '-' . str_pad($progress->id, 6, '0', STR_PAD_LEFT),
            'tanggal_generate' => now()->format('d F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('siswa.sertifikat_pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        // Stream PDF (tampil di browser tanpa download)
        return $pdf->stream('preview_sertifikat.pdf');
    }

    /**
     * Helper: Format durasi dari detik ke format yang readable
     *
     * @param int $detik Durasi dalam detik
     * @return string Format: "2 jam 30 menit" atau "45 menit"
     */
    private function formatDurasi($detik)
    {
        if ($detik < 60) {
            return $detik . ' detik';
        }

        $menit = floor($detik / 60);

        if ($menit < 60) {
            return $menit . ' menit';
        }

        $jam = floor($menit / 60);
        $sisaMenit = $menit % 60;

        if ($sisaMenit > 0) {
            return $jam . ' jam ' . $sisaMenit . ' menit';
        }

        return $jam . ' jam';
    }

    /**
     * [NEW FEATURE] Daftar Semua Sertifikat Siswa
     *
     * Menampilkan halaman daftar semua sertifikat
     * yang pernah diperoleh siswa
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $idSiswa = Auth::id();

        // Ambil semua progress yang sudah selesai dengan nilai >= 80
        $sertifikatList = LessonProgress::with(['lessonFlow'])
            ->where('id_siswa', $idSiswa)
            ->where('status', 'selesai')
            ->where('persentase', '>=', 80)
            ->orderBy('waktu_selesai', 'desc')
            ->paginate(12); // 12 sertifikat per halaman

        return view('siswa.sertifikat_list', compact('sertifikatList'));
    }
}
