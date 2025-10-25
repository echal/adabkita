# 🚀 Production Readiness Checklist - AdabKita

## Status: ⚠️ BELUM SIAP UNTUK PRODUCTION

Aplikasi **AdabKita** masih memerlukan beberapa konfigurasi penting sebelum dapat di-deploy ke production. Berikut adalah analisis lengkap dan checklist yang harus diselesaikan:

---

## ✅ Yang Sudah Siap

### 1. **Aplikasi & Framework**
- ✅ Laravel 11.46.1 (versi terbaru dan stabil)
- ✅ PHP Extensions lengkap (curl, dom, gd, mbstring, openssl, pdo_mysql, zip)
- ✅ Structure folder Laravel standar
- ✅ Routes sudah terdefinisi dengan baik (auth, role-based access)
- ✅ Middleware authentication dan role-based authorization
- ✅ Package DomPDF untuk sertifikat PDF

### 2. **Fitur Aplikasi**
- ✅ Sistem login & logout
- ✅ Role-based dashboard (Admin, Guru, Siswa)
- ✅ CRUD Pengguna, Materi, Lesson Flow
- ✅ Sistem pembelajaran interaktif
- ✅ Sistem sertifikat PDF (baru diaktifkan)
- ✅ Analytics & rekap nilai
- ✅ Search & pagination

### 3. **Security**
- ✅ CSRF Protection (Laravel default)
- ✅ Password hashing (bcrypt)
- ✅ Route middleware protection
- ✅ .env file di .gitignore (credentials tidak terupload ke Git)
- ✅ .htaccess configured

---

## ❌ Yang BELUM Siap (CRITICAL)

### 1. **Environment Configuration** 🔴 PRIORITY HIGH

**File:** `.env`

**Masalah:**
```env
APP_ENV=local          # ❌ Harus diubah ke 'production'
APP_DEBUG=true         # ❌ Harus diubah ke 'false'
APP_KEY=               # ❌ Harus di-generate
APP_URL=http://localhost  # ❌ Harus diganti dengan domain production
```

**Solusi:**
```bash
# 1. Copy .env.example ke .env untuk production
cp .env.example .env.production

# 2. Edit .env.production:
APP_NAME="AdabKita"
APP_ENV=production
APP_KEY=                    # Akan di-generate
APP_DEBUG=false
APP_URL=https://adabkita.gaspul.com   # Ganti dengan domain Anda

# 3. Generate APP_KEY
php artisan key:generate

# 4. Pastikan APP_KEY terisi (base64:...)
```

**⚠️ BAHAYA jika APP_DEBUG=true di production:**
- Semua error akan menampilkan detail code, database query, dan path file
- Hacker bisa melihat struktur aplikasi Anda
- Credentials bisa terekspos

---

### 2. **Database Configuration** 🔴 PRIORITY HIGH

**File:** `.env`

**Masalah:**
```env
DB_CONNECTION=sqlite    # ❌ SQLite tidak disarankan untuk production
# DB_HOST=127.0.0.1    # ❌ Masih commented
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

**Solusi untuk Production:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1           # Atau IP database server
DB_PORT=3306
DB_DATABASE=adabkita_db     # Nama database production
DB_USERNAME=adabkita_user   # Username database (JANGAN pakai 'root')
DB_PASSWORD=********        # Password KUAT (min 16 karakter)
```

**Langkah Deploy Database:**
```bash
# 1. Buat database baru di server production
CREATE DATABASE adabkita_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 2. Buat user khusus (jangan pakai root)
CREATE USER 'adabkita_user'@'localhost' IDENTIFIED BY 'password_kuat_123!@#';
GRANT ALL PRIVILEGES ON adabkita_db.* TO 'adabkita_user'@'localhost';
FLUSH PRIVILEGES;

# 3. Jalankan migration
php artisan migrate --force

# 4. (Opsional) Seed data awal
php artisan db:seed --class=UserSeeder
```

---

### 3. **File Permissions** 🔴 PRIORITY HIGH

**Masalah:**
- Folder `storage/` dan `bootstrap/cache/` harus writable oleh web server

**Solusi (Linux/cPanel):**
```bash
# Set ownership ke user web server (biasanya www-data atau apache)
sudo chown -R www-data:www-data storage bootstrap/cache

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Atau jika tidak punya akses root (shared hosting):
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

**Untuk Windows (XAMPP/Production):**
- Pastikan folder `storage/` dan `bootstrap/cache/` memiliki write permission untuk user IIS/Apache

---

### 4. **Optimasi Production** 🟡 PRIORITY MEDIUM

**File:** Commands yang perlu dijalankan sebelum deploy

```bash
# 1. Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Optimize untuk production
php artisan config:cache       # Cache configuration
php artisan route:cache        # Cache routes
php artisan view:cache         # Cache views

