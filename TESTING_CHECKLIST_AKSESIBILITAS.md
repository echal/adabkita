# ✅ Testing Checklist - Fitur Aksesibilitas AdabKita

## 🚀 Quick Start

1. Buka browser: **http://127.0.0.1:8000**
2. Lihat 2 floating button:
   - **Kanan bawah:** Aksesibilitas (ungu-pink, ikon ♿)
   - **Kiri bawah:** Text-to-Speech (hijau, ikon 🔊)

---

## 📋 Testing Steps

### 1. Font Size Adjustment

**Langkah:**
- [ ] Klik tombol aksesibilitas (♿) kanan bawah
- [ ] Panel terbuka dengan smooth animation
- [ ] Klik tombol **+** (plus) 3x
- [ ] Badge menampilkan "130%"
- [ ] Semua teks di halaman membesar
- [ ] Klik tombol **-** (minus) 5x
- [ ] Notifikasi warning muncul: "Ukuran minimum tercapai (80%)"
- [ ] Refresh halaman (F5)
- [ ] Ukuran teks tetap 80% (tersimpan)

**Expected Result:** ✅ Font size berubah dinamis 80%-150%, tersimpan di localStorage

---

### 2. High Contrast Mode

**Langkah:**
- [ ] Klik toggle "Kontras Tinggi"
- [ ] Halaman berubah menjadi dark mode (background hitam)
- [ ] Teks menjadi putih
- [ ] Card/section menjadi dark gray
- [ ] Link berwarna light blue
- [ ] Notifikasi: "Mode kontras tinggi aktif"
- [ ] Klik toggle lagi untuk matikan
- [ ] Halaman kembali normal
- [ ] Refresh halaman
- [ ] Mode masih mati (sesuai setting terakhir)

**Expected Result:** ✅ High contrast mode ON/OFF, tersimpan di localStorage

---

### 3. Text Spacing

**Langkah:**
- [ ] Klik toggle "Spasi Teks"
- [ ] Jarak antar kata dan baris melebar
- [ ] Line height bertambah
- [ ] Paragraf lebih mudah dibaca
- [ ] Notifikasi: "Spasi teks diperlebar"
- [ ] Klik toggle lagi untuk matikan
- [ ] Spasi kembali normal
- [ ] Refresh halaman
- [ ] Setting tetap tersimpan

**Expected Result:** ✅ Text spacing ON/OFF, tersimpan di localStorage

---

### 4. Text-to-Speech (Baca Halaman)

**Langkah:**
- [ ] Klik tombol hijau (🔊) di kiri bawah
- [ ] Notifikasi: "Memulai pembacaan halaman..."
- [ ] Browser mulai membaca konten dengan suara
- [ ] Tombol berubah warna menjadi MERAH
- [ ] Icon berubah menjadi pause (⏸️)
- [ ] Text berubah menjadi "Hentikan"
- [ ] Animasi pulse merah aktif
- [ ] Klik tombol lagi
- [ ] Pembacaan berhenti
- [ ] Tombol kembali hijau dengan icon volume (🔊)
- [ ] Notifikasi: "Pembacaan dihentikan"

**Expected Result:** ✅ TTS berfungsi, hanya baca konten utama (skip navbar/footer)

---

### 5. Reset ke Default

**Langkah:**
- [ ] Aktifkan semua fitur:
  - Font size → 140%
  - High contrast → ON
  - Text spacing → ON
- [ ] Klik tombol "Reset ke Default" (merah)
- [ ] Popup konfirmasi muncul
- [ ] Klik "OK"
- [ ] Semua setting kembali normal:
  - Font size → 100%
  - High contrast → OFF
  - Text spacing → OFF
- [ ] Badge menampilkan "100%"
- [ ] Toggle semua unchecked
- [ ] Notifikasi: "Pengaturan direset ke default"
- [ ] Refresh halaman
- [ ] Semua tetap default (localStorage terhapus)

**Expected Result:** ✅ Reset berhasil, semua kembali ke default

---

### 6. Panel Interaction

**Langkah:**
- [ ] Klik tombol aksesibilitas (♿)
- [ ] Panel muncul dengan animasi slide + scale
- [ ] Panel blur background terlihat
- [ ] Klik tombol close (X) di kanan atas panel
- [ ] Panel tertutup dengan smooth
- [ ] Klik tombol aksesibilitas lagi
- [ ] Panel terbuka lagi
- [ ] Klik area di luar panel
- [ ] Panel otomatis tertutup

**Expected Result:** ✅ Panel open/close smooth, auto-close saat klik di luar

---

### 7. Toast Notifications

**Langkah:**
- [ ] Klik toggle "Kontras Tinggi"
- [ ] Toast notification muncul di tengah bawah layar
- [ ] Background hijau (success)
- [ ] Animasi slide up masuk
- [ ] Tunggu 3 detik
- [ ] Toast otomatis hilang dengan slide down
- [ ] Klik tombol + font size sampai maksimum
- [ ] Toast warning muncul dengan background kuning
- [ ] Test notifikasi info dengan toggle text spacing

**Expected Result:** ✅ Toast muncul, warna sesuai tipe, auto-hide setelah 3 detik

---

### 8. Responsive (Mobile)

