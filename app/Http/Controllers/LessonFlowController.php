<?php

namespace App\Http\Controllers;

use App\Models\LessonFlow;
use App\Models\LessonItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =====================================================
 * CONTROLLER LESSON FLOW
 * =====================================================
 * Controller untuk mengelola Lesson Flow Interaktif
 *
 * Fitur:
 * - Guru bisa melihat daftar lesson flow yang dibuat
 * - Guru bisa membuat lesson flow baru
 * - Guru bisa mengedit lesson flow (judul, deskripsi)
 * - Guru bisa mengelola item pembelajaran (video, gambar, soal)
 * - Guru bisa hapus lesson flow
 *
 * @package App\Http\Controllers
 * @author System
 * @created 2025-10-15
 * =====================================================
 */
class LessonFlowController extends Controller
{
    /**
     * Tampilkan daftar semua lesson flow
     *
     * Menampilkan halaman index dengan daftar lesson flow
     * yang dibuat oleh guru yang sedang login.
     *
     * Route: GET /lesson-flow
     * View: lesson_flow.index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua lesson flow yang dibuat oleh guru yang login
        // Urutkan berdasarkan terbaru (created_at desc)
        // Gunakan pagination 10 item per halaman
        $lessonFlows = LessonFlow::where('dibuat_oleh', Auth::id())
            ->with('guru') // Eager load relasi guru untuk efisiensi
            ->withCount('items') // Hitung jumlah item dalam flow
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Kirim data ke view
        return view('lesson_flow.index', [
            'lessonFlows' => $lessonFlows,
        ]);
    }

    /**
     * Tampilkan form untuk membuat lesson flow baru
     *
     * Menampilkan form input untuk judul dan deskripsi
     * lesson flow baru.
     *
     * Route: GET /lesson-flow/create
     * View: lesson_flow.create
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Tampilkan form create
        return view('lesson_flow.create');
    }

    /**
     * Simpan lesson flow baru ke database
     *
     * Menerima data dari form create, validasi,
     * dan simpan ke database. Setelah itu redirect
     * ke halaman edit untuk menambah item.
     *
     * Route: POST /lesson-flow
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'durasi_menit' => 'nullable|integer|min:0|max:1440',
        ], [
            // Pesan error dalam Bahasa Indonesia
            'judul_materi.required' => 'Judul materi wajib diisi',
            'judul_materi.max' => 'Judul materi maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'durasi_menit.integer' => 'Durasi harus berupa angka',
            'durasi_menit.min' => 'Durasi minimal 0 menit (0 = tanpa batas waktu)',
            'durasi_menit.max' => 'Durasi maksimal 1440 menit (24 jam)',
        ]);

        // Tambahkan data pembuat (guru yang login)
        $validated['dibuat_oleh'] = Auth::id();

        // Status default adalah draft
        $validated['status'] = 'draft';

        // Simpan ke database
        $lessonFlow = LessonFlow::create($validated);

        // Redirect ke halaman edit untuk menambah item
        return redirect()
            ->route('lesson-flow.edit', $lessonFlow->id)
            ->with('success', 'Lesson Flow berhasil dibuat! Sekarang tambahkan item pembelajaran.');
    }

    /**
     * Tampilkan detail lesson flow
     *
     * Menampilkan detail lengkap lesson flow beserta
     * semua item yang ada di dalamnya (preview).
     *
     * Route: GET /lesson-flow/{id}
     * View: lesson_flow.show
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ambil lesson flow berdasarkan ID
        // Pastikan hanya pembuat yang bisa melihat
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->with(['items' => function($query) {
                // Urutkan items berdasarkan urutan
                $query->orderBy('urutan', 'asc');
            }])
            ->firstOrFail();

        // Kirim ke view
        return view('lesson_flow.show', [
            'lessonFlow' => $lessonFlow,
        ]);
    }

    /**
     * Tampilkan form edit lesson flow dan kelola item
     *
     * Halaman ini adalah halaman utama untuk mengelola
     * lesson flow. Guru bisa:
     * - Edit judul dan deskripsi
     * - Menambah item (video, gambar, soal)
     * - Mengatur urutan item
     * - Edit/hapus item
     *
     * Route: GET /lesson-flow/{id}/edit
     * View: lesson_flow.edit
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Ambil lesson flow berdasarkan ID
        // Pastikan hanya pembuat yang bisa edit
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->with(['items' => function($query) {
                // Urutkan items berdasarkan urutan
                $query->orderBy('urutan', 'asc');
            }])
            ->firstOrFail();

        // Kirim ke view edit (kelola urutan)
        return view('lesson_flow.edit', [
            'lessonFlow' => $lessonFlow,
        ]);
    }

    /**
     * Update lesson flow (judul, deskripsi, status, dll)
     *
     * Method ini untuk update data lesson flow saja,
     * bukan untuk update item di dalamnya.
     *
     * Route: PUT /lesson-flow/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,published,archived',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'durasi_menit' => 'nullable|integer|min:0|max:1440',
        ], [
            // Pesan error Bahasa Indonesia
            'judul_materi.required' => 'Judul materi wajib diisi',
            'judul_materi.max' => 'Judul materi maksimal 255 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai',
            'durasi_menit.integer' => 'Durasi harus berupa angka',
            'durasi_menit.min' => 'Durasi minimal 0 menit',
            'durasi_menit.max' => 'Durasi maksimal 1440 menit',
        ]);

        // Update data
        $lessonFlow->update($validated);

        // Redirect kembali dengan pesan sukses
        return redirect()
            ->route('lesson-flow.edit', $lessonFlow->id)
            ->with('success', 'Lesson Flow berhasil diperbarui!');
    }

    /**
     * Hapus lesson flow dari database
     *
     * Menghapus lesson flow beserta semua item di dalamnya
     * (karena cascade delete di migration).
     *
     * Route: DELETE /lesson-flow/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Simpan judul untuk pesan
        $judul = $lessonFlow->judul_materi;

        // Hapus lesson flow (cascade akan hapus items & jawaban)
        $lessonFlow->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()
            ->route('lesson-flow.index')
            ->with('success', "Lesson Flow \"$judul\" berhasil dihapus!");
    }

    /**
     * Update urutan item dalam lesson flow
     *
     * Method ini dipanggil via AJAX dari drag & drop
     * untuk menyimpan urutan baru item.
     *
     * Route: POST /lesson-flow/{id}/update-order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request, $id)
    {
        // Validasi bahwa user adalah pembuat
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Ambil array urutan dari request
        // Format: ['item_id' => urutan]
        $orders = $request->input('orders', []);

        // Update urutan setiap item
        foreach ($orders as $itemId => $urutan) {
            LessonItem::where('id', $itemId)
                ->where('id_lesson_flow', $lessonFlow->id)
                ->update(['urutan' => $urutan]);
        }

        // Return JSON response untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diperbarui!',
        ]);
    }

    /**
     * Publikasikan lesson flow (ubah status menjadi published)
     *
     * Route: POST /lesson-flow/{id}/publish
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish($id)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Cek apakah ada minimal 1 item
        if ($lessonFlow->items()->count() === 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat mempublikasi lesson flow kosong. Tambahkan minimal 1 item!');
        }

        // Publikasikan
        $lessonFlow->publikasikan();

        // Redirect dengan pesan sukses
        return redirect()
            ->route('lesson-flow.show', $lessonFlow->id)
            ->with('success', 'Lesson Flow berhasil dipublikasi dan dapat diakses siswa!');
    }

    /**
     * Arsipkan lesson flow (ubah status menjadi archived)
     *
     * Route: POST /lesson-flow/{id}/archive
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive($id)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Arsipkan
        $lessonFlow->arsipkan();

        // Redirect dengan pesan sukses
        return redirect()
            ->route('lesson-flow.index')
            ->with('success', 'Lesson Flow berhasil diarsipkan!');
    }

    /**
     * =====================================================
     * METHOD UNTUK SISWA
     * =====================================================
     */

