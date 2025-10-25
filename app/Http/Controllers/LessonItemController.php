<?php

namespace App\Http\Controllers;

use App\Models\LessonFlow;
use App\Models\LessonItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * =====================================================
 * CONTROLLER LESSON ITEM
 * =====================================================
 * Controller untuk mengelola Item dalam Lesson Flow
 *
 * Fitur:
 * - Tambah item (video, gambar, soal PG, soal gambar, isian)
 * - Edit item
 * - Hapus item
 * - Upload gambar untuk item
 *
 * @package App\Http\Controllers
 * @author System
 * @created 2025-10-15
 * =====================================================
 */
class LessonItemController extends Controller
{
    /**
     * Simpan item baru ke lesson flow
     *
     * Method ini menerima data dari form tambah item
     * dan menyimpannya ke database.
     *
     * Route: POST /lesson-flow/{lessonFlowId}/items
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonFlowId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $lessonFlowId)
    {
        // Pastikan lesson flow milik guru yang login
        $lessonFlow = LessonFlow::where('id', $lessonFlowId)
            ->where('dibuat_oleh', Auth::id())
            ->firstOrFail();

        // Validasi berdasarkan tipe item
        $rules = [
            'tipe_item' => 'required|in:video,gambar,soal_pg,soal_gambar,isian',
        ];

        $messages = [
            'tipe_item.required' => 'Tipe item wajib dipilih',
            'tipe_item.in' => 'Tipe item tidak valid',
        ];

        // Validasi tambahan berdasarkan tipe
        $tipeItem = $request->input('tipe_item');

        switch ($tipeItem) {
            case 'video':
                $rules['konten'] = 'required|url';
                $messages['konten.required'] = 'Link YouTube wajib diisi';
                $messages['konten.url'] = 'Link YouTube tidak valid';
                break;

            case 'gambar':
                $rules['konten'] = 'required';
                $rules['gambar_file'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120';
                $messages['konten.required'] = 'URL gambar wajib diisi jika tidak upload';
                $messages['gambar_file.image'] = 'File harus berupa gambar';
                $messages['gambar_file.max'] = 'Ukuran gambar maksimal 5MB';
                break;

            case 'soal_pg':
                $rules['konten'] = 'required|string';
                $rules['opsi_a'] = 'required|string|max:500';
                $rules['opsi_b'] = 'required|string|max:500';
                $rules['opsi_c'] = 'required|string|max:500';
                $rules['opsi_d'] = 'required|string|max:500';
                $rules['jawaban_benar'] = 'required|in:a,b,c,d';
                $rules['poin'] = 'nullable|integer|min:1';
                $rules['penjelasan'] = 'nullable|string';

                $messages['konten.required'] = 'Pertanyaan wajib diisi';
                $messages['opsi_a.required'] = 'Opsi A wajib diisi';
                $messages['opsi_b.required'] = 'Opsi B wajib diisi';
                $messages['opsi_c.required'] = 'Opsi C wajib diisi';
                $messages['opsi_d.required'] = 'Opsi D wajib diisi';
                $messages['jawaban_benar.required'] = 'Jawaban benar wajib dipilih';
                $messages['jawaban_benar.in'] = 'Jawaban benar harus A, B, C, atau D';
                break;

            case 'soal_gambar':
                $rules['konten'] = 'required|string';
                $rules['opsi_a'] = 'nullable|string|max:500';
                $rules['opsi_b'] = 'nullable|string|max:500';
                $rules['opsi_c'] = 'nullable|string|max:500';
                $rules['opsi_d'] = 'nullable|string|max:500';
                $rules['gambar_opsi_a'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
                $rules['gambar_opsi_b'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
                $rules['gambar_opsi_c'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
                $rules['gambar_opsi_d'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
                $rules['jawaban_benar'] = 'required|in:a,b,c,d';
                $rules['poin'] = 'nullable|integer|min:1';
                $rules['penjelasan'] = 'nullable|string';

                $messages['konten.required'] = 'Pertanyaan wajib diisi';
                $messages['gambar_opsi_a.required'] = 'Gambar opsi A wajib diupload';
                $messages['gambar_opsi_b.required'] = 'Gambar opsi B wajib diupload';
                $messages['gambar_opsi_c.required'] = 'Gambar opsi C wajib diupload';
                $messages['gambar_opsi_d.required'] = 'Gambar opsi D wajib diupload';
                $messages['jawaban_benar.required'] = 'Jawaban benar wajib dipilih';
                break;

            case 'isian':
                $rules['konten'] = 'required|string';
                $rules['jawaban_benar'] = 'required|string|max:255';
                $rules['poin'] = 'nullable|integer|min:1';
                $rules['penjelasan'] = 'nullable|string';

                $messages['konten.required'] = 'Pertanyaan wajib diisi';
                $messages['jawaban_benar.required'] = 'Jawaban benar wajib diisi';
                break;
        }

        // Jalankan validasi
        $validated = $request->validate($rules, $messages);

        // Tentukan urutan item (tambahkan di akhir)
        $maxUrutan = $lessonFlow->items()->max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;

        // Set poin default jika tidak diisi
        if (in_array($tipeItem, ['soal_pg', 'soal_gambar', 'isian'])) {
            $validated['poin'] = $validated['poin'] ?? 10;
        }

        // Handle upload gambar
        if ($tipeItem === 'gambar' && $request->hasFile('gambar_file')) {
            $file = $request->file('gambar_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/lesson_gambar', $fileName);
            $validated['konten'] = $fileName;
        }

        // Handle upload gambar opsi untuk soal_gambar
        if ($tipeItem === 'soal_gambar') {
            foreach (['a', 'b', 'c', 'd'] as $opsi) {
                $fieldName = 'gambar_opsi_' . $opsi;
                if ($request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $fileName = time() . '_opsi' . $opsi . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/lesson_gambar', $fileName);
                    $validated[$fieldName] = $fileName;
                }
            }
        }

        // Set foreign key
        $validated['id_lesson_flow'] = $lessonFlow->id;

        // Simpan ke database
        LessonItem::create($validated);

        // Redirect kembali dengan pesan sukses
        return redirect()
            ->route('lesson-flow.edit', $lessonFlow->id)
            ->with('success', 'Item berhasil ditambahkan ke lesson flow!');
    }

    /**
     * Tampilkan form edit item
     *
     * Route: GET /lesson-items/{id}/edit
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Ambil item
        $item = LessonItem::findOrFail($id);

        // Pastikan lesson flow milik guru yang login
        $lessonFlow = $item->lessonFlow;
        if ($lessonFlow->dibuat_oleh !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Kirim data ke view
        return view('lesson_items.edit', compact('item', 'lessonFlow'));
    }

    /**
     * Update item dalam lesson flow
     *
     * Route: PUT /lesson-items/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Ambil item
        $item = LessonItem::findOrFail($id);

        // Pastikan lesson flow milik guru yang login
        $lessonFlow = $item->lessonFlow;
        if ($lessonFlow->dibuat_oleh !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi (sama seperti store)
        $rules = [
            'tipe_item' => 'required|in:video,gambar,soal_pg,soal_gambar,isian',
        ];

        $messages = [
            'tipe_item.required' => 'Tipe item wajib dipilih',
        ];

        $tipeItem = $request->input('tipe_item');

        // Tambahkan rules berdasarkan tipe (sama seperti store method)
        // Untuk singkat, saya tidak ulangi semua rules di sini
        // Dalam produksi, Anda bisa extract validasi ke method terpisah

        switch ($tipeItem) {
            case 'video':
                $rules['konten'] = 'required|url';
                break;

            case 'gambar':
                $rules['konten'] = 'nullable';
                $rules['gambar_file'] = 'nullable|image|max:5120';
                break;

            case 'soal_pg':
                $rules['konten'] = 'required|string';
                $rules['opsi_a'] = 'required|string';
                $rules['opsi_b'] = 'required|string';
                $rules['opsi_c'] = 'required|string';
                $rules['opsi_d'] = 'required|string';
                $rules['jawaban_benar'] = 'required|in:a,b,c,d';
                $rules['poin'] = 'nullable|integer';
                $rules['penjelasan'] = 'nullable|string';
                break;

            case 'soal_gambar':
                $rules['konten'] = 'required|string';
                $rules['opsi_a'] = 'nullable|string|max:500';
                $rules['opsi_b'] = 'nullable|string|max:500';
                $rules['opsi_c'] = 'nullable|string|max:500';
                $rules['opsi_d'] = 'nullable|string|max:500';
                $rules['gambar_opsi_a'] = 'nullable|image|max:3072';
                $rules['gambar_opsi_b'] = 'nullable|image|max:3072';
                $rules['gambar_opsi_c'] = 'nullable|image|max:3072';
                $rules['gambar_opsi_d'] = 'nullable|image|max:3072';
                $rules['jawaban_benar'] = 'required|in:a,b,c,d';
                $rules['poin'] = 'nullable|integer';
                $rules['penjelasan'] = 'nullable|string';
                break;

            case 'isian':
                $rules['konten'] = 'required|string';
                $rules['jawaban_benar'] = 'required|string';
                $rules['poin'] = 'nullable|integer';
                $rules['penjelasan'] = 'nullable|string';
                break;
        }

        $validated = $request->validate($rules, $messages);

        // Handle upload gambar baru (jika ada)
        if ($tipeItem === 'gambar') {
            if ($request->hasFile('gambar_file')) {
                // Hapus gambar lama jika ada
                if ($item->konten && !filter_var($item->konten, FILTER_VALIDATE_URL)) {
                    Storage::delete('public/lesson_gambar/' . $item->konten);
                }

                // Upload gambar baru
                $file = $request->file('gambar_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/lesson_gambar', $fileName);
                $validated['konten'] = $fileName;
            } elseif (!$request->input('konten')) {
                // Jika tidak ada URL baru dan tidak ada upload, pertahankan gambar lama
                $validated['konten'] = $item->konten;
            }
        }

        // Handle upload gambar opsi baru
        if ($tipeItem === 'soal_gambar') {
            foreach (['a', 'b', 'c', 'd'] as $opsi) {
                $fieldName = 'gambar_opsi_' . $opsi;
                if ($request->hasFile($fieldName)) {
                    // Hapus gambar lama
                    $oldField = 'gambar_opsi_' . $opsi;
                    if ($item->$oldField) {
                        Storage::delete('public/lesson_gambar/' . $item->$oldField);
                    }

                    // Upload gambar baru
                    $file = $request->file($fieldName);
                    $fileName = time() . '_opsi' . $opsi . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/lesson_gambar', $fileName);
                    $validated[$fieldName] = $fileName;
                } else {
                    // Pertahankan gambar lama jika tidak ada upload baru
                    $validated[$fieldName] = $item->$fieldName;
                }
            }
        }

        // Update item
        $item->update($validated);

        // Redirect dengan pesan sukses
        return redirect()
            ->route('lesson-flow.edit', $lessonFlow->id)
            ->with('success', 'Item berhasil diperbarui!');
    }

    /**
     * Hapus item dari lesson flow
     *
     * Route: DELETE /lesson-items/{id}
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Ambil item
        $item = LessonItem::findOrFail($id);

        // Pastikan lesson flow milik guru yang login
        $lessonFlow = $item->lessonFlow;
        if ($lessonFlow->dibuat_oleh !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Hapus file gambar jika ada
        if ($item->tipe_item === 'gambar' && $item->konten) {
            if (!filter_var($item->konten, FILTER_VALIDATE_URL)) {
                Storage::delete('public/lesson_gambar/' . $item->konten);
            }
        }

        // Hapus gambar opsi jika soal_gambar
        if ($item->tipe_item === 'soal_gambar') {
            foreach (['a', 'b', 'c', 'd'] as $opsi) {
                $fieldName = 'gambar_opsi_' . $opsi;
                if ($item->$fieldName) {
                    Storage::delete('public/lesson_gambar/' . $item->$fieldName);
                }
            }
        }

        // Hapus item
        $item->delete();

        // Redirect dengan pesan sukses
        return redirect()
            ->route('lesson-flow.edit', $lessonFlow->id)
            ->with('success', 'Item berhasil dihapus!');
    }
}