**Langkah:**
- [ ] Buka DevTools (F12)
- [ ] Toggle device toolbar (Ctrl+Shift+M)
- [ ] Pilih "iPhone 12 Pro" atau device mobile
- [ ] Tombol aksesibilitas mengecil (50x50px)
- [ ] Tombol TTS mengecil (50x50px)
- [ ] Text "Baca" di TTS button tersembunyi
- [ ] Panel aksesibilitas full-width (minus 32px)
- [ ] Klik panel → terbuka dengan baik
- [ ] Semua fitur tetap berfungsi di mobile
- [ ] Panel responsive, tidak overflow

**Expected Result:** ✅ Semua responsive, UI optimal untuk mobile

---

### 9. Kombinasi Fitur

**Langkah:**
- [ ] Aktifkan Font Size → 130%
- [ ] Aktifkan High Contrast
- [ ] Aktifkan Text Spacing
- [ ] Refresh halaman
- [ ] Ketiga fitur tetap aktif (kombinasi berfungsi)
- [ ] Klik TTS button
- [ ] Suara tetap jelas dengan semua fitur ON
- [ ] Visual tetap bagus dengan kombinasi

**Expected Result:** ✅ Semua fitur bisa dikombinasikan tanpa konflik

---

### 10. Browser Compatibility

**Test di Browser Berbeda:**

**Chrome/Edge:**
- [ ] Font Size: ✅
- [ ] High Contrast: ✅
- [ ] Text Spacing: ✅
- [ ] Text-to-Speech: ✅

**Firefox:**
- [ ] Font Size: ✅
- [ ] High Contrast: ✅
- [ ] Text Spacing: ✅
- [ ] Text-to-Speech: ✅

**Safari (jika tersedia):**
- [ ] Font Size: ✅
- [ ] High Contrast: ✅
- [ ] Text Spacing: ✅
- [ ] Text-to-Speech: ✅

**Expected Result:** ✅ Semua fitur berfungsi di major browsers

---

## 🐛 Bug Testing

### Edge Cases:

**1. Multiple Clicks:**
- [ ] Klik tombol + berkali-kali dengan cepat
- [ ] Tidak terjadi error
- [ ] Maksimum tetap 150%

**2. Rapid Toggle:**
- [ ] Toggle high contrast ON/OFF dengan cepat 10x
- [ ] Tidak terjadi flicker atau error
- [ ] State konsisten

**3. Panel Spam Click:**
- [ ] Klik tombol aksesibilitas berkali-kali
- [ ] Panel tidak duplicate
- [ ] Toggle smooth

**4. TTS Spam Click:**
- [ ] Klik TTS button berkali-kali saat sedang membaca
- [ ] Tidak ada double speech
- [ ] State tetap konsisten

**5. localStorage Full:**
- [ ] (Sulit ditest, skip jika tidak perlu)
- [ ] Error handling tetap ada di code

---

## 📊 Console Log Check

**Buka Console (F12 → Console Tab):**

Expected logs setelah halaman dimuat:
```
✅ Accessibility Features initialized
📊 Current preferences: {fontSize: "100", highContrast: null, textSpacing: null}
🎤 Text-to-Speech: Available
```

**No Errors Expected:**
- ❌ Tidak boleh ada error merah
- ⚠️ Warning boleh diabaikan (jika ada)

---

## ✅ Final Verification

### Semua Fitur Terimplementasi:
- [x] Font Size Adjustment (80%-150%)
- [x] High Contrast Mode
- [x] Text Spacing
- [x] Text-to-Speech dengan Web Speech API
- [x] localStorage Persistence
- [x] Toast Notifications
- [x] Responsive Design
- [x] Accessibility Panel dengan gradient AdabKita
- [x] Floating Buttons (2 buttons)
- [x] Smooth Animations
- [x] ARIA Attributes
- [x] Reset Button
- [x] Auto-save Preferences

### Semua Kode Berkomentar:
- [x] HTML: `{{-- [ACCESSIBILITY FEATURE] --}}`
- [x] CSS: `/* [ACCESSIBILITY FEATURE] */`
- [x] JavaScript: `// [ACCESSIBILITY FEATURE]`

---

## 🎯 Success Criteria

✅ **PASS** jika:
- Semua 10 testing steps berhasil
- Tidak ada error di console
- Toast notifications muncul dengan benar
- localStorage menyimpan preferences
- Responsive di mobile
- TTS berfungsi di browser yang support

❌ **FAIL** jika:
- Ada error di console yang blocking
- Fitur tidak berfungsi
- localStorage tidak save
- UI broken di mobile
- TTS tidak ada suara (di browser yang support)

---

## 🚨 Troubleshooting Quick Fix

**TTS tidak keluar suara:**
1. Cek volume sistem
2. Test browser lain (Chrome recommended)
3. Cek console: `speechSynthesis in window` → harus `true`

**Font size tidak berubah:**
1. Ctrl+F5 (hard refresh)
2. Clear cache browser
3. Cek localStorage di DevTools → Application → Local Storage

**High contrast tidak apply:**
1. Inspect element
2. Pastikan class `high-contrast` ada di `<body>`
3. Cek CSS override

---

## 📝 Testing Notes

**Tested By:** _____________________
**Date:** _____________________
**Browser:** _____________________
**OS:** _____________________
**Result:** [ ] PASS  [ ] FAIL
**Notes:**
_________________________________________________
_________________________________________________
_________________________________________________

---

**Status Akhir:** 🟢 **SIAP TESTING**

**URL:** http://127.0.0.1:8000

**Estimasi Waktu Testing:** 15-20 menit (semua steps)
