# 🚀 AdabKita - Production Deployment

## Quick Start untuk Deploy ke Production

Dokumen ini adalah ringkasan cara deploy **AdabKita** ke production server. Untuk panduan lengkap, lihat [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md).

---

## 📋 Checklist Pre-Deployment

Sebelum deploy, pastikan:

- [ ] Server sudah disetup (Ubuntu/Debian/CentOS)
- [ ] PHP 8.2+ terinstall dengan extensions lengkap
- [ ] MySQL/MariaDB terinstall
- [ ] Apache/Nginx terinstall
- [ ] Composer terinstall
- [ ] Domain sudah pointing ke server
- [ ] Akses SSH ke server

---

## 🎯 Deployment Pertama Kali

### 1. Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/echal/adabkita.git
cd adabkita
```

### 2. Setup Database

```bash
chmod +x database-setup.sh
sudo ./database-setup.sh
```

**Output:** Script akan create database, user, dan save credentials.

### 3. Setup Environment

```bash
# Copy template production
cp .env.production .env

# Edit dan sesuaikan (minimal: APP_URL, DB_*)
nano .env

# Generate APP_KEY
php artisan key:generate --force
```

### 4. Install Dependencies & Migrate

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force
php artisan db:seed --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/adabkita
sudo chmod -R 775 storage bootstrap/cache
```

### 6. Configure Web Server

**Apache:**
```bash
sudo cp server-configs/apache-adabkita.conf /etc/apache2/sites-available/
sudo a2ensite apache-adabkita.conf
sudo a2enmod rewrite ssl headers
sudo systemctl reload apache2
```

**Nginx:**
```bash
sudo cp server-configs/nginx-adabkita.conf /etc/nginx/sites-available/adabkita
sudo ln -s /etc/nginx/sites-available/adabkita /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 7. Install SSL Certificate

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache  # atau python3-certbot-nginx

# Generate certificate
sudo certbot --apache -d adabkita.gaspul.com -d www.adabkita.gaspul.com
# atau
sudo certbot --nginx -d adabkita.gaspul.com -d www.adabkita.gaspul.com
```

### 8. Setup Auto Backup

```bash
chmod +x backup-database.sh

# Setup cron job
crontab -e
# Tambahkan: 0 2 * * * /var/www/adabkita/backup-database.sh
```

### ✅ Selesai!

Test aplikasi: `https://adabkita.gaspul.com`

---

## 🔄 Update Deployment

Untuk update aplikasi setelah push code baru:

```bash
cd /var/www/adabkita
chmod +x deploy.sh
sudo ./deploy.sh
```

Script akan otomatis:
- Pull latest code
- Update dependencies
- Run migrations
- Clear & rebuild caches
- Restart services

---

## 📁 File Structure

```
adabkita/
├── .env.production              # Template environment production ⚙️
├── deploy.sh                    # Script update deployment 🔄
├── deploy-first-time.sh         # Script deployment pertama 🎬
├── database-setup.sh            # Setup database production 💾
├── backup-database.sh           # Backup otomatis 💿
├── restore-database.sh          # Restore dari backup ♻️
├── DEPLOYMENT_GUIDE.md          # Panduan lengkap 📖
├── PRODUCTION_READINESS_CHECKLIST.md  # Checklist production ✅
└── server-configs/
    ├── apache-adabkita.conf     # Konfigurasi Apache 🌐
    └── nginx-adabkita.conf      # Konfigurasi Nginx 🌐
```

---

## ⚙️ Environment Production (.env)

### Konfigurasi Wajib

```env
APP_ENV=production              # ⚠️ WAJIB
APP_DEBUG=false                 # ⚠️ WAJIB (keamanan!)
APP_URL=https://your-domain.com # ⚠️ Sesuaikan

DB_CONNECTION=mysql
DB_DATABASE=adabkita_production
DB_USERNAME=adabkita_user
DB_PASSWORD=secure_password     # ⚠️ Password kuat
```

### Konfigurasi Optional (Recommended)

```env
# Mail (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

# Session (untuk multi-server)
SESSION_DRIVER=database
SESSION_DOMAIN=.your-domain.com
SESSION_SECURE_COOKIE=true      # Jika sudah HTTPS
```

---

