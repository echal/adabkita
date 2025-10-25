# 📊 Analytics & Rekap Nilai - Quick Start Guide

## 🚀 Cara Menggunakan Fitur Analytics

### 📌 Akses Halaman Analytics

**Step 1: Login sebagai Guru**
```
1. Buka browser, akses aplikasi
2. Login dengan akun guru
3. Dashboard Guru akan muncul
```

**Step 2: Navigasi ke Analytics**
```
Cara 1: Dari Dashboard
  → Klik menu "Lesson Flow"
  → Klik tab "Analytics" atau button "Lihat Analytics"

Cara 2: URL Langsung
  → Ketik: http://localhost/deeplearning/public/guru/lesson-analytics
  → Enter
```

---

## 📊 Halaman Utama Analytics

### Apa yang Ditampilkan?

#### 1️⃣ **Statistik Cards** (4 Cards)
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ Total       │ Siswa       │ Rata-Rata   │ Total       │
│ Lesson      │ Aktif       │ Nilai       │ Penyelesaian│
│ 5           │ 25          │ 85.50%      │ 75          │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

**Penjelasan**:
- **Total Lesson**: Jumlah lesson yang sudah dipublikasi
- **Siswa Aktif**: Siswa yang telah menyelesaikan minimal 1 lesson
- **Rata-Rata Nilai**: Rata-rata keseluruhan dari semua lesson
- **Total Penyelesaian**: Total kali siswa menyelesaikan lesson

---

#### 2️⃣ **Grafik Bar Chart - Rata-Rata Nilai per Lesson**
```
Lesson A  ████████████████████░░  85%
Lesson B  ██████████████████████  92%
Lesson C  ████████████░░░░░░░░░  65%
Lesson D  ███████████████████░░  88%
```

**Cara Membaca**:
- Bar lebih panjang = Nilai rata-rata lebih tinggi
- Warna hijau/cyan = Performa baik
- Hover mouse ke bar untuk lihat detail:
  - Rata-rata nilai
  - Total siswa
  - Distribusi badge (Gold, Silver, Bronze)

**Tips**:
- Jika bar pendek (<75%), review materi lesson tersebut
- Bandingkan antar lesson untuk lihat mana yang paling mudah/sulit

---

#### 3️⃣ **Grafik Stacked Bar - Distribusi Badge**
```
Lesson A  [Gold██][Silver██][Bronze█]
Lesson B  [Gold████][Silver█]
Lesson C  [Bronze████][Silver█]
Lesson D  [Gold███][Silver██][Bronze█]
```

**Cara Membaca**:
- **Gold** (kuning): Siswa dengan skor ≥90%
- **Silver** (abu-abu): Siswa dengan skor 75-89%
- **Bronze** (merah): Siswa dengan skor <75%

**Tips**:
- Ideal: Mayoritas Gold dan Silver
- Banyak Bronze? → Materi perlu disederhanakan atau review

---

#### 4️⃣ **Tabel Rekap Detail Nilai**
```
┌────┬─────────┬─────────┬──────┬───────┬───────┬────────┐
│ No │ Siswa   │ Lesson  │ Skor │ Badge │ B/S/T │ Durasi │
├────┼─────────┼─────────┼──────┼───────┼───────┼────────┤
│ 1  │ Ahmad   │ Adab A  │ 95%  │ 🥇    │ 9/1/10│ 15 min │
│ 2  │ Siti    │ Adab A  │ 80%  │ 🥈    │ 8/2/10│ 18 min │
│ 3  │ Budi    │ Adab B  │ 65%  │ 🥉    │ 6/4/10│ 12 min │
└────┴─────────┴─────────┴──────┴───────┴───────┴────────┘
```

**Kolom**:
- **No**: Nomor urut
- **Siswa**: Nama siswa
- **Lesson**: Judul lesson flow
- **Skor**: Persentase nilai (warna badge)
- **Badge**: Emoji achievement
- **B/S/T**: Benar / Salah / Total soal
- **Durasi**: Waktu pengerjaan
- **Waktu Selesai**: Timestamp
- **Aksi**: Button untuk lihat detail analytics lesson

---

### 🔍 Fitur Search/Filter

