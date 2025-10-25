# 🎯 DOKUMENTASI FITUR BARU DASHBOARD SISWA

## **Overview**
Dokumen ini berisi panduan lengkap implementasi 5 fitur baru yang telah ditambahkan ke Dashboard Siswa AdabKita.

---

## **📋 Daftar Fitur Baru**

1. **🔍 Pencarian Pelajaran Real-time** - Search dengan AJAX
2. **📄 Pagination Dinamis** - 8 pelajaran per halaman
3. **🏅 Generator Sertifikat PDF** - Download sertifikat dengan DomPDF
4. **📊 Grafik Progress Mingguan** - Chart.js untuk visualisasi
5. **🔔 Notifikasi Real-time** - (Opsional, untuk pengembangan lanjutan)

---

## **🔧 Dependencies yang Dibutuhkan**

### **1. Install Laravel DomPDF**

```bash
composer require barryvdh/laravel-dompdf
```

### **2. Publish Config (Opsional)**

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### **3. Chart.js (Sudah terintegras via CDN di view)**
Tidak perlu instalasi tambahan, menggunakan CDN.

---

## **📁 File yang Telah Dibuat/Diupdate**

### **Controllers**

1. **`app/Http/Controllers/Siswa/SertifikatController.php`** ⭐ **BARU**
   - `download($idProgress)` - Generate dan download PDF sertifikat
   - `preview($idProgress)` - Preview sertifikat di browser
   - `index()` - Daftar semua sertifikat siswa

2. **`app/Http/Controllers/Siswa/DashboardController.php`** ✏️ **UPDATED**
   - `index(Request $request)` - Tambahan parameter request untuk search
   - **[NEW]** Search & Pagination untuk pelajaran
   - **[NEW]** `getProgressChartData($idSiswa)` - Data untuk chart mingguan
   - **[NEW]** `searchPelajaran(Request $request)` - AJAX endpoint

### **Routes**

3. **`routes/web.php`** ✏️ **UPDATED**
   - Route AJAX search: `/siswa/search-pelajaran`
   - Route sertifikat: `/siswa/sertifikat`
   - Route download PDF: `/siswa/sertifikat/download/{id}`
   - Route preview PDF: `/siswa/sertifikat/preview/{id}`

### **Views**

4. **`resources/views/siswa/sertifikat_pdf.blade.php`** ⭐ **BARU**
   - Template PDF sertifikat dengan design elegant
   - A4 Landscape format
   - Gradient border, decorative corners
   - Signature section untuk guru & kepala sekolah

5. **`resources/views/halaman_siswa/dashboard.blade.php`** ✏️ **TO BE UPDATED**
   - **Fitur Search Box** dengan AJAX
   - **Pagination Links** dengan Tailwind style
   - **Download Button** sertifikat di course card
   - **Progress Chart** menggunakan Chart.js
   - **Responsive** untuk mobile/tablet/desktop

---

## **🎨 IMPLEMENTASI DI VIEW (dashboard.blade.php)**

Berikut adalah kode yang perlu ditambahkan ke dashboard.blade.php:

### **1. Search Box (Sebelum Section "Kelas Saya")**

```blade
{{-- =====================================================
    [NEW FEATURE] SEARCH SECTION
    Pencarian pelajaran dengan AJAX
    ===================================================== --}}
<section class="bg-white py-6 sticky top-16 z-40 shadow-md">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="relative">
                {{-- Search Icon --}}
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Search Input --}}
                <input
                    type="text"
                    id="searchPelajaran"
                    name="search"
                    placeholder="Cari pelajaran adab..."
                    value="{{ request('search') }}"
                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-full focus:outline-none focus:border-adab-purple transition"
                >

                {{-- Loading Indicator --}}
                <div id="searchLoading" class="hidden absolute inset-y-0 right-0 pr-4 flex items-center">
                    <svg class="animate-spin h-5 w-5 text-adab-purple" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            {{-- Search Results Count --}}
            <div id="searchResultCount" class="mt-2 text-sm text-gray-600 text-center hidden">
                Ditemukan <span id="resultCount">0</span> pelajaran
            </div>
        </div>
    </div>
</section>
```

