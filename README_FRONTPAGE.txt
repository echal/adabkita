================================================================================
                    PANDUAN SINGKAT FRONTPAGE ADABKITA
================================================================================

📁 FILE YANG DIBUAT:
-------------------
1. resources/views/layouts/front.blade.php        (Layout template)
2. resources/views/frontpage.blade.php            (Halaman utama)
3. routes/web.php                                 (Sudah diupdate)
4. DOKUMENTASI_FRONTPAGE.md                       (Dokumentasi lengkap)

================================================================================

🎯 CARA TESTING:
----------------

METODE 1 - PHP Artisan:
    1. Buka Command Prompt/Terminal
    2. cd C:\xampp\htdocs\deeplearning
    3. php artisan serve
    4. Buka browser: http://localhost:8000

METODE 2 - XAMPP:
    1. Jalankan Apache di XAMPP
    2. Buka browser: http://localhost/deeplearning/public

================================================================================

✏️ EDIT CEPAT (Yang Paling Sering Diubah):
-------------------------------------------

1. GANTI VIDEO YOUTUBE:
   File: resources/views/frontpage.blade.php
   Baris: ~241
   Cari: src="https://www.youtube.com/embed/dQw4w9WgXcQ"
   Ganti: dQw4w9WgXcQ dengan ID video YouTube yang baru

2. UBAH JUDUL HERO:
   File: resources/views/frontpage.blade.php
   Baris: ~72
   Cari: <h1 class="hero-title">
   Edit teksnya sesuai kebutuhan

3. UBAH EMAIL KONTAK:
   File: resources/views/frontpage.blade.php
   Cari: info@adabkita.gaspul.com
   Ganti semua (ada 3 tempat)

4. UBAH WARNA TEMA:
   File: resources/views/layouts/front.blade.php
   Cari: #667eea dan #764ba2
   Ganti dengan kode warna yang diinginkan

================================================================================

🧹 CLEAR CACHE (Jika perubahan tidak muncul):
----------------------------------------------
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear

================================================================================

📖 DOKUMENTASI LENGKAP:
------------------------
Baca file: DOKUMENTASI_FRONTPAGE.md
(Berisi penjelasan detail cara mengubah setiap bagian frontpage)

================================================================================

🎨 STRUKTUR FRONTPAGE:
----------------------
1. Navbar (Logo + Menu + Tombol Login)
2. Hero Section (Judul besar + Deskripsi + CTA buttons)
3. Keunggulan (3 fitur: Deep Learning, Evaluasi, Interaktif)
4. Tentang AdabKita
5. Video Penjelasan (Embed YouTube)
6. Call to Action (Ajakan login)
7. Footer (Info kontak + Copyright)

================================================================================

⚠️ CATATAN PENTING:
-------------------
✓ Selalu backup file sebelum edit
✓ Test di localhost dulu sebelum upload ke server
✓ Gunakan text editor (VS Code, Notepad++, bukan MS Word!)
✓ Pastikan koneksi internet aktif (untuk Bootstrap CDN)
✓ Jangan edit langsung di server production

================================================================================

📞 BUTUH BANTUAN?
-----------------
Baca dokumentasi lengkap di: DOKUMENTASI_FRONTPAGE.md
Atau hubungi tim developer GASPUL

================================================================================
© 2025 AdabKita - GASPUL
================================================================================