**Cara Menggunakan**:
```
1. Lihat search box di atas tabel
   [🔍 Cari nama siswa atau lesson...]

2. Ketik nama siswa:
   → Contoh: "Ahmad"
   → Tabel otomatis filter, hanya tampil row Ahmad

3. Ketik nama lesson:
   → Contoh: "Adab"
   → Tabel filter, hanya tampil lesson dengan kata "Adab"

4. Real-time filtering (tidak perlu klik button)
```

---

### 📥 Export to Excel

**Cara Export**:
```
1. Klik button "Export ke Excel" (hijau, icon Excel)
2. File .xlsx otomatis terdownload
3. Nama file: Rekap_Nilai_Lesson_2025-10-17.xlsx
4. Buka dengan Microsoft Excel atau Google Sheets
```

**Isi File Excel**:
- Semua data tabel rekap
- Header kolom
- Format siap print

**Kegunaan**:
- Laporan bulanan
- Dokumentasi pembelajaran
- Arsip nilai siswa

---

## 📊 Halaman Detail Analytics Per Lesson

### Cara Akses:
```
Cara 1: Dari Tabel Rekap
  → Klik icon grafik (📊) di kolom "Aksi"

Cara 2: URL Langsung
  → http://localhost/deeplearning/public/guru/lesson-analytics/{id}
  → Replace {id} dengan ID lesson
```

---

### Apa yang Ditampilkan?

#### 1️⃣ **Statistik Lesson** (5 Cards)
```
┌────────┬─────────┬───────┬────────┬────────┐
│ Total  │ Rata²   │ 🥇    │ 🥈     │ 🥉     │
│ Siswa  │ Skor    │ Gold  │ Silver │ Bronze │
│ 25     │ 85.50%  │ 10    │ 12     │ 3      │
└────────┴─────────┴───────┴────────┴────────┘
```

---

#### 2️⃣ **Pie Chart - Distribusi Badge**
```
       ╱───────╲
      ╱         ╲
     │  Gold     │
     │  40%      │
      ╲         ╱
       ╲───────╱
      ╱         ╲
     │ Silver    │
     │  48%      │
      ╲         ╱
       └─Bronze─┘
          12%
```

**Cara Membaca**:
- Slice lebih besar = Lebih banyak siswa
- Hover untuk lihat count + persentase
- Warna: Gold (kuning), Silver (abu), Bronze (merah)

---

#### 3️⃣ **Bar Chart - Histogram Distribusi Skor**
```
90-100%  ████████  (10 siswa)
75-89%   ████████████  (12 siswa)
60-74%   ███  (3 siswa)
0-59%    █  (1 siswa)
```

**Cara Membaca**:
- Range skor di kiri
- Bar horizontal menunjukkan jumlah siswa
- Warna:
  - Hijau (90-100%): Excellent
  - Biru (75-89%): Good
  - Kuning (60-74%): Average
  - Merah (0-59%): Needs Improvement

**Tips**:
- Jika banyak siswa di range merah/kuning → Review materi
- Jika mayoritas hijau/biru → Materi sudah dikuasai

---

#### 4️⃣ **Tabel Detail Hasil Siswa**
```
┌────┬─────────┬──────┬───────┬───────┬───────┬────────┐
│ No │ Siswa   │ Skor │ Badge │ Benar │ Salah │ Durasi │
├────┼─────────┼──────┼───────┼───────┼───────┼────────┤
│ 1  │ Ahmad   │ 95%  │ 🥇    │  9    │  1    │ 15 min │
│ 2  │ Siti    │ 80%  │ 🥈    │  8    │  2    │ 18 min │
│ 3  │ Budi    │ 65%  │ 🥉    │  6    │  4    │ 12 min │
└────┴─────────┴──────┴───────┴───────┴───────┴────────┘
```

**Fokus untuk lesson ini saja**

---

## 💡 Tips Menggunakan Analytics

### Untuk Monitoring Siswa:

#### 1. **Identifikasi Siswa yang Perlu Bantuan**
```
Cara:
1. Buka tabel rekap
2. Search nama siswa
3. Lihat skor semua lesson
4. Jika banyak Bronze → Remedial diperlukan
```

#### 2. **Bandingkan Performa Antar Lesson**
```
Cara:
1. Lihat grafik bar chart rata-rata nilai
2. Identifikasi lesson dengan bar terendah
3. Review materi lesson tersebut
4. Pertimbangkan revisi soal/konten
```