## 🛡️ Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `APP_ENV=production` di production
- [ ] Database user BUKAN 'root'
- [ ] Database password minimal 16 karakter
- [ ] SSL certificate terinstall (HTTPS)
- [ ] Firewall configured (UFW/iptables)
- [ ] Folder `storage/` dan `bootstrap/cache/` writable
- [ ] File `.env` tidak terupload ke Git
- [ ] Auto backup database aktif
- [ ] Regular security updates

---

## 📊 Monitoring

### Check Logs

```bash
# Application logs
tail -f /var/www/adabkita/storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/adabkita-error.log    # Nginx
tail -f /var/log/apache2/adabkita-error.log  # Apache
```

### Check Status

```bash
# Web server
sudo systemctl status nginx    # atau apache2

# PHP-FPM (Nginx)
sudo systemctl status php8.2-fpm

# Database
sudo systemctl status mysql
```

### Performance

```bash
# Server resources
htop

# Disk usage
df -h
du -sh /var/www/adabkita/storage/

# Database size
mysql -u root -p -e "
SELECT table_schema 'Database',
ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'adabkita_production';
"
```

---

## 🔧 Troubleshooting

### 500 Internal Server Error

```bash
# Check logs
tail -f /var/www/adabkita/storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/adabkita
sudo chmod -R 775 storage bootstrap/cache

# Clear caches
php artisan cache:clear
php artisan config:clear
```

### 404 Not Found

```bash
# Apache: Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess
ls -la /var/www/adabkita/public/.htaccess
```

### Database Connection Failed

```bash
# Test connection
mysql -u adabkita_user -p adabkita_production

# Check credentials di .env
grep DB_ .env

# Check MySQL running
sudo systemctl status mysql
```

---

## 💾 Backup & Restore

### Manual Backup

```bash
./backup-database.sh
```

Backup tersimpan di: `/backup/adabkita/database/`

### Restore Database

```bash
# List backups
ls -lh /backup/adabkita/database/

# Restore
./restore-database.sh /backup/adabkita/database/adabkita_20251026_020000.sql.gz
```

---

## 📚 Dokumentasi Lengkap

- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Panduan deployment lengkap step-by-step
- **[PRODUCTION_READINESS_CHECKLIST.md](PRODUCTION_READINESS_CHECKLIST.md)** - Analisis kesiapan production
- **Laravel Docs**: https://laravel.com/docs/11.x/deployment

---

## 🆘 Support

### Common Issues

1. **Permission Denied** → Check ownership (`www-data`) dan permissions (775)
2. **CSRF Token Mismatch** → Check `SESSION_DOMAIN` di .env
3. **File Upload Error** → Check `upload_max_filesize` di php.ini
4. **SSL Certificate Error** → Run `certbot renew --force-renewal`

### Monitoring Tools (Free)

- **Uptime**: [UptimeRobot](https://uptimerobot.com/)
- **Errors**: [Sentry](https://sentry.io/)
- **Performance**: [Netdata](https://www.netdata.cloud/)

---

## ✅ Production Checklist Summary

| Task | Status | Priority |
|------|--------|----------|
| Edit .env untuk production | ⚠️ TODO | 🔴 CRITICAL |
| Setup database MySQL | ⚠️ TODO | 🔴 CRITICAL |
| Set file permissions | ⚠️ TODO | 🔴 CRITICAL |
| Configure web server | ⚠️ TODO | 🔴 CRITICAL |
| Install SSL certificate | ⚠️ TODO | 🟡 HIGH |
| Setup auto backup | ⚠️ TODO | 🟡 HIGH |
| Configure monitoring | ⚠️ TODO | 🟢 MEDIUM |

**Estimasi Waktu Setup:** 2-3 jam

---

## 🎉 Next Steps After Deployment

1. ✅ Test semua fitur aplikasi
2. ✅ Login sebagai admin dan ganti password
3. ✅ Test sertifikat PDF generation
4. ✅ Test pembelajaran interaktif
5. ✅ Setup monitoring (UptimeRobot, Sentry)
6. ✅ Dokumentasi credentials (simpan aman!)
7. ✅ Schedule regular maintenance

---

**Repository:** https://github.com/echal/adabkita.git

**Version:** 1.0

**Last Updated:** 2025-10-26

**Happy Deploying! 🚀**
