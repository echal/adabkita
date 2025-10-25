<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LessonFlow;
use App\Models\LessonItem;
use App\Models\LessonProgress;
use App\Models\LessonJawaban;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * =====================================================
 * [NEW FEATURE] LESSON INTERAKTIF CONTROLLER
 * =====================================================
 * Controller untuk menangani materi pembelajaran interaktif
 * dengan integrasi Materi dan Lesson Flow.
 *
 * Alur:
 * 1. Guru input data di "Kelola Materi" (tabel: materi)
 * 2. Guru buat "Lesson Flow" dengan relasi ke materi_id
 * 3. Siswa lihat daftar materi interaktif (yang punya lesson_flow)
 * 4. Siswa klik card → buka fullscreen interaktif
 *
 * @package App\Http\Controllers\Siswa
 * @author System
 * @created 2025-10-25
 * =====================================================
 */
class LessonInteraktifController extends Controller
{
    /**
     * [NEW FEATURE] Integrasi Materi Interaktif
     * Menampilkan daftar semua materi yang memiliki Lesson Flow aktif
     *
     * Route: GET /siswa/lesson-interaktif
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $idSiswa = Auth::id();

        // [NEW FEATURE] Ambil materi yang memiliki lesson flow dengan status published
        // Menggunakan Eloquent relationship antara Materi dan LessonFlow
        $materiList = LessonFlow::where('status', 'published')
            ->with(['guru', 'items']) // Eager load relasi
            ->latest()
            ->get()
            ->map(function($lessonFlow) use ($idSiswa) {
                // Ambil progress siswa untuk lesson flow ini
                $progress = LessonProgress::where('id_lesson_flow', $lessonFlow->id)
                    ->where('id_siswa', $idSiswa)
                    ->first();

                // Hitung jumlah items/soal
                $totalItems = $lessonFlow->items->count();
                $totalSoal = $lessonFlow->items()
                    ->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian'])
                    ->count();

                // Progress persentase
                $progressPersentase = $progress ? round($progress->persentase ?? 0) : 0;

                // Tentukan badge status
                $badgeStatus = 'Belum Dimulai';
                $badgeClass = 'bg-gray-500';

                if ($progress) {
                    if ($progress->status === 'selesai') {
                        $badgeStatus = 'Selesai';
                        $badgeClass = 'bg-green-500';
                    } elseif ($progress->status === 'sedang_dikerjakan') {
                        $badgeStatus = 'Sedang Belajar';
                        $badgeClass = 'bg-blue-500';
                    }
                }

                // Generate icon berdasarkan judul
                $icon = $this->getIconForLesson($lessonFlow->judul_materi);

                return [
                    'id' => $lessonFlow->id,
                    'judul' => $lessonFlow->judul_materi,
                    'deskripsi' => $lessonFlow->deskripsi,
                    'kategori' => 'Adab Islami', // Default kategori
                    'durasi' => $lessonFlow->durasi_menit ?? 30,
                    'total_items' => $totalItems,
                    'total_soal' => $totalSoal,
                    'progress' => $progressPersentase,
                    'badge_status' => $badgeStatus,
                    'badge_class' => $badgeClass,
                    'icon' => $icon,
                    'thumbnail' => $lessonFlow->thumbnail ?? null,
                    'guru_nama' => $lessonFlow->guru->name ?? 'Guru',
                ];
            });

        return view('siswa.lesson_interaktif.index', [
            'materiList' => $materiList,
        ]);
    }

    /**
     * [NEW FEATURE] Integrasi Materi Interaktif - Fullscreen
     * Menampilkan halaman fullscreen untuk belajar interaktif
     *
     * Route: GET /siswa/lesson-interaktif/{id}
     *
     * @param int $id ID Lesson Flow
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show($id, Request $request)
    {
        $idSiswa = Auth::id();

        // Ambil lesson flow dengan items diurutkan
        $lessonFlow = LessonFlow::with(['guru', 'items' => function($query) {
                $query->orderBy('urutan', 'asc');
            }, 'items.soal'])
            ->findOrFail($id);

        // Cek apakah lesson flow sudah published
        if ($lessonFlow->status !== 'published') {
            abort(403, 'Materi ini belum dipublikasikan oleh guru.');
        }

        // Ambil atau buat progress siswa
        $progress = LessonProgress::firstOrCreate(
            [
                'id_lesson_flow' => $id,
                'id_siswa' => $idSiswa,
            ],
            [
                'status' => 'sedang_dikerjakan',
                'persentase' => 0,
                'waktu_mulai' => now(),
            ]
        );

        // Update status jika masih 'mulai'
        if ($progress->status === 'mulai') {
            $progress->update(['status' => 'sedang_dikerjakan']);
        }

        // [NEW FEATURE] Navigasi item - ambil current item dari query string
        $currentItemId = $request->query('item');
        if ($currentItemId) {
            $currentItem = $lessonFlow->items->where('id', $currentItemId)->first();
        } else {
            $currentItem = $lessonFlow->items->first();
        }

        // Jika tidak ada item, redirect kembali
        if (!$currentItem) {
            return redirect()->route('siswa.lesson-interaktif.index')
                ->with('error', 'Materi belum memiliki konten pembelajaran.');
        }

        // [NEW FEATURE] Navigasi next/prev
        $currentIndex = $lessonFlow->items->search(function($item) use ($currentItem) {
            return $item->id === $currentItem->id;
        });

        $nextItem = $lessonFlow->items->get($currentIndex + 1);
        $prevItem = $currentIndex > 0 ? $lessonFlow->items->get($currentIndex - 1) : null;

        // Hitung progress detail
        $totalItems = $lessonFlow->items->count();
        $itemsDikerjakan = LessonJawaban::where('id_siswa', $idSiswa)
            ->whereIn('id_lesson_item', $lessonFlow->items->pluck('id'))
            ->distinct('id_lesson_item')
            ->count('id_lesson_item');

        $progressPersentase = $totalItems > 0 ? round(($itemsDikerjakan / $totalItems) * 100) : 0;

        // Cek apakah current item sudah dikerjakan
        $isItemDikerjakan = LessonJawaban::where('id_siswa', $idSiswa)
            ->where('id_lesson_item', $currentItem->id)
            ->exists();

        return view('siswa.lesson_interaktif.show', [
            'lessonFlow' => $lessonFlow,
            'currentItem' => $currentItem,
            'nextItem' => $nextItem,
            'prevItem' => $prevItem,
            'progress' => $progressPersentase,
            'totalItems' => $totalItems,
            'itemsDikerjakan' => $itemsDikerjakan,
            'isItemDikerjakan' => $isItemDikerjakan,
            'currentIndex' => $currentIndex,
        ]);
    }

    /**
     * [NEW FEATURE] Submit jawaban kuis
     * Route: POST /siswa/lesson-interaktif/{id}/submit
     *
     * @param Request $request
     * @param int $id ID Lesson Flow
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitJawaban(Request $request, $id)
    {
        $idSiswa = Auth::id();

        // Validasi input jawaban
        $request->validate([
            'item_id' => 'required|exists:lesson_items,id',
            'jawaban' => 'required',
        ]);

        $lessonFlow = LessonFlow::findOrFail($id);
        $itemId = $request->input('item_id');
        $jawaban = $request->input('jawaban');

        DB::beginTransaction();
        try {
            // Ambil item
            $item = LessonItem::findOrFail($itemId);

            // Cek kebenaran jawaban
            $isBenar = false;
            if ($item->tipe_item === 'soal_pg' || $item->tipe_item === 'soal_gambar') {
                $isBenar = ($item->jawaban_benar === $jawaban);
            } elseif ($item->tipe_item === 'isian') {
                $isBenar = (strtolower(trim($item->jawaban_benar)) === strtolower(trim($jawaban)));
            }

            // Simpan jawaban
            LessonJawaban::updateOrCreate(
                [
                    'id_siswa' => $idSiswa,
                    'id_lesson_item' => $itemId,
                ],
                [
                    'jawaban' => $jawaban,
                    'is_correct' => $isBenar,
                    'waktu_jawab' => now(),
                ]
            );

            // Update progress
            $this->updateProgress($idSiswa, $id);

            DB::commit();

            // Redirect ke item berikutnya atau dashboard jika sudah selesai
            $nextItemId = $request->query('next');
            if ($nextItemId) {
                return redirect()->route('siswa.lesson-interaktif.show', ['id' => $id, 'item' => $nextItemId])
                    ->with('success', $isBenar ? 'Jawaban Anda benar! 🎉' : 'Jawaban kurang tepat, coba lagi! 💪');
            } else {
                return redirect()->route('siswa.lesson-interaktif.show', $id)
                    ->with('success', $isBenar ? 'Jawaban Anda benar! 🎉' : 'Jawaban kurang tepat, coba lagi! 💪');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * [NEW FEATURE] Simpan progress belajar (AJAX)
     * Route: POST /siswa/lesson-interaktif/{id}/save-progress
     *
     * @param Request $request
     * @param int $id ID Lesson Flow
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveProgress(Request $request, $id)
    {
        $idSiswa = Auth::id();

        $progress = LessonProgress::where('id_lesson_flow', $id)
            ->where('id_siswa', $idSiswa)
            ->first();

        if ($progress) {
            $this->updateProgress($idSiswa, $id);

            return response()->json([
                'success' => true,
                'message' => 'Progress berhasil disimpan',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Progress tidak ditemukan',
        ], 404);
    }

    /**
     * [PRIVATE] Update progress siswa
     *
     * @param int $idSiswa
     * @param int $idLessonFlow
     * @return void
     */
    private function updateProgress($idSiswa, $idLessonFlow)
    {
        $lessonFlow = LessonFlow::with('items')->findOrFail($idLessonFlow);

        // Hitung total items dan items yang sudah dikerjakan
        $totalItems = $lessonFlow->items->count();
        $itemsDikerjakan = LessonJawaban::where('id_siswa', $idSiswa)
            ->whereIn('id_lesson_item', $lessonFlow->items->pluck('id'))
            ->distinct('id_lesson_item')
            ->count('id_lesson_item');

        // Hitung persentase
        $persentase = $totalItems > 0 ? round(($itemsDikerjakan / $totalItems) * 100) : 0;

        // Update progress
        $progress = LessonProgress::where('id_lesson_flow', $idLessonFlow)
            ->where('id_siswa', $idSiswa)
            ->first();

        if ($progress) {
            $progress->update([
                'persentase' => $persentase,
                'status' => $persentase >= 100 ? 'selesai' : 'sedang_dikerjakan',
                'waktu_selesai' => $persentase >= 100 ? now() : null,
            ]);
        }
    }

    /**
     * [PRIVATE] Dapatkan icon emoji berdasarkan judul
     *
     * @param string $judul
     * @return string
     */
    private function getIconForLesson($judul)
    {
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
            'berbicara' => '🗣️',
            'membaca' => '📖',
        ];

        $judulLower = strtolower($judul);

        foreach ($keywords as $keyword => $icon) {
            if (str_contains($judulLower, $keyword)) {
                return $icon;
            }
        }

        return '📖'; // Default icon
    }
}