#### 3. **Monitor Distribusi Badge**
```
Ideal Target:
- Gold: 40-50% (siswa excellent)
- Silver: 40-50% (siswa good)
- Bronze: 0-10% (siswa perlu improve)

Jika Bronze >30%:
→ Materi terlalu sulit
→ Perlu revisi atau tambah penjelasan
```

---

### Untuk Laporan:

#### 1. **Laporan Bulanan**
```
1. Export ke Excel di akhir bulan
2. Rename file: Laporan_Oktober_2025.xlsx
3. Simpan di folder Laporan
4. Gunakan untuk rapat evaluasi
```

#### 2. **Dokumentasi**
```
- Screenshot grafik untuk presentasi
- Export Excel untuk arsip
- Print tabel untuk dokumentasi fisik
```

---

## 🎯 Interpretasi Data

### Skor Rata-Rata:

| Range      | Status             | Aksi                          |
|------------|--------------------|------------------------------ |
| 90-100%    | 🟢 Excellent       | Maintain quality              |
| 75-89%     | 🔵 Good            | Minor improvement             |
| 60-74%     | 🟡 Average         | Review materi                 |
| 0-59%      | 🔴 Needs Improve   | **Revisi urgent**             |

---

### Distribusi Badge:

**Skenario 1: Ideal**
```
Gold:   50% (10 siswa)
Silver: 40% (8 siswa)
Bronze: 10% (2 siswa)

✅ Materi balanced, siswa paham
✅ Teaching method efektif
```

**Skenario 2: Terlalu Mudah**
```
Gold:   90% (18 siswa)
Silver: 10% (2 siswa)
Bronze: 0%

⚠️ Materi terlalu mudah
→ Pertimbangkan soal lebih challenging
```

**Skenario 3: Terlalu Sulit**
```
Gold:   5% (1 siswa)
Silver: 20% (4 siswa)
Bronze: 75% (15 siswa)

❌ Materi terlalu sulit
→ Simplify materi
→ Tambah penjelasan/video
→ Kurangi jumlah soal sulit
```

---

### Durasi Pengerjaan:

| Durasi        | Interpretasi                         |
|---------------|--------------------------------------|
| <5 menit      | Siswa terburu-buru / materi mudah    |
| 5-15 menit    | ✅ Normal                            |
| 15-30 menit   | Materi challenging tapi manageable   |
| >30 menit     | ⚠️ Terlalu banyak soal / terlalu sulit|

**Tips**:
- Jika mayoritas <5 menit + skor tinggi → Tambah soal
- Jika mayoritas >30 menit + skor rendah → Kurangi soal / simplify

---

## 🔧 Troubleshooting

### ❌ Grafik Tidak Muncul

**Masalah**: Grafik hanya menampilkan area kosong

**Solusi**:
```
1. Refresh browser (Ctrl + F5)
2. Buka DevTools (F12)
   → Console tab
   → Cek error Chart.js
3. Pastikan koneksi internet aktif (CDN Chart.js)
4. Clear cache browser
```

---

### ❌ Data Tidak Muncul / Kosong

**Masalah**: "Belum ada data analytics"

**Penyebab**:
- Belum ada lesson yang dipublikasi
- Belum ada siswa yang menyelesaikan lesson

**Solusi**:
```
1. Cek lesson status: Harus "Published"
2. Minta siswa kerjakan lesson sampai selesai
3. Verifikasi di tabel lesson_progress:
   → status = 'selesai'
4. Refresh halaman analytics
```

---

### ❌ Search Tidak Berfungsi

**Masalah**: Ketik di search box, tabel tidak filter

**Solusi**:
```
1. Refresh halaman
2. Clear browser cache
3. Cek console untuk error JavaScript
4. Gunakan browser modern (Chrome, Firefox, Edge)
```

---

### ❌ Export Excel Error

**Masalah**: Klik "Export ke Excel" tidak ada yang terjadi

**Solusi**:
```
1. Pastikan browser allow download
2. Cek popup blocker (disable jika perlu)
3. Buka DevTools → Console, cek error
4. Pastikan koneksi internet aktif (CDN SheetJS)
5. Coba browser lain
```

---

## 📱 Responsive Design

### Desktop (>1200px):
- Grafik lebar penuh
- Tabel 9 kolom visible
- Best experience