# 3. Install dependencies production-only (tanpa dev packages)
composer install --optimize-autoloader --no-dev

# 4. Generate autoload files
composer dump-autoload --optimize
```

**⚠️ PENTING:**
- Jangan jalankan `*:cache` di development (local)
- Setiap kali ada perubahan config/routes, jalankan `*:clear` lalu `*:cache` lagi

---

### 5. **Web Server Configuration** 🟡 PRIORITY MEDIUM

#### **Apache (.htaccess sudah OK)**
✅ File `public/.htaccess` sudah ada dan configured

#### **Nginx (jika pakai Nginx)**
```nginx
server {
    listen 80;
    server_name adabkita.gaspul.com;
    root /var/www/adabkita/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### **Document Root**
- ⚠️ Document root HARUS mengarah ke folder `public/`, BUKAN root project
- ❌ SALAH: `/var/www/adabkita/`
- ✅ BENAR: `/var/www/adabkita/public/`

---

### 6. **SSL Certificate** 🟡 PRIORITY MEDIUM

**Status:** Belum ada konfigurasi HTTPS

**Solusi:**
```bash
# Option 1: Let's Encrypt (GRATIS)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d adabkita.gaspul.com

# Option 2: SSL dari hosting provider (cPanel)
# Install SSL Certificate melalui cPanel > SSL/TLS Manager

# Update .env
APP_URL=https://adabkita.gaspul.com
SESSION_SECURE_COOKIE=true
```

---

### 7. **Backup Strategy** 🟢 RECOMMENDED

**Tidak ada sistem backup:**

**Solusi:**
```bash
# 1. Backup Database (cron job harian)
#!/bin/bash
mysqldump -u adabkita_user -p adabkita_db > /backup/adabkita_$(date +\%Y\%m\%d).sql

# 2. Backup Files (weekly)
tar -czf /backup/adabkita_files_$(date +\%Y\%m\%d).tar.gz /var/www/adabkita

# 3. Setup cron job
0 2 * * * /path/to/backup_database.sh    # Daily at 2 AM
0 3 * * 0 /path/to/backup_files.sh       # Weekly Sunday 3 AM
```

---

### 8. **Monitoring & Logging** 🟢 RECOMMENDED

**Perlu ditambahkan:**
```env
# .env
LOG_CHANNEL=daily           # Rotate logs per hari
LOG_LEVEL=error             # Production: hanya log error, bukan debug

# Storage logs
storage/logs/laravel.log    # Sudah ada ✅
```

**Setup log rotation (Linux):**
```bash
# /etc/logrotate.d/laravel-adabkita
/var/www/adabkita/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
}
```

---

## 📋 Pre-Deployment Checklist

Sebelum deploy, pastikan checklist ini SEMUANYA centang:

### Critical (WAJIB)
- [ ] `APP_ENV=production` di `.env`
- [ ] `APP_DEBUG=false` di `.env`
- [ ] `APP_KEY` sudah di-generate
- [ ] `APP_URL` sesuai domain production
- [ ] Database production sudah dibuat dan dikonfigurasi
- [ ] Migration sudah dijalankan di database production
- [ ] Folder `storage/` dan `bootstrap/cache/` writable
- [ ] `.env` file TIDAK terupload ke Git (sudah di `.gitignore`)
- [ ] Document root mengarah ke folder `public/`
- [ ] Test login dengan user yang ada di database

### Performance (SANGAT DISARANKAN)
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] Test semua fitur utama (login, lesson, sertifikat)

### Security (SANGAT DISARANKAN)
- [ ] SSL Certificate sudah terinstall (HTTPS)
- [ ] Database user bukan 'root'
- [ ] Password database kuat (min 16 karakter)
- [ ] `SESSION_SECURE_COOKIE=true` jika sudah HTTPS
- [ ] Firewall configured (hanya port 80/443 terbuka)

### Monitoring (OPSIONAL TAPI PENTING)
- [ ] Backup database otomatis setup
- [ ] Backup files setup
- [ ] Log rotation configured
- [ ] Error monitoring (Sentry, Bugsnag, atau sejenisnya)
- [ ] Uptime monitoring (UptimeRobot, Pingdom, dll)