### **2. Update Course Cards - Tambah Button Download Sertifikat**

Di dalam loop `@forelse($pelajaranList as $pelajaran)`, tambahkan tombol download di bagian bawah card:

```blade
{{-- Button Section - Updated --}}
<div class="space-y-2">
    {{-- Button Lanjutkan/Mulai Belajar --}}
    <a href="{{ route('siswa.lesson-interaktif.mulai', $pelajaran['id']) }}"
       class="block w-full {{ $pelajaran['progress'] >= 100 ? 'bg-green-500' : 'bg-adab-gradient-purple' }} text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:scale-105 text-center">
        @if($pelajaran['progress'] >= 100)
            ✅ Lihat Ulang
        @elseif($pelajaran['progress'] > 0)
            ▶️ Lanjutkan Belajar
        @else
            ▶️ Mulai Belajar
        @endif
    </a>

    {{-- [NEW FEATURE] Button Download Sertifikat --}}
    @if($pelajaran['dapat_sertifikat'])
        <a href="{{ route('siswa.sertifikat.download', $pelajaran['progress_id']) }}"
           class="block w-full bg-yellow-500 text-white font-semibold py-3 rounded-lg hover:bg-yellow-600 hover:shadow-lg transition transform hover:scale-105 text-center">
            🏆 Unduh Sertifikat
        </a>
    @endif
</div>
```

### **3. Pagination Links (Setelah Course Cards Loop)**

```blade
{{-- [NEW FEATURE] PAGINATION --}}
@if($pelajaranPaginated->hasPages())
    <div class="mt-12 flex justify-center">
        <nav class="inline-flex rounded-md shadow-sm -space-x-px">
            {{-- Previous Button --}}
            @if ($pelajaranPaginated->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $pelajaranPaginated->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-adab-purple hover:text-white transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Sebelumnya
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($pelajaranPaginated->links()->elements[0] as $page => $url)
                @if ($page == $pelajaranPaginated->currentPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-adab-purple bg-adab-gradient-purple text-sm font-medium text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-adab-purple hover:text-white transition">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Button --}}
            @if ($pelajaranPaginated->hasMorePages())
                <a href="{{ $pelajaranPaginated->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-adab-purple hover:text-white transition">
                    Selanjutnya
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                    Selanjutnya
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif
```

### **4. Progress Chart (Sebelum Section Kategori)**

```blade
{{-- =====================================================
    [NEW FEATURE] GRAFIK PROGRESS MINGGUAN
    Visualisasi progress belajar dengan Chart.js
    ===================================================== --}}
<section class="py-16 bg-gradient-to-b from-white to-purple-50">
    <div class="container mx-auto px-4 lg:px-8">
        {{-- SECTION HEADER --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                📊 Progress Belajar Mingguan
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Lihat perkembangan nilai rata-rata kamu selama 8 minggu terakhir
            </p>
        </div>

        {{-- CHART CONTAINER --}}
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
            <canvas id="progressChart" height="80"></canvas>
        </div>
    </div>
</section>
```

### **5. JavaScript untuk Search & Chart (Sebelum `</body>`)**