### Tablet (768-1199px):
- Grafik auto-resize
- Tabel scroll horizontal
- Cards 2 kolom

### Mobile (320-767px):
- Grafik vertical
- Tabel full scroll
- Cards 1 kolom
- Touch-friendly

---

## 🎓 Best Practices

### 1. **Monitor Berkala**
```
Frekuensi: Minimal 1x seminggu
Waktu: Setelah siswa selesai mengerjakan lesson
Aksi: Review grafik + identifikasi issue
```

### 2. **Dokumentasi**
```
- Export Excel setiap akhir bulan
- Screenshot grafik untuk presentasi
- Simpan di folder terorganisir
```

### 3. **Tindak Lanjut**
```
Jika Nilai Rendah:
  → Remedial untuk siswa
  → Review materi lesson
  → Revisi soal jika perlu

Jika Nilai Tinggi:
  → Berikan apresiasi siswa
  → Maintain quality
  → Tambah challenge jika terlalu mudah
```

### 4. **Gunakan Detail Analytics**
```
- Jangan hanya lihat overview
- Klik detail per lesson untuk deep dive
- Analisis distribusi skor untuk insight
```

---

## 📊 Contoh Use Case

### Use Case 1: Identifikasi Lesson Sulit
```
Problem:
  Lesson "Adab Makan" rata-rata nilai 55%

Aksi:
1. Klik detail analytics lesson tersebut
2. Lihat histogram distribusi skor
   → Mayoritas di range 0-59%
3. Lihat tabel detail siswa
   → Banyak siswa salah di soal tertentu
4. Review soal yang sering salah
5. Simplify soal atau tambah penjelasan
6. Republish lesson dengan revisi
```

---

### Use Case 2: Monitor Siswa Tertinggal
```
Problem:
  Ada siswa yang selalu dapat Bronze

Aksi:
1. Search nama siswa di tabel rekap
2. Lihat semua lesson yang dikerjakan
   → Semua Bronze badge
3. Hubungi siswa untuk remedial
4. Berikan materi tambahan/bimbingan
5. Monitor improvement di analytics berikutnya
```

---

### Use Case 3: Laporan Bulanan
```
Goal:
  Buat laporan pembelajaran bulan Oktober

Aksi:
1. Akses analytics di akhir bulan
2. Screenshot:
   - Grafik rata-rata nilai
   - Grafik distribusi badge
3. Export Excel tabel rekap
4. Buat PowerPoint presentation:
   - Slide 1: Overview statistik
   - Slide 2: Grafik rata-rata nilai
   - Slide 3: Distribusi badge
   - Slide 4: Top performers
   - Slide 5: Students need support
5. Presentasi di rapat evaluasi
```

---

## ✅ Quick Checklist

### Sebelum Menggunakan Analytics:
- [x] Sudah login sebagai guru
- [x] Sudah ada lesson flow published
- [x] Sudah ada siswa yang menyelesaikan lesson
- [x] Browser modern dengan JavaScript enabled
- [x] Koneksi internet aktif (untuk CDN)

### Saat Menggunakan Analytics:
- [ ] Lihat overview statistik cards
- [ ] Analisis grafik rata-rata nilai
- [ ] Cek distribusi badge
- [ ] Review tabel rekap detail
- [ ] Gunakan search untuk filter data
- [ ] Export Excel untuk dokumentasi
- [ ] Klik detail per lesson untuk deep dive

### Tindak Lanjut:
- [ ] Identifikasi lesson dengan nilai rendah
- [ ] Identifikasi siswa yang perlu bantuan
- [ ] Review dan revisi materi jika perlu
- [ ] Berikan remedial untuk siswa tertinggal
- [ ] Apresiasi siswa dengan performa tinggi
- [ ] Dokumentasi untuk laporan

---

## 🎉 Selamat Menggunakan Analytics!

Analytics & Rekap Nilai membantu Anda:
✅ Monitor performa siswa real-time
✅ Identifikasi masalah pembelajaran
✅ Dokumentasi nilai otomatis
✅ Visualisasi data yang mudah dipahami
✅ Export data untuk laporan

**Happy Teaching!** 📚✨

---

**Created**: 2025-10-17
**Phase**: 5 - Analytics & Rekap Nilai Guru
**Status**: Production Ready ✅