---

## 🚀 Deployment Steps

### Option 1: Manual Deployment (Shared Hosting / cPanel)

```bash
# 1. Upload files ke server (FTP/SFTP)
# Upload SEMUA file KECUALI:
# - node_modules/
# - vendor/
# - .env (upload manual setelahnya)

# 2. Di server, jalankan:
composer install --no-dev --optimize-autoloader

# 3. Copy .env.example ke .env dan edit konfigurasi
cp .env.example .env
nano .env  # Edit sesuai production config

# 4. Generate APP_KEY
php artisan key:generate

# 5. Set permissions
chmod -R 775 storage bootstrap/cache

# 6. Run migrations
php artisan migrate --force

# 7. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Test aplikasi
curl https://adabkita.gaspul.com/login
```

### Option 2: Git Deployment

```bash
# Di server production:

# 1. Clone repository
git clone https://github.com/echal/adabkita.git /var/www/adabkita
cd /var/www/adabkita

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Setup .env
cp .env.example .env
nano .env
php artisan key:generate

# 4. Set permissions
sudo chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache

# 5. Database setup
php artisan migrate --force
php artisan db:seed --class=UserSeeder

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Configure web server
# Edit Apache/Nginx config untuk point ke /var/www/adabkita/public

# 8. Restart web server
sudo systemctl restart apache2  # atau nginx
```

### Update Deployment (setelah push baru)

```bash
# Di server production:
cd /var/www/adabkita

# 1. Pull latest changes
git pull origin main

# 2. Update dependencies (jika ada perubahan composer.json)
composer install --no-dev --optimize-autoloader

# 3. Run migrations baru (jika ada)
php artisan migrate --force

# 4. Clear & rebuild cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart PHP-FPM (jika perlu)
sudo systemctl restart php8.2-fpm
```

---

## ⚠️ Common Production Issues & Solutions

### 1. **500 Internal Server Error**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Common fixes:
chmod -R 775 storage bootstrap/cache
php artisan cache:clear
php artisan config:clear
```

### 2. **404 Not Found (routes tidak berfungsi)**
```bash
# Enable mod_rewrite (Apache)
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess di folder public/
```

### 3. **CSRF Token Mismatch**
```env
# .env
SESSION_DRIVER=database  # Atau redis untuk multi-server
SESSION_DOMAIN=.adabkita.gaspul.com
```

### 4. **File Upload Error**
```bash
# Check upload_max_filesize di php.ini
upload_max_filesize = 50M
post_max_size = 50M

# Restart PHP
sudo systemctl restart php8.2-fpm
```

---

## 📞 Support & Maintenance

### Regular Maintenance
```bash
# Weekly tasks
php artisan cache:clear
php artisan queue:restart  # Jika pakai queue

# Monthly tasks
composer update  # Update dependencies
php artisan migrate  # Check for new migrations
```

### Monitoring
- Setup Uptime Monitoring: [UptimeRobot](https://uptimerobot.com/) (Free)
- Error Tracking: [Sentry](https://sentry.io/) (Free tier)
- Database Backup: Pastikan berjalan otomatis

---

## ✅ Kesimpulan

**Aplikasi AdabKita:**
- ✅ **Fitur**: Lengkap dan berfungsi dengan baik
- ✅ **Code Quality**: Baik, mengikuti Laravel best practices
- ❌ **Production Ready**: **BELUM**, masih perlu konfigurasi

**Waktu yang Dibutuhkan untuk Production:**
- Setup Environment: 30 menit
- Database Migration: 15 menit
- Optimization: 15 menit
- SSL Setup: 30 menit
- Testing: 1 jam
- **TOTAL: ~2.5 jam**

**Prioritas:**
1. 🔴 Fix environment config (.env)
2. 🔴 Setup database production
3. 🔴 Set file permissions
4. 🟡 Install SSL certificate
5. 🟡 Run optimization commands
6. 🟢 Setup backup & monitoring

---

## 📚 Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/11.x/deployment)
- [Laravel Forge](https://forge.laravel.com/) - Automated deployment (paid)
- [Laravel Vapor](https://vapor.laravel.com/) - Serverless deployment (paid)
- [DigitalOcean Laravel Deployment Guide](https://www.digitalocean.com/community/tutorials/how-to-deploy-laravel-applications)

---

**Generated on:** 2025-10-26
**Version:** 1.0
**Last Updated:** After certificate download feature activation
