# Badge Images untuk Lesson Flow Interaktif

## 📋 Deskripsi
Folder ini berisi gambar badge achievement yang akan ditampilkan di halaman hasil lesson flow.

## 🎨 Badge yang Dibutuhkan

Anda perlu menyediakan 3 file gambar badge:

1. **`gold.png`** - Badge emas (untuk skor ≥90%)
2. **`silver.png`** - Badge perak (untuk skor ≥75%)
3. **`bronze.png`** - Badge perunggu (untuk skor <75%)

## 📐 Spesifikasi Gambar

- **Format**: PNG dengan background transparan
- **Dimensi**: 150x150 px atau 300x300 px (HD)
- **Ukuran File**: Maksimal 100 KB per file
- **Style**: Medal/trophy dengan warna sesuai nama badge

## 🌐 Sumber Gambar Badge Gratis

### Opsi 1: Download dari Flaticon (Recommended)
1. Kunjungi: https://www.flaticon.com/
2. Cari keyword: "gold medal", "silver medal", "bronze medal"
3. Pilih icon dengan lisensi gratis (Free)
4. Download dalam format PNG 512px
5. Rename file menjadi: `gold.png`, `silver.png`, `bronze.png`
6. Copy ke folder ini

### Opsi 2: Download dari Icons8
1. Kunjungi: https://icons8.com/icons/set/medal
2. Pilih medal dengan warna gold, silver, bronze
3. Download gratis (max 100px) atau berbayar untuk HD
4. Rename dan copy ke folder ini

### Opsi 3: Generate dengan AI (Canva/Figma)
1. Buat desain medal 150x150px
2. Gunakan warna:
   - Gold: #FFD700 (kuning emas)
   - Silver: #C0C0C0 (abu-abu metalik)
   - Bronze: #CD7F32 (coklat keemasan)
3. Export sebagai PNG dengan background transparan

### Opsi 4: Gunakan Emoji/Icon (Fallback)
Jika tidak ada gambar, sistem akan otomatis menampilkan emoji:
- 🥇 Gold
- 🥈 Silver
- 🥉 Bronze

## 📂 Struktur File yang Benar

```
public/
  └── badges/
      ├── gold.png      ✅ (diperlukan)
      ├── silver.png    ✅ (diperlukan)
      ├── bronze.png    ✅ (diperlukan)
      └── README.md     (file ini)
```

## 🔧 Testing

Setelah menambahkan gambar badge:

1. Akses halaman hasil lesson: `/siswa/lesson-interaktif/{id}/hasil`
2. Badge akan muncul di:
   - Popup SweetAlert2 saat halaman load
   - Card Achievement di halaman hasil
3. Jika gambar tidak ditemukan, sistem akan fallback ke emoji

## ✅ Checklist

- [ ] Download 3 gambar badge (gold, silver, bronze)
- [ ] Rename file sesuai format: `gold.png`, `silver.png`, `bronze.png`
- [ ] Copy file ke folder `public/badges/`
- [ ] Test dengan mengakses halaman hasil lesson
- [ ] Verifikasi badge muncul di popup dan card achievement

---

**Last Updated**: 2025-10-17
**Phase**: 4 - Badge System Implementation