    /**
     * Tampilkan daftar lesson flow untuk siswa
     *
     * Menampilkan hanya lesson flow yang sudah dipublikasi
     * dan tersedia untuk diikuti siswa.
     *
     * Route: GET /siswa/lesson-interaktif
     * View: siswa.lesson_interaktif.index
     *
     * @return \Illuminate\View\View
     */
    public function indexSiswa()
    {
        // Ambil lesson flow yang published dan aktif
        $lessonFlows = LessonFlow::where('status', 'published')
            ->with(['guru', 'items'])
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('siswa.lesson_interaktif.index', [
            'lessonFlows' => $lessonFlows,
        ]);
    }

    /**
     * Halaman untuk siswa mengikuti lesson flow interaktif
     *
     * Menampilkan item per item dengan fitur menjawab soal.
     * Jawaban akan disimpan via AJAX.
     * Sistem akan tracking progress siswa (waktu mulai, item terakhir, dll).
     *
     * Route: GET /siswa/lesson-interaktif/{id}/mulai
     * View: siswa.lesson_interaktif.mulai
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function mulaiLesson($id)
    {
        // ============================================
        // PHASE 1: AUTO-PLAY VIDEO & AUTO-NEXT
        // ============================================
        // Fungsi: Muat lesson beserta item-itemnya, deteksi video di item pertama
        // untuk auto-play, dan siapkan data untuk progress tracking
        // ============================================

        // Ambil lesson flow yang published beserta semua items (diurutkan)
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('status', 'published')
            ->with(['items' => function($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->firstOrFail();

        // ============================================
        // HITUNG STATISTIK LESSON
        // ============================================
        // Total items: semua elemen (video, gambar, soal)
        $totalItems = $lessonFlow->items->count();

        // Total soal: hanya item yang bertipe soal
        $totalSoal = $lessonFlow->items->whereIn('tipe_item', ['soal_pg', 'soal_gambar', 'isian'])->count();

        // Total konten: video dan gambar saja
        $totalKonten = $lessonFlow->items->whereIn('tipe_item', ['video', 'gambar'])->count();

        // Item pertama (untuk deteksi auto-play video)
        $itemPertama = $lessonFlow->items->first();
        $isVideoFirst = $itemPertama && $itemPertama->tipe_item === 'video';

        // ============================================
        // PROGRESS TRACKING - Buat atau ambil progress siswa
        // ============================================
        $progress = \App\Models\LessonProgress::firstOrCreate(
            [
                'id_lesson_flow' => $lessonFlow->id,
                'id_siswa' => Auth::id(),
            ],
            [
                // Data awal saat pertama kali mulai
                'waktu_mulai' => now(),
                'status' => 'sedang_dikerjakan',
                'persentase' => 0,
                'durasi_detik' => 0,
            ]
        );

        // Jika waktu_mulai null (data lama), set sekarang
        if (!$progress->waktu_mulai) {
            $progress->update(['waktu_mulai' => now()]);
        }

        // Cek apakah waktu sudah habis (jika ada durasi)
        if ($progress->isTimeout()) {
            $progress->markAsTimeout();
            return redirect()
                ->route('siswa.lesson-interaktif.hasil', $lessonFlow->id)
                ->with('warning', 'Waktu pengerjaan lesson telah habis. Silakan lihat hasil Anda.');
        }

        // Hitung sisa waktu dalam detik (null jika unlimited)
        $sisaWaktuDetik = $progress->getSisaWaktuDetik();

        // Cek jawaban siswa yang sudah ada (untuk resume progress)
        $jawabanSiswa = \App\Models\LessonJawaban::where('id_siswa', Auth::id())
            ->whereIn('id_lesson_item', $lessonFlow->items->pluck('id'))
            ->get()
            ->keyBy('id_lesson_item');

        // Update progress persentase
        $progress->updateProgress();

        // ============================================
        // KEMBALIKAN DATA KE VIEW
        // ============================================
        return view('siswa.lesson_interaktif.mulai', [
            'lessonFlow' => $lessonFlow,
            'items' => $lessonFlow->items,          // Semua items untuk navigasi
            'itemPertama' => $itemPertama,          // Item pertama untuk auto-play
            'isVideoFirst' => $isVideoFirst,        // Flag video pertama
            'totalItems' => $totalItems,            // Total semua items
            'totalSoal' => $totalSoal,              // Total soal saja
            'totalKonten' => $totalKonten,          // Total video & gambar
            'jawabanSiswa' => $jawabanSiswa,        // Jawaban yang sudah ada
            'progress' => $progress,                // Progress tracking
            'sisaWaktuDetik' => $sisaWaktuDetik,    // Sisa waktu (null = unlimited)
        ]);
    }

    /**
     * Simpan jawaban siswa via AJAX
     *
     * Method ini dipanggil saat siswa menjawab soal.
     * Validasi jawaban, simpan ke database, dan update progress.
     *
     * Route: POST /siswa/lesson-interaktif/simpan-jawaban
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simpanJawaban(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_lesson_item' => 'required|exists:lesson_item,id',
            'jawaban_siswa' => 'required|string',
        ]);

        // Ambil lesson item
        $item = LessonItem::findOrFail($validated['id_lesson_item']);

        // Ambil lesson flow dari item
        $lessonFlow = $item->lessonFlow;

        // ============================================
        // CEK TIMEOUT - Cek apakah waktu sudah habis
        // ============================================
        $progress = \App\Models\LessonProgress::where('id_lesson_flow', $lessonFlow->id)
            ->where('id_siswa', Auth::id())
            ->first();

        if ($progress && $progress->isTimeout()) {
            return response()->json([
                'success' => false,
                'timeout' => true,
                'message' => 'Waktu pengerjaan telah habis!',
            ], 403);
        }

        // Cek apakah jawaban benar
        $isBenar = $item->cekJawaban($validated['jawaban_siswa']);

        // Hitung poin
        $poinDidapat = $isBenar ? $item->poin : 0;

        // Cek apakah siswa sudah pernah menjawab item ini
        $jawabanExist = \App\Models\LessonJawaban::where('id_lesson_item', $item->id)
            ->where('id_siswa', Auth::id())
            ->first();

        if ($jawabanExist) {
            // Update jawaban (retry)
            $percobaan = $jawabanExist->percobaan_ke + 1;

            $jawabanExist->update([
                'jawaban_siswa' => $validated['jawaban_siswa'],
                'benar_salah' => $isBenar,
                'poin_didapat' => $poinDidapat,
                'percobaan_ke' => $percobaan,
                'waktu_selesai' => now(),
            ]);
        } else {
            // Buat jawaban baru
            \App\Models\LessonJawaban::create([
                'id_lesson_item' => $item->id,
                'id_siswa' => Auth::id(),
                'jawaban_siswa' => $validated['jawaban_siswa'],
                'benar_salah' => $isBenar,
                'poin_didapat' => $poinDidapat,
                'percobaan_ke' => 1,
                'waktu_mulai' => now(),
                'waktu_selesai' => now(),
            ]);
        }

        // ============================================
        // UPDATE PROGRESS - Update progress siswa setelah menjawab
        // ============================================
        if ($progress) {
            // Update item terakhir yang dikerjakan
            $progress->update(['item_terakhir' => $item->id]);

            // Update persentase progres
            $progress->updateProgress();
        }

        // Return response JSON dengan pesan motivasi
        $pesanMotivasi = $isBenar
            ? ['MasyaAllah, hebat! 🌟', 'Alhamdulillah, benar! ✅', 'Luar biasa! Keep it up! 🎉', 'Sempurna! 💯']
            : ['Coba lagi ya! 💪', 'Jangan menyerah! 📚', 'Hampir benar, semangat! 🔥'];

        return response()->json([
            'success' => true,
            'benar' => $isBenar,
            'poin' => $poinDidapat,
            'penjelasan' => $item->penjelasan,
            'jawaban_benar' => $item->jawaban_benar,
            'message' => $pesanMotivasi[array_rand($pesanMotivasi)],
            'progress_persentase' => $progress ? $progress->persentase : 0,
        ]);
    }

    /**
     * Tampilkan hasil/skor setelah siswa selesai
     *
     * ============================================
     * PHASE 4: BADGE SYSTEM + HASIL AKHIR
     * ============================================
     * Fungsi: Menampilkan hasil pembelajaran dengan badge achievement
     * berdasarkan skor yang didapat (gold/silver/bronze)
     * ============================================
     *
     * Route: GET /siswa/lesson-interaktif/{id}/hasil
     * View: siswa.lesson_interaktif.hasil
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function hasilLesson($id)
    {
        // Ambil lesson flow
        $lessonFlow = LessonFlow::where('id', $id)
            ->where('status', 'published')
            ->with(['items' => function($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->firstOrFail();

        // Ambil semua jawaban siswa untuk lesson ini
        $jawabanSiswa = \App\Models\LessonJawaban::where('id_siswa', Auth::id())
            ->whereIn('id_lesson_item', $lessonFlow->items->pluck('id'))
            ->with('lessonItem')
            ->get();

        // ============================================
        // HITUNG STATISTIK
        // ============================================
        $totalSoal = $lessonFlow->items()->soalSaja()->count();
        $totalJawaban = $jawabanSiswa->count();
        $totalBenar = $jawabanSiswa->where('benar_salah', true)->count();
        $totalSalah = $jawabanSiswa->where('benar_salah', false)->count();
        $totalPoin = $jawabanSiswa->sum('poin_didapat');
        $maxPoin = $lessonFlow->total_poin;

        // Hitung skor persentase
        $skor = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100, 2) : 0;

        // ============================================
        // BADGE DETERMINATION - Tentukan badge berdasarkan skor
        // ============================================
        if ($skor >= 90) {
            $badge = 'gold';
            $badgeLabel = 'Gold';
            $badgeMessage = 'Luar biasa! Kamu menguasai materi dengan sangat baik!';
            $badgeIcon = '🥇';
            $badgeColor = 'warning'; // Bootstrap yellow/gold
        } elseif ($skor >= 75) {
            $badge = 'silver';
            $badgeLabel = 'Silver';
            $badgeMessage = 'Bagus! Kamu sudah memahami sebagian besar materi!';
            $badgeIcon = '🥈';
            $badgeColor = 'secondary'; // Bootstrap gray
        } else {
            $badge = 'bronze';
            $badgeLabel = 'Bronze';
            $badgeMessage = 'Tetap semangat! Pelajari kembali materi yang belum dikuasai.';
            $badgeIcon = '🥉';
            $badgeColor = 'danger'; // Bootstrap orange/red-brown
        }

        // ============================================
        // UPDATE PROGRESS - Tandai lesson sebagai selesai
        // ============================================
        $progress = \App\Models\LessonProgress::where('id_lesson_flow', $lessonFlow->id)
            ->where('id_siswa', Auth::id())
            ->first();

        if ($progress) {
            // Hitung durasi (dalam detik)
            $waktuMulai = $progress->waktu_mulai;
            $waktuSelesai = now();
            $durasiDetik = $waktuMulai ? $waktuMulai->diffInSeconds($waktuSelesai) : 0;

            // Update status progress menjadi selesai
            $progress->update([
                'status' => 'selesai',
                'waktu_selesai' => $waktuSelesai,
                'persentase' => 100,
                'durasi_detik' => $durasiDetik,
            ]);

            // Format durasi untuk ditampilkan (contoh: 15 menit 30 detik)
            $durasiMenit = floor($durasiDetik / 60);
            $durasiSisaDetik = $durasiDetik % 60;
            $durasiFormatted = $durasiMenit > 0
                ? "{$durasiMenit} menit {$durasiSisaDetik} detik"
                : "{$durasiSisaDetik} detik";
        } else {
            $durasiFormatted = 'Tidak tersedia';
        }

        // ============================================
        // KEMBALIKAN DATA KE VIEW
        // ============================================
        return view('siswa.lesson_interaktif.hasil', [
            'lessonFlow' => $lessonFlow,
            'jawabanSiswa' => $jawabanSiswa,
            'totalSoal' => $totalSoal,
            'totalJawaban' => $totalJawaban,
            'totalBenar' => $totalBenar,
            'totalSalah' => $totalSalah,
            'totalPoin' => $totalPoin,
            'maxPoin' => $maxPoin,
            'persentase' => $skor,

            // Badge data untuk achievement system
            'badge' => $badge,                    // 'gold', 'silver', atau 'bronze'
            'badgeLabel' => $badgeLabel,          // 'Gold', 'Silver', 'Bronze'
            'badgeMessage' => $badgeMessage,      // Pesan motivasi
            'badgeIcon' => $badgeIcon,            // Emoji medal
            'badgeColor' => $badgeColor,          // Bootstrap color class

            // Duration info
            'durasi' => $durasiFormatted,         // "15 menit 30 detik"
        ]);
    }

    // ✅ Phase 4: Badge System + Hasil Akhir complete

    /**
     * =====================================================
     * METHOD UNTUK REKAP NILAI SISWA (GURU)
     * =====================================================
     */

