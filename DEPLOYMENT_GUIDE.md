# 🚀 AdabKita Deployment Guide

## Panduan Lengkap Deploy ke Production

Dokumen ini berisi panduan step-by-step untuk deploy aplikasi **AdabKita** ke production server.

---

## 📋 Daftar Isi

1. [Persiapan Server](#persiapan-server)
2. [Deployment Pertama Kali](#deployment-pertama-kali)
3. [Update/Deploy Selanjutnya](#update-deployment)
4. [Konfigurasi Web Server](#konfigurasi-web-server)
5. [Setup SSL Certificate](#setup-ssl-certificate)
6. [Backup & Restore](#backup--restore)
7. [Monitoring & Maintenance](#monitoring--maintenance)
8. [Troubleshooting](#troubleshooting)

---

## 📦 File-File Deployment

Setelah menjalankan setup, Anda akan memiliki file-file berikut:

```
adabkita/
├── .env.production          # Template environment production
├── deploy.sh                # Script deployment update
├── deploy-first-time.sh     # Script deployment pertama kali
├── database-setup.sh        # Script setup database
├── backup-database.sh       # Script backup database otomatis
├── restore-database.sh      # Script restore database
└── server-configs/
    ├── apache-adabkita.conf # Konfigurasi Apache VirtualHost
    └── nginx-adabkita.conf  # Konfigurasi Nginx Server Block
```

---

## 1️⃣ Persiapan Server

### Minimum Requirements

- **OS**: Ubuntu 20.04+ / Debian 11+ / CentOS 8+
- **PHP**: 8.2+
- **Database**: MySQL 8.0+ / MariaDB 10.5+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Memory**: 2GB RAM minimum (4GB recommended)
- **Storage**: 10GB+ free space
- **Composer**: Latest version

### Install Dependencies (Ubuntu/Debian)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install -y php8.2 php8.2-{cli,fpm,mysql,mbstring,xml,curl,zip,gd,bcmath,intl}

# Install MySQL
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install -y git

# Install Web Server (pilih salah satu)
# Apache:
sudo apt install -y apache2

# Atau Nginx:
sudo apt install -y nginx
```

### Install Dependencies (CentOS/RHEL)

```bash
# Update system
sudo yum update -y

# Install PHP repository
sudo yum install -y epel-release
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm
sudo yum module reset php
sudo yum module enable php:remi-8.2

# Install PHP and extensions
sudo yum install -y php php-{cli,fpm,mysqlnd,mbstring,xml,curl,zip,gd,bcmath,intl}

# Install MySQL
sudo yum install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo yum install -y git

# Install Web Server
# Apache:
sudo yum install -y httpd

# Atau Nginx:
sudo yum install -y nginx
```

---

## 2️⃣ Deployment Pertama Kali

### Step 1: Clone Repository

```bash
# Clone dari GitHub
cd /var/www
sudo git clone https://github.com/echal/adabkita.git
cd adabkita

# Atau upload via FTP/SFTP
# Upload semua file ke /var/www/adabkita
```

### Step 2: Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/adabkita
sudo chmod -R 775 /var/www/adabkita/storage
sudo chmod -R 775 /var/www/adabkita/bootstrap/cache
```

### Step 3: Setup Database

```bash
cd /var/www/adabkita
chmod +x database-setup.sh
sudo ./database-setup.sh

# Script akan:
# 1. Tanya nama database (default: adabkita_production)
# 2. Tanya username database (default: adabkita_user)
# 3. Generate password otomatis (atau input manual)
# 4. Create database dan user
# 5. Test connection
# 6. Save credentials ke database-credentials.txt
```

**Output:**
```
Database berhasil dibuat:
  Host: localhost
  Database: adabkita_production
  Username: adabkita_user
  Password: [generated-password]

Credentials tersimpan di: database-credentials.txt
```

### Step 4: Setup Environment

```bash
# Copy template .env
cp .env.production .env

# Edit .env dan sesuaikan konfigurasi
nano .env
```

**Konfigurasi Wajib di .env:**
```env
APP_NAME="AdabKita"
APP_ENV=production
APP_DEBUG=false                           # WAJIB false!
APP_URL=https://adabkita.gaspul.com       # Domain Anda

# Database (dari output database-setup.sh)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adabkita_production
DB_USERNAME=adabkita_user
DB_PASSWORD=[password-dari-database-setup]

# Mail (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@adabkita.gaspul.com
```

### Step 5: Install Dependencies & Generate Key

```bash
# Install Composer dependencies (production only)
composer install --no-dev --optimize-autoloader

# Generate APP_KEY
php artisan key:generate --force

# Verify APP_KEY terisi
grep APP_KEY .env
# Output harus: APP_KEY=base64:...
```

### Step 6: Run Migrations & Seeders

```bash
# Run database migrations
php artisan migrate --force

# Seed initial data (user, dll)
php artisan db:seed --force

# Verify migration status
php artisan migrate:status
```

### Step 7: Optimize Application

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

### Step 8: Setup Web Server

**Untuk Apache:**
```bash
# Copy konfigurasi
sudo cp server-configs/apache-adabkita.conf /etc/apache2/sites-available/

# Edit domain dan path jika perlu
sudo nano /etc/apache2/sites-available/apache-adabkita.conf

# Enable site dan modules
sudo a2ensite apache-adabkita.conf
sudo a2enmod rewrite ssl headers

# Test configuration
sudo apache2ctl configtest

# Reload Apache
sudo systemctl reload apache2
```

**Untuk Nginx:**
```bash
# Copy konfigurasi
sudo cp server-configs/nginx-adabkita.conf /etc/nginx/sites-available/adabkita

# Edit domain dan path jika perlu
sudo nano /etc/nginx/sites-available/adabkita

# Create symlink
sudo ln -s /etc/nginx/sites-available/adabkita /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### Step 9: Setup SSL Certificate

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache  # Untuk Apache
# atau
sudo apt install -y certbot python3-certbot-nginx   # Untuk Nginx

# Generate SSL certificate
sudo certbot --apache -d adabkita.gaspul.com -d www.adabkita.gaspul.com
# atau
sudo certbot --nginx -d adabkita.gaspul.com -d www.adabkita.gaspul.com

# Auto-renewal sudah disetup oleh certbot
# Test renewal:
sudo certbot renew --dry-run
```

### Step 10: Verify Deployment

```bash
# Test aplikasi
curl https://adabkita.gaspul.com

# Check PHP-FPM (jika pakai Nginx)
sudo systemctl status php8.2-fpm

# Check logs
tail -f /var/www/adabkita/storage/logs/laravel.log
```

**✅ Deployment Pertama Selesai!**

---

## 3️⃣ Update Deployment

Untuk update aplikasi setelah ada perubahan code:

### Option 1: Menggunakan Script Otomatis

```bash
cd /var/www/adabkita
chmod +x deploy.sh
sudo ./deploy.sh
```

Script akan otomatis:
1. Enable maintenance mode
2. Pull latest code dari Git
3. Install/update dependencies
4. Run migrations
5. Clear & rebuild caches
6. Disable maintenance mode
7. Restart queue workers (jika ada)

### Option 2: Manual Update

```bash
cd /var/www/adabkita

# Enable maintenance mode
php artisan down

# Pull latest code
git pull origin main

# Update dependencies (jika ada perubahan composer.json)
composer install --no-dev --optimize-autoloader

# Run migrations (jika ada migration baru)
php artisan migrate --force

# Clear & rebuild caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Disable maintenance mode
php artisan up

# Restart PHP-FPM (jika pakai Nginx)
sudo systemctl restart php8.2-fpm
```

---

## 4️⃣ Konfigurasi Web Server

### Apache Configuration

File: `server-configs/apache-adabkita.conf`

**Features:**
- ✅ Auto redirect HTTP → HTTPS
- ✅ Laravel rewrite rules
- ✅ Security headers (X-Frame-Options, CSP, HSTS)
- ✅ GZIP compression
- ✅ Browser caching untuk static files
- ✅ Deny access ke sensitive files (.env, .git, dll)

**Document Root:** `/var/www/adabkita/public` (BUKAN root folder!)

### Nginx Configuration

File: `server-configs/nginx-adabkita.conf`

**Features:**
- ✅ HTTP/2 support
- ✅ SSL optimization
- ✅ FastCGI caching
- ✅ Security headers
- ✅ GZIP compression
- ✅ Rate limiting support
- ✅ Deny access ke sensitive files

**Document Root:** `/var/www/adabkita/public`

---

## 5️⃣ Setup SSL Certificate

### Let's Encrypt (Gratis)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache

# Generate certificate
sudo certbot --apache -d adabkita.gaspul.com -d www.adabkita.gaspul.com

# Follow prompts:
# - Email address untuk notifications
# - Agree to terms
# - Redirect HTTP → HTTPS (pilih yes)

# Auto-renewal sudah disetup via cron
# Test: sudo certbot renew --dry-run
```

### SSL Test

Test SSL configuration:
- https://www.ssllabs.com/ssltest/
- Target: A+ rating

---

## 6️⃣ Backup & Restore

### Setup Automatic Backup

```bash
cd /var/www/adabkita

# Make backup script executable
chmod +x backup-database.sh

# Test manual backup
./backup-database.sh

# Setup cron job untuk backup otomatis
crontab -e

# Tambahkan (backup setiap hari jam 2 pagi):
0 2 * * * /var/www/adabkita/backup-database.sh
```

**Backup Storage:**
- Location: `/backup/adabkita/database/`
- Format: `adabkita_[database]_[date].sql.gz`
- Retention: 30 hari (otomatis hapus yang lebih lama)

### Manual Backup

```bash
cd /var/www/adabkita
./backup-database.sh
```

### Restore Database

```bash
cd /var/www/adabkita

# List available backups
ls -lh /backup/adabkita/database/

# Restore dari backup
./restore-database.sh /backup/adabkita/database/adabkita_20251026_020000.sql.gz

# Script akan:
# 1. Create safety backup dari database saat ini
# 2. Verify backup file integrity
# 3. Drop & recreate database (atau import langsung)
# 4. Import data
# 5. Verify restore
# 6. Clear Laravel cache
```

---

## 7️⃣ Monitoring & Maintenance

### Monitor Logs

```bash
# Laravel logs
tail -f /var/www/adabkita/storage/logs/laravel.log

# Apache logs
sudo tail -f /var/log/apache2/adabkita-ssl-error.log
sudo tail -f /var/log/apache2/adabkita-ssl-access.log

# Nginx logs
sudo tail -f /var/log/nginx/adabkita-error.log
sudo tail -f /var/log/nginx/adabkita-access.log

# MySQL logs
sudo tail -f /var/log/mysql/error.log
```

### Disk Space Monitoring

```bash
# Check disk usage
df -h

# Check Laravel storage size
du -sh /var/www/adabkita/storage/

# Check database size
mysql -u root -p -e "
SELECT
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'adabkita_production'
GROUP BY table_schema;
"
```

### Performance Monitoring

```bash
# Server load
htop  # atau top

# MySQL processes
mysql -u root -p -e "SHOW PROCESSLIST;"

# PHP-FPM status (Nginx)
sudo systemctl status php8.2-fpm

# Apache status
sudo systemctl status apache2

# Check slow queries
mysql -u root -p -e "
SELECT * FROM mysql.slow_log
ORDER BY start_time DESC
LIMIT 10;
"
```

### Weekly Maintenance Tasks

```bash
cd /var/www/adabkita

# Clear expired sessions
php artisan session:gc

# Clear old logs (keep 30 days)
find storage/logs/ -name "*.log" -mtime +30 -delete

# Optimize database tables
mysql -u root -p adabkita_production -e "OPTIMIZE TABLE users, lesson_progress, lesson_flow, lesson_item, lesson_jawaban, materi;"

# Check for Laravel updates
composer outdated "laravel/*"
```

---

## 8️⃣ Troubleshooting

### Problem: 500 Internal Server Error

**Check logs:**
```bash
tail -f /var/www/adabkita/storage/logs/laravel.log
```

**Common fixes:**
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/adabkita
sudo chmod -R 775 storage bootstrap/cache

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Problem: 404 Not Found (routes tidak berfungsi)

**Apache:**
```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess di folder public/
ls -la /var/www/adabkita/public/.htaccess
```

**Nginx:**
```bash
# Check try_files directive di config
grep "try_files" /etc/nginx/sites-available/adabkita

# Should be: try_files $uri $uri/ /index.php?$query_string;
```

### Problem: CSRF Token Mismatch

```bash
# Clear sessions
php artisan session:flush

# Check SESSION_DOMAIN di .env
grep SESSION_DOMAIN .env
# Should be: SESSION_DOMAIN=.adabkita.gaspul.com
```

### Problem: Permission Denied untuk Upload File

```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/adabkita/storage
sudo chmod -R 775 /var/www/adabkita/storage

# Check PHP upload settings
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Edit php.ini jika perlu
sudo nano /etc/php/8.2/fpm/php.ini
# Set:
# upload_max_filesize = 50M
# post_max_size = 50M

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Problem: Database Connection Failed

```bash
# Test database connection
mysql -h 127.0.0.1 -u adabkita_user -p adabkita_production

# Check credentials di .env
grep DB_ .env

# Check MySQL is running
sudo systemctl status mysql

# Check MySQL logs
sudo tail -f /var/log/mysql/error.log
```

### Problem: SSL Certificate Issues

```bash
# Renew certificate
sudo certbot renew --force-renewal

# Check certificate expiry
sudo certbot certificates

# Test SSL configuration
curl -I https://adabkita.gaspul.com
```

---

## 📞 Support & Resources

### Official Documentation
- Laravel: https://laravel.com/docs/11.x
- DomPDF: https://github.com/barryvdh/laravel-dompdf

### Monitoring Tools (Recommended)
- **Uptime Monitoring**: [UptimeRobot](https://uptimerobot.com/) (Free)
- **Error Tracking**: [Sentry](https://sentry.io/) (Free tier)
- **Server Monitoring**: [Netdata](https://www.netdata.cloud/) (Free)

### Performance Testing
```bash
# Apache Benchmark
ab -n 1000 -c 10 https://adabkita.gaspul.com/

# Siege
siege -c 10 -t 30s https://adabkita.gaspul.com/
```

---

## 🎉 Kesimpulan

Setelah mengikuti panduan ini, aplikasi **AdabKita** Anda seharusnya sudah:

- ✅ Running di production dengan HTTPS
- ✅ Database ter-configure dengan aman
- ✅ Auto-backup database setiap hari
- ✅ Web server ter-optimize
- ✅ Security headers aktif
- ✅ Ready untuk production traffic

**Happy deploying! 🚀**

---

**Last Updated:** 2025-10-26
**Version:** 1.0
**Maintainer:** AdabKita Development Team
