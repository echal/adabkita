# ⚡ Quick Start - Deploy via cPanel

## Ringkasan Super Cepat untuk Deployment di cPanel

**Estimasi Waktu:** 30-45 menit

---

## 📋 Checklist Sebelum Mulai

- [ ] Login ke cPanel sudah bisa
- [ ] Domain sudah pointing ke hosting
- [ ] Punya akses File Manager & Terminal

---

## 🚀 10 Langkah Deploy

### **1️⃣ Create Database** (5 menit)

**cPanel → MySQL Databases**

1. Create Database: `adabkita_prod`
2. Create User: `adabkita_user` (gunakan Password Generator!)
3. Add User to Database (ALL PRIVILEGES)
4. **SIMPAN credentials:**
   ```
   DB: cpaneluser_adabkita_prod
   User: cpaneluser_adabkita_user
   Pass: [generated-password]
   ```

---

### **2️⃣ Upload Files** (10 menit)

**Option A: Via Terminal (Recommended)**

```bash
cd public_html
git clone https://github.com/echal/adabkita.git .
```

**Option B: Via File Manager**

1. Download ZIP dari GitHub
2. Upload ke `public_html`
3. Extract files

---

### **3️⃣ Install Dependencies** (5 menit)

**Terminal:**
```bash
composer install --no-dev --optimize-autoloader
```

Jika error memory:
```bash
php -d memory_limit=512M /usr/local/bin/composer install --no-dev
```

---

### **4️⃣ Configure .env** (5 menit)

```bash
cp .env.production .env
nano .env
```

**Edit minimal:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_DATABASE=cpaneluser_adabkita_prod
DB_USERNAME=cpaneluser_adabkita_user
DB_PASSWORD=[password-dari-step-1]
```

Save: **Ctrl+O** → **Enter** → **Ctrl+X**

---

### **5️⃣ Generate Key** (1 menit)

```bash
php artisan key:generate --force
```

---

### **6️⃣ Run Migrations** (3 menit)

```bash
php artisan migrate --force
php artisan db:seed --force
```

---

### **7️⃣ Set Permissions** (1 menit)

```bash
chmod -R 775 storage bootstrap/cache
```

Jika error, coba:
```bash
chmod -R 777 storage bootstrap/cache
```

---

### **8️⃣ Optimize** (2 menit)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### **9️⃣ Set Document Root** (3 menit)

**cPanel → Domains → Manage**

Ubah Document Root dari:
```
/home/username/public_html
```
Ke:
```
/home/username/public_html/public
```

---

### **🔟 Install SSL** (5 menit)

**cPanel → SSL/TLS Status**

1. Pilih domain
2. Klik **"Run AutoSSL"**
3. Tunggu 1-5 menit

---

## ✅ Test Aplikasi

Buka browser:
```
https://your-domain.com
```

**Default Login:**
- Email: `admin@adabkita.com`
- Password: `password`

**✅ SELESAI!** Jika homepage dan login bekerja, deployment berhasil! 🎉

---

## 🔧 Troubleshooting Cepat

### 500 Error
```bash
chmod -R 777 storage bootstrap/cache
php artisan cache:clear
```

### 404 Error
- Check document root pointing ke `/public`
- Check `.htaccess` exists di folder public

### Database Error
- Verify credentials di `.env`
- Use `localhost` untuk DB_HOST
- Check user has privileges

### Composer Not Found
```bash
# Try full path
/usr/local/bin/composer install --no-dev
```

---

## 📚 Dokumentasi Lengkap

Untuk panduan detail, baca:
- **[DEPLOYMENT_CPANEL_GUIDE.md](DEPLOYMENT_CPANEL_GUIDE.md)** - Panduan lengkap dengan screenshots
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Panduan untuk VPS/Dedicated Server

---

## 🆘 Butuh Bantuan?

1. Check logs: `tail -50 storage/logs/laravel.log`
2. Check cPanel error logs: **Metrics → Errors**
3. Hubungi hosting support jika masalah dengan:
   - PHP version
   - Composer
   - mod_rewrite
   - Memory limit

---

**Quick Reference Created:** 2025-10-26

**Happy Deploying! 🚀**