    /**
     * Halaman rekap nilai siswa untuk guru
     *
     * Menampilkan daftar semua siswa yang sudah mengerjakan
     * lesson flow dengan nilai dan status penyelesaian.
     *
     * Route: GET /rekap-nilai-siswa
     * View: rekap_nilai.index
     *
     * @return \Illuminate\View\View
     */
    public function rekapNilaiSiswa()
    {
        // Ambil semua lesson flow yang dibuat guru ini
        $lessonFlowIds = LessonFlow::where('dibuat_oleh', Auth::id())->pluck('id');

        // Ambil semua jawaban dari lesson flow guru ini
        // Group by siswa dan lesson flow untuk mendapat rekap per siswa per lesson
        $rekapData = \App\Models\LessonJawaban::whereHas('lessonItem', function($query) use ($lessonFlowIds) {
                $query->whereIn('id_lesson_flow', $lessonFlowIds);
            })
            ->with(['siswa', 'lessonItem.lessonFlow'])
            ->get()
            ->groupBy(function($item) {
                // Group by kombinasi id_siswa dan id_lesson_flow
                return $item->lessonItem->id_lesson_flow . '_' . $item->id_siswa;
            })
            ->map(function($group) {
                $firstItem = $group->first();
                $lessonFlow = $firstItem->lessonItem->lessonFlow;
                $siswa = $firstItem->siswa;

                // Hitung statistik
                $totalSoal = $lessonFlow->items()->soalSaja()->count();
                $totalJawaban = $group->count();
                $totalBenar = $group->where('benar_salah', true)->count();
                $totalPoin = $group->sum('poin_didapat');
                $maxPoin = $lessonFlow->total_poin;
                $persentase = $maxPoin > 0 ? round(($totalPoin / $maxPoin) * 100, 2) : 0;

                // Status: Selesai jika semua soal dijawab
                $status = $totalJawaban >= $totalSoal ? 'Selesai' : 'Belum Selesai';

                return [
                    'id_siswa' => $siswa->id,
                    'nama_siswa' => $siswa->nama_lengkap,
                    'email_siswa' => $siswa->email,
                    'id_lesson_flow' => $lessonFlow->id,
                    'judul_lesson' => $lessonFlow->judul_materi,
                    'total_soal' => $totalSoal,
                    'total_jawaban' => $totalJawaban,
                    'total_benar' => $totalBenar,
                    'total_poin' => $totalPoin,
                    'max_poin' => $maxPoin,
                    'persentase' => $persentase,
                    'status' => $status,
                    'terakhir_dikerjakan' => $group->max('waktu_selesai'),
                ];
            })
            ->sortByDesc('terakhir_dikerjakan')
            ->values();

        return view('rekap_nilai.index', [
            'rekapData' => $rekapData,
        ]);
    }

