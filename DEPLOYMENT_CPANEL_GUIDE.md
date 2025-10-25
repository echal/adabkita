# 🎯 Panduan Deploy AdabKita via cPanel

## Panduan Lengkap Deployment untuk Shared Hosting dengan cPanel

Dokumen ini khusus untuk deployment di **shared hosting** yang menggunakan **cPanel** (seperti Hostinger, Niagahoster, Rumahweb, dll).

---

## 📋 Persiapan Sebelum Mulai

### Yang Anda Butuhkan:
- ✅ Akses cPanel (username + password)
- ✅ Domain sudah pointing ke hosting
- ✅ Akses email untuk notifikasi
- ✅ Download source code dari GitHub

### Informasi yang Perlu Disiapkan:
- Domain: `adabkita.gaspul.com`
- cPanel URL: `https://adabkita.gaspul.com:2083` atau `https://cpanel.hosting-anda.com`
- Username cPanel: (dari provider hosting)
- Password cPanel: (dari provider hosting)

---

## 🚀 STEP-BY-STEP DEPLOYMENT

### **STEP 1: Login ke cPanel**

1. Buka browser
2. Akses cPanel URL (biasanya: `https://your-domain.com:2083`)
3. Login dengan username dan password cPanel Anda

![cPanel Login](https://i.imgur.com/cpanel-login.png)

---

### **STEP 2: Create Database MySQL**

#### 2.1 Buka MySQL Databases

1. Di cPanel, scroll ke bagian **"Databases"**
2. Klik **"MySQL Databases"**

#### 2.2 Create Database

1. Di bagian **"Create New Database"**
2. Masukkan nama database: `adabkita_prod` (akan jadi: `cpaneluser_adabkita_prod`)
3. Klik **"Create Database"**
4. **Catat nama database lengkap** (contoh: `cpaneluser_adabkita_prod`)

#### 2.3 Create Database User

1. Scroll ke **"MySQL Users"** → **"Add New User"**
2. Username: `adabkita_user`
3. Password: Klik **"Password Generator"** untuk generate password kuat
4. **PENTING: COPY dan SIMPAN password ini!**
5. Klik **"Create User"**
6. **Catat username lengkap** (contoh: `cpaneluser_adabkita_user`)

#### 2.4 Add User to Database

1. Scroll ke **"Add User To Database"**
2. User: Pilih `cpaneluser_adabkita_user`
3. Database: Pilih `cpaneluser_adabkita_prod`
4. Klik **"Add"**
5. Di halaman privileges, centang **"ALL PRIVILEGES"**
6. Klik **"Make Changes"**

**✅ Database Credentials (SIMPAN INI!):**
```
DB_HOST: localhost
DB_DATABASE: cpaneluser_adabkita_prod
DB_USERNAME: cpaneluser_adabkita_user
DB_PASSWORD: [password yang di-generate]
```

---

### **STEP 3: Upload Files via File Manager**

#### 3.1 Download Source Code

**Option A: Download dari GitHub**
1. Buka https://github.com/echal/adabkita
2. Klik **"Code"** → **"Download ZIP"**
3. Extract file ZIP di komputer Anda

**Option B: Clone via Terminal cPanel** (skip ke Step 4 jika pilih ini)

#### 3.2 Upload Files

1. Di cPanel, buka **"File Manager"**
2. Navigate ke folder **`public_html`**
3. **PENTING:** Buat folder baru atau gunakan subdomain:
   - **Jika main domain:** Bersihkan `public_html` dulu
   - **Jika subdomain:** Buat folder `adabkita` di `public_html`

4. Upload source code:
   - Klik **"Upload"** di toolbar
   - Upload file `adabkita-main.zip` (atau drag & drop)
   - Tunggu sampai upload selesai (bisa 5-10 menit tergantung koneksi)

#### 3.3 Extract Files

1. Kembali ke File Manager
2. Klik kanan pada `adabkita-main.zip`
3. Pilih **"Extract"**
4. Tunggu proses extract selesai
5. Pindahkan semua file dari folder `adabkita-main` ke `public_html`
   ```
   public_html/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/         ← Folder ini yang akan jadi document root
   ├── resources/
   ├── routes/
   ├── storage/
   ├── .env.production
   ├── composer.json
   └── artisan
   ```

---

### **STEP 4: Setup via Terminal cPanel (RECOMMENDED)**

cPanel modern menyediakan Terminal. Ini lebih mudah dari File Manager.

#### 4.1 Buka Terminal

1. Di cPanel, cari **"Terminal"** di search box
2. Klik **"Terminal"** untuk membuka
3. Terminal akan terbuka di tab baru

#### 4.2 Clone Repository

```bash
# Masuk ke public_html
cd public_html

# Backup file default (jika ada)
mkdir -p ~/backup_default
mv * ~/backup_default/ 2>/dev/null || true

# Clone repository
git clone https://github.com/echal/adabkita.git .

# Lihat isi folder
ls -la
```

**✅ Output yang diharapkan:**
```
app/
bootstrap/
config/
database/
public/
resources/
...
.env.production
composer.json
```

---

### **STEP 5: Install Composer Dependencies**

**PENTING:** Shared hosting biasanya sudah ada Composer, tapi versi module mungkin berbeda.

#### 5.1 Check Composer

```bash
# Cek versi composer
composer --version
```

Jika composer tidak ada, hubungi hosting support untuk enable.

#### 5.2 Install Dependencies

```bash
# Install dependencies (PRODUCTION ONLY - tanpa dev packages)
composer install --no-dev --optimize-autoloader --no-interaction

# Ini akan download ~50MB dependencies
# Tunggu 5-10 menit tergantung kecepatan server
```

**Jika error "memory limit":**
```bash
# Gunakan flag memory limit lebih besar
php -d memory_limit=512M /usr/local/bin/composer install --no-dev --optimize-autoloader
```

---

### **STEP 6: Configure Environment (.env)**

#### 6.1 Copy Template Production

```bash
# Copy .env.production ke .env
cp .env.production .env
```

#### 6.2 Edit .env File

**Via Terminal (nano editor):**
```bash
nano .env
```

**Via File Manager:**
1. Klik kanan pada `.env`
2. Pilih **"Edit"**
3. Pilih **"Code Editor"** (lebih bagus dari Text Editor)

#### 6.3 Konfigurasi Wajib

Edit bagian berikut di `.env`:

```env
# ===== APPLICATION =====
APP_NAME="AdabKita"
APP_ENV=production
APP_DEBUG=false                              # WAJIB false!
APP_URL=https://adabkita.gaspul.com          # Ganti dengan domain Anda

# ===== DATABASE (dari Step 2) =====
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpaneluser_adabkita_prod         # Nama database lengkap
DB_USERNAME=cpaneluser_adabkita_user         # Username lengkap
DB_PASSWORD=password_from_step2              # Password dari Step 2

# ===== SESSION =====
SESSION_DRIVER=database
SESSION_DOMAIN=.adabkita.gaspul.com          # Dengan titik di depan

# ===== MAIL (Optional - untuk notifikasi) =====
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com                     # Atau SMTP hosting Anda
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@adabkita.gaspul.com
```

**Save:**
- **Nano:** Ctrl+O → Enter → Ctrl+X
- **File Manager:** Klik **"Save Changes"**

---

### **STEP 7: Generate APP_KEY**

```bash
# Generate APP_KEY (PENTING!)
php artisan key:generate --force

# Verify APP_KEY terisi
grep APP_KEY .env
```

**Output yang diharapkan:**
```
APP_KEY=base64:abc123def456...
```

---

### **STEP 8: Run Database Migrations**

```bash
# Test database connection dulu
php artisan migrate:status

# Jika OK, run migrations
php artisan migrate --force

# Seed initial data (admin user, dll)
php artisan db:seed --force
```

**✅ Output yang diharapkan:**
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
...
Database seeding completed successfully.
```

**Jika error "Access denied":**
- Cek DB_HOST (harus `localhost`)
- Cek DB_USERNAME dan DB_PASSWORD
- Cek privileges database user

---

### **STEP 9: Set File Permissions**

```bash
# Set permissions untuk storage dan cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika masih error, coba 777 (kurang aman tapi kadang perlu di shared hosting)
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

---

### **STEP 10: Optimize Application**

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Build production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

### **STEP 11: Configure Document Root**

**PENTING:** Laravel mengharuskan document root mengarah ke folder `/public`, BUKAN root folder.

#### Option A: Via cPanel (Recommended)

1. Di cPanel, buka **"Domains"** → **"Addon Domains"** atau **"Domains"**
2. Klik **"Manage"** di domain Anda
3. Ubah **"Document Root"** dari:
   ```
   /home/username/public_html
   ```
   Menjadi:
   ```
   /home/username/public_html/public
   ```
4. Klik **"Change"** atau **"Save"**

#### Option B: Via .htaccess Redirect (Jika Option A tidak bisa)

Jika tidak bisa ubah document root, buat redirect:

**File:** `public_html/.htaccess`
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Atau pindahkan semua file dari `/public` ke root:**
```bash
# JANGAN LAKUKAN INI KECUALI TERPAKSA!
# Ini kurang secure

cd ~/public_html
mv public/.htaccess .
mv public/index.php .
mv public/robots.txt .
mv public/favicon.ico .

# Edit index.php
# Ganti:
# require __DIR__.'/../vendor/autoload.php';
# Jadi:
# require __DIR__.'/vendor/autoload.php';
```

---

### **STEP 12: Install SSL Certificate (HTTPS)**

#### 12.1 Via cPanel SSL/TLS

1. Di cPanel, cari **"SSL/TLS Status"**
2. Pilih domain Anda
3. Klik **"Run AutoSSL"**
4. Tunggu 1-5 menit sampai SSL terinstall

#### 12.2 Verify SSL

1. Buka browser
2. Akses: `https://adabkita.gaspul.com`
3. Cek icon gembok di address bar (harus hijau/secure)

#### 12.3 Force HTTPS

Edit file `public_html/public/.htaccess` (atau `.htaccess` di root):

Tambahkan di paling atas:
```apache
# Force HTTPS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

---

### **STEP 13: Test Aplikasi**

#### 13.1 Test Homepage

Buka browser dan akses:
```
https://adabkita.gaspul.com
```

**✅ Yang diharapkan:**
- Homepage AdabKita muncul
- No error 500 atau 404
- CSS dan assets loading dengan baik

#### 13.2 Test Login

1. Akses: `https://adabkita.gaspul.com/login`
2. Login dengan user default (dari seeder):
   ```
   Email: admin@adabkita.com
   Password: password
   ```
3. Harus bisa login dan redirect ke dashboard

#### 13.3 Test Sertifikat Download

1. Login sebagai siswa
2. Selesaikan satu pelajaran dengan nilai >= 80%
3. Download sertifikat PDF
4. Verify PDF ter-generate dengan baik

---

### **STEP 14: Setup Automatic Backup (Optional)**

#### Via cPanel Backup

1. Di cPanel, buka **"Backup"** atau **"Backup Wizard"**
2. Pilih **"Full Backup"** atau **"Database Backup"**
3. Set schedule (jika hosting support)

#### Via Cron Job

1. Di cPanel, buka **"Cron Jobs"**
2. Add New Cron Job:
   ```
   Minute: 0
   Hour: 2
   Day: *
   Month: *
   Weekday: *

   Command:
   cd /home/username/public_html && /usr/local/bin/php artisan backup:database
   ```

**Atau script custom:**
```bash
# Create backup script
nano ~/backup-adabkita.sh
```

Isi:
```bash
#!/bin/bash
cd /home/username/public_html
mysqldump -u cpaneluser_adabkita_user -p'password' cpaneluser_adabkita_prod | gzip > ~/backups/adabkita_$(date +%Y%m%d).sql.gz
find ~/backups/ -name "adabkita_*.sql.gz" -mtime +30 -delete
```

Set executable:
```bash
chmod +x ~/backup-adabkita.sh
```

Add ke cron:
```
0 2 * * * /home/username/backup-adabkita.sh
```

---

## 🔧 Troubleshooting cPanel

### Problem 1: "500 Internal Server Error"

**Solusi:**
```bash
# Check logs
tail -50 storage/logs/laravel.log

# Check error log cPanel
# Di cPanel → Metrics → Errors

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Problem 2: "composer: command not found"

**Solusi:**
```bash
# Coba gunakan path lengkap
/usr/local/bin/composer --version

# Atau
php /usr/local/bin/composer install --no-dev

# Jika masih tidak ada, hubungi hosting support
```

### Problem 3: "PHP Version Too Low"

**Solusi:**
1. Di cPanel, buka **"Select PHP Version"** atau **"MultiPHP Manager"**
2. Pilih **PHP 8.2** atau **PHP 8.1**
3. Enable extensions:
   - mbstring
   - xml
   - curl
   - zip
   - gd
   - mysql (atau mysqli)
   - bcmath

### Problem 4: "Memory Limit Exhausted"

**Solusi:**

Edit file `.user.ini` atau `php.ini` di root:
```ini
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M
```

Atau via `.htaccess`:
```apache
php_value memory_limit 512M
php_value max_execution_time 300
```

### Problem 5: "Database Connection Failed"

**Solusi:**
```bash
# Test koneksi MySQL
mysql -u cpaneluser_adabkita_user -p

# Jika failed:
# 1. Cek DB_HOST di .env (harus 'localhost')
# 2. Cek username dan password di .env
# 3. Verify user added to database (cPanel → MySQL Databases)
# 4. Test dengan different host:
#    - localhost
#    - 127.0.0.1
#    - mysql.adabkita.gaspul.com (check dengan hosting support)
```

### Problem 6: "Route Not Found / 404"

**Solusi:**
```bash
# Check .htaccess exists
ls -la public/.htaccess

# Check mod_rewrite enabled (hubungi hosting support jika tidak)

# Clear route cache
php artisan route:clear
php artisan route:cache

# Verify document root pointing ke /public
```

### Problem 7: "CSRF Token Mismatch"

**Solusi:**

Edit `.env`:
```env
SESSION_DRIVER=database
SESSION_DOMAIN=.adabkita.gaspul.com
SESSION_SECURE_COOKIE=true
```

Lalu:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📋 Checklist Deployment cPanel

Sebelum Go Live, pastikan semua ini sudah ✅:

### Pre-Deployment
- [ ] Akses cPanel tersedia
- [ ] Domain sudah pointing ke hosting
- [ ] PHP 8.2+ available
- [ ] MySQL available

### Database Setup
- [ ] Database created
- [ ] Database user created
- [ ] User added to database dengan ALL PRIVILEGES
- [ ] Credentials disimpan dengan aman

### Files Upload
- [ ] Source code uploaded/cloned
- [ ] Composer dependencies installed
- [ ] .env file configured
- [ ] APP_KEY generated

### Configuration
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_URL set dengan domain production
- [ ] Database credentials correct
- [ ] Migrations run successfully

### Permissions
- [ ] storage/ writable (755 atau 777)
- [ ] bootstrap/cache/ writable (755 atau 777)

### Web Server
- [ ] Document root pointing ke /public
- [ ] .htaccess exists di /public
- [ ] mod_rewrite enabled

### Security
- [ ] SSL certificate installed
- [ ] HTTPS redirect active
- [ ] .env not accessible via web

### Testing
- [ ] Homepage loads (no 500/404)
- [ ] Login works
- [ ] Dashboard loads
- [ ] Sertifikat PDF generates
- [ ] All features tested

### Optional
- [ ] Auto backup setup
- [ ] Monitoring setup
- [ ] Email SMTP configured

---

## 📞 Kontak Hosting Support

Jika ada masalah, hubungi hosting support dengan info berikut:

**Template Email:**
```
Subject: Bantuan Setup Laravel Application

Halo Support Team,

Saya sedang deploy aplikasi Laravel di hosting ini.
Mohon bantuan untuk hal berikut:

1. Confirm PHP version 8.2 atau 8.1 tersedia
2. Enable PHP extensions: mbstring, xml, curl, zip, gd, mysql, bcmath
3. Confirm Composer tersedia
4. Cara set document root ke subfolder /public
5. Confirm mod_rewrite enabled

Domain: adabkita.gaspul.com
cPanel Username: [username]

Terima kasih!
```

---

## 🎉 Selesai!

Jika semua step diikuti dengan benar, aplikasi AdabKita Anda seharusnya sudah running di production!

**Test URL:** `https://adabkita.gaspul.com`

**Default Login:**
- Email: `admin@adabkita.com`
- Password: `password` (GANTI SEGERA!)

**Next Steps:**
1. ✅ Ganti password admin
2. ✅ Tambah user guru dan siswa
3. ✅ Upload materi pembelajaran
4. ✅ Test semua fitur
5. ✅ Setup monitoring

---

## 📚 Resources

- **cPanel Documentation:** https://docs.cpanel.net/
- **Laravel Deployment:** https://laravel.com/docs/11.x/deployment
- **GitHub Repository:** https://github.com/echal/adabkita

---

**Last Updated:** 2025-10-26
**Version:** 1.0
**For:** cPanel Shared Hosting

**Happy Deploying! 🚀**