```blade
{{-- =====================================================
    [NEW FEATURE] Chart.js CDN
    ===================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // =====================================================
    // [NEW FEATURE] AJAX SEARCH FUNCTIONALITY
    // =====================================================
    const searchInput = document.getElementById('searchPelajaran');
    const searchLoading = document.getElementById('searchLoading');
    const searchResultCount = document.getElementById('searchResultCount');
    const resultCount = document.getElementById('resultCount');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const keyword = this.value;

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Show loading indicator
        searchLoading.classList.remove('hidden');

        // Debounce search (tunggu 500ms setelah user berhenti mengetik)
        searchTimeout = setTimeout(() => {
            performSearch(keyword);
        }, 500);
    });

    function performSearch(keyword) {
        // Jika keyword kosong, redirect ke halaman normal tanpa search
        if (keyword.trim() === '') {
            window.location.href = '{{ route("siswa.dashboard") }}';
            return;
        }

        // Redirect dengan query parameter
        const url = '{{ route("siswa.dashboard") }}?search=' + encodeURIComponent(keyword);
        window.location.href = url;
    }

    // =====================================================
    // [NEW FEATURE] PROGRESS CHART WITH CHART.JS
    // =====================================================
    const ctx = document.getElementById('progressChart').getContext('2d');

    // Data dari controller
    const chartLabels = @json($progressData['labels']);
    const chartValues = @json($progressData['values']);

    const progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Rata-rata Nilai (%)',
                data: chartValues,
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                tension: 0.4, // Curved line
                fill: true,
                pointBackgroundColor: 'rgb(102, 126, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            family: 'Poppins'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        family: 'Poppins'
                    },
                    bodyFont: {
                        size: 13,
                        family: 'Poppins'
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Nilai: ' + context.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            family: 'Poppins'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Poppins'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
```

---

## **🧪 Cara Testing**

### **1. Test Search Pelajaran**
1. Buka dashboard siswa
2. Ketik keyword di search box (contoh: "adab makan")
3. Halaman akan reload dengan filter hasil search
4. Coba hapus keyword, halaman kembali ke daftar lengkap

### **2. Test Pagination**
1. Pastikan ada lebih dari 8 pelajaran di database
2. Buka dashboard, scroll ke bawah
3. Klik tombol "Selanjutnya" atau nomor halaman
4. Pastikan data pelajaran berganti

### **3. Test Download Sertifikat**
1. Selesaikan satu pelajaran dengan nilai >= 80%
2. Tombol "🏆 Unduh Sertifikat" akan muncul di course card
3. Klik tombol tersebut
4. PDF sertifikat akan terdownload

### **4. Test Progress Chart**
1. Pastikan ada data progress di `lesson_progress` dengan `status = 'selesai'`
2. Chart akan menampilkan grafik progress 8 minggu terakhir
3. Hover pada titik grafik untuk melihat detail nilai

---

## **📌 Catatan Penting**

### **Untuk Admin/Developer:**

1. **Install Composer Package:**
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

2. **Clear Cache Laravel:**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Pastikan Folder Writable:**
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

4. **Testing Route:**
   ```bash
   php artisan route:list | grep siswa
   ```

### **Troubleshooting:**

**Error: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"**
- Pastikan sudah install package: `composer require barryvdh/laravel-dompdf`
- Clear config cache: `php artisan config:clear`

**Chart tidak muncul:**
- Pastikan CDN Chart.js terload (cek console browser)
- Pastikan ada data di `$progressData`

**Pagination tidak bekerja:**
- Pastikan menggunakan `$pelajaranPaginated` di view, bukan `$pelajaranList`

---

## **🚀 Next Steps (Pengembangan Lanjutan)**

1. **Real-time Notification dengan Laravel Echo**
   - Install Laravel Echo + Pusher/Socket.io
   - Broadcast event saat pelajaran baru ditambahkan
   - Broadcast event saat sertifikat diterbitkan

2. **Export Grafik ke Image**
   - Tambahkan button untuk download chart sebagai PNG

3. **Filter Pelajaran Berdasarkan Kategori**
   - Klik kategori → filter pelajaran sesuai kategori

4. **Leaderboard Siswa**
   - Tampilkan top 10 siswa berdasarkan rata-rata nilai

---

## **📞 Support**

Jika ada pertanyaan atau kendala, silakan hubungi:
- **Developer:** AdabKita Development Team
- **Documentation:** File ini (FITUR_BARU_DASHBOARD.md)

---

**Generated:** 25 Oktober 2025
**Version:** 1.0 - Dashboard Siswa Enhanced Features
**Laravel Version:** 11.x
**TailwindCSS:** v3