    /**
     * Halaman detail jawaban siswa per lesson
     *
     * Menampilkan detail semua jawaban siswa untuk satu lesson flow,
     * termasuk soal dan penanda benar/salah.
     *
     * Route: GET /rekap-nilai-siswa/{idSiswa}/{idLessonFlow}
     * View: rekap_nilai.detail
     *
     * @param  int  $idSiswa
     * @param  int  $idLessonFlow
     * @return \Illuminate\View\View
     */
    public function detailJawabanSiswa($idSiswa, $idLessonFlow)
    {
        // Validasi lesson flow milik guru yang login
        $lessonFlow = LessonFlow::where('id', $idLessonFlow)
            ->where('dibuat_oleh', Auth::id())
            ->with(['items' => function($query) {
                $query->orderBy('urutan', 'asc');
            }])
            ->firstOrFail();

        // Ambil data siswa
        $siswa = \App\Models\User::findOrFail($idSiswa);

        // Ambil semua jawaban siswa untuk lesson ini
        $jawabanSiswa = \App\Models\LessonJawaban::where('id_siswa', $idSiswa)
            ->whereIn('id_lesson_item', $lessonFlow->items->pluck('id'))
            ->with('lessonItem')
            ->get()
            ->keyBy('id_lesson_item');

        // Hitung statistik
        $totalSoal = $lessonFlow->items()->soalSaja()->count();
        $totalJawaban = $jawabanSiswa->count();
        $totalBenar = $jawabanSiswa->where('benar_salah', true)->count();
        $totalSalah = $jawabanSiswa->where('benar_salah', false)->count();
        $totalPoin = $jawabanSiswa->sum('poin_didapat');
        $maxPoin = $lessonFlow->total_poin;
        $persentase = $maxPoin > 0 ? round(($totalPoin / $maxPoin) * 100, 2) : 0;

        return view('rekap_nilai.detail', [
            'lessonFlow' => $lessonFlow,
            'siswa' => $siswa,
            'jawabanSiswa' => $jawabanSiswa,
            'totalSoal' => $totalSoal,
            'totalJawaban' => $totalJawaban,
            'totalBenar' => $totalBenar,
            'totalSalah' => $totalSalah,
            'totalPoin' => $totalPoin,
            'maxPoin' => $maxPoin,
            'persentase' => $persentase,
        ]);
    }
}
