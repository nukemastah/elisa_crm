# Deploy Laravel ke Render.com

## Keuntungan Render.com
- ✅ **Free tier** dengan database PostgreSQL gratis
- ✅ **Native PHP support** - tidak perlu serverless complexity
- ✅ **Auto-deploy** dari GitHub
- ✅ **Managed PostgreSQL** sudah include
- ✅ **SSL certificate** otomatis
- ✅ **Persistent storage** (tidak seperti Vercel)

---

## 📋 Langkah-Langkah Deploy

### 1. Persiapan Repository

#### Push ke GitHub (jika belum)
```bash
cd /home/bayu/Documents/Archive/bayu/Magang/crm-app

# Initialize git (kalau belum)
git init
git add .
git commit -m "Initial commit for Render deployment"

# Create repo di GitHub, lalu:
git remote add origin https://github.com/[username]/[repo-name].git
git branch -M main
git push -u origin main
```

### 2. Setup di Render.com

1. **Buka** https://render.com dan **Sign Up** (bisa pakai GitHub)

2. **Klik "New +"** → **"Blueprint"**

3. **Connect Repository:**
   - Pilih repository GitHub Anda
   - Render akan auto-detect `render.yaml`

4. **Deploy:**
   - Klik "Apply"
   - Render akan otomatis:
     - Create PostgreSQL database
     - Deploy Laravel app
     - Link database ke app
     - Set environment variables

5. **Tunggu Build Selesai** (~3-5 menit)

---

## 🔧 Konfigurasi Manual (Alternatif tanpa render.yaml)

Jika tidak mau pakai Blueprint:

### A. Buat Database Dulu

1. Dashboard Render → **New +** → **PostgreSQL**
2. Name: `smart-crm-db`
3. Database: `smart_crm`
4. User: `smart_crm_user`
5. Plan: **Free**
6. Klik **Create Database**
7. **Catat** connection details (Internal/External Connection String)

### B. Buat Web Service

1. Dashboard → **New +** → **Web Service**
2. Connect repository GitHub Anda
3. Settings:
   - **Name:** `smart-crm`
   - **Runtime:** `PHP`
   - **Build Command:** `bash build.sh`
   - **Start Command:** `bash start.sh`
   - **Plan:** `Free`

4. **Environment Variables** (klik Advanced):

```bash
APP_NAME=PT.Smart CRM
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxx... # Generate: php artisan key:generate --show
APP_URL=https://smart-crm.onrender.com  # Sesuaikan dengan URL Render Anda

LOG_CHANNEL=errorlog
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

DB_CONNECTION=pgsql
DB_HOST=xxx.oregon-postgres.render.com  # Dari database dashboard
DB_PORT=5432
DB_DATABASE=smart_crm
DB_USERNAME=smart_crm_user
DB_PASSWORD=xxx  # Dari database dashboard
```

5. **Klik Create Web Service**

---

## 🗄️ Setup Database

### Opsi 1: Auto Migration (Recommended)

Script `start.sh` sudah include:
```bash
php artisan migrate --force
```

Migrations akan otomatis run setiap deploy.

### Opsi 2: Manual Migration

1. **Render Dashboard** → Web Service → **Shell**
2. Jalankan:
```bash
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force
```

### Opsi 3: Via psql (Lokal ke Render DB)

```bash
# Install psql
sudo apt install postgresql-client

# Connect ke Render database (ambil dari dashboard)
psql postgres://smart_crm_user:password@host.render.com:5432/smart_crm

# Run migrations manual jika perlu
\i database/migrations/xxxx_create_users_table.php
```

---

## 🔐 Generate APP_KEY

Jika belum punya APP_KEY:

```bash
cd /home/bayu/Documents/Archive/bayu/Magang/crm-app
php artisan key:generate --show
```

Copy output (contoh: `base64:abcd1234...`) dan paste ke Environment Variables di Render.

---

## 📊 Monitoring & Logs

### Cek Logs
1. Render Dashboard → Your Service → **Logs**
2. Real-time logs akan muncul
3. Cari error messages jika ada masalah

### Cek Database
1. Render Dashboard → Database → **Connect**
2. Gunakan credentials untuk koneksi via:
   - **psql** (command line)
   - **DBeaver** (GUI)
   - **pgAdmin** (GUI)

---

## ⚙️ Update & Redeploy

Setelah push code baru ke GitHub:

### Auto Deploy (Default)
```bash
git add .
git commit -m "Update fitur xxx"
git push origin main
```
Render akan **auto-deploy** dalam 1-2 menit.

### Manual Deploy
Render Dashboard → Service → **Manual Deploy** → **Deploy latest commit**

---

## 🔄 Clear Cache

Jika ada error setelah update:

```bash
# Via Render Shell
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Restart service
# (otomatis setelah deploy)
```

---

## 🎯 Seed Admin User

Setelah migrations run:

### Via Render Shell:
```bash
php artisan db:seed --class=AdminUserSeeder --force
```

### Via psql:
```sql
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES (
  'Admin',
  'admin@smart.com',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  NOW(),
  NOW()
);
```

---

## 🚨 Troubleshooting

### Error: "No APP_KEY"
**Fix:** Generate dan set di Environment Variables
```bash
php artisan key:generate --show
```

### Error: "Database connection refused"
**Fix:** Check environment variables:
- DB_HOST harus Internal Database URL dari Render
- DB_PORT = 5432
- DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai dashboard

### Error: "500 Internal Server Error"
**Fix:** 
1. Check Logs di Render Dashboard
2. Set `APP_DEBUG=true` temporary untuk lihat error detail
3. Pastikan semua migrations sudah run

### Error: "Session not persisting"
**Fix:** Pastikan sudah:
```bash
SESSION_DRIVER=database  # Bukan 'file'
php artisan session:table  # Migration harus ada
```

### Build Gagal
**Fix:**
1. Check `build.sh` ada dan executable
2. Pastikan `composer.json` valid
3. Check PHP version compatibility (PHP 8.2)

---

## 📈 Free Tier Limits

Render Free tier:
- ✅ 750 jam compute/bulan (cukup untuk 1 app 24/7)
- ✅ PostgreSQL 1GB storage
- ✅ Auto-sleep setelah 15 menit inactive
- ✅ Cold start ~30 detik (lebih cepat dari Vercel)

**Tips:** App akan sleep jika tidak ada traffic 15 menit. First request setelah sleep akan lambat (~30s). Request berikutnya normal.

---

## 🔗 Custom Domain (Opsional)

Jika punya domain sendiri:

1. Render Dashboard → Service → **Settings** → **Custom Domain**
2. Add domain: `crm.yourdomain.com`
3. Update DNS records di domain provider:
   ```
   Type: CNAME
   Name: crm
   Value: smart-crm.onrender.com
   ```
4. Wait DNS propagation (~5-10 menit)
5. SSL certificate auto-generated

---

## 📝 File yang Sudah Dibuat

- ✅ `render.yaml` - Blueprint configuration (auto-setup)
- ✅ `build.sh` - Build script untuk Composer & cache
- ✅ `start.sh` - Start script untuk migrations & server
- ✅ `RENDER_DEPLOY.md` - Dokumentasi lengkap (ini)

---

## 🎓 Perbandingan Platform

| Feature | Render | Railway | Vercel |
|---------|--------|---------|--------|
| **Setup Complexity** | ⭐⭐ Easy | ⭐ Easiest | ⭐⭐⭐⭐ Complex |
| **Free Database** | ✅ Yes | ✅ Yes | ❌ No |
| **Persistent Storage** | ✅ Yes | ✅ Yes | ❌ No |
| **Auto-sleep** | ✅ 15 min | ❌ No | N/A |
| **Cold Start** | ~30s | <5s | ~10s |
| **Best For** | Production-ready | Quick prototypes | Static sites/APIs |

---

## ✅ Success Checklist

Setelah deploy sukses:

- [ ] Web service status: **Live**
- [ ] Database status: **Available**
- [ ] Open app URL → Laravel welcome or login page
- [ ] Login dengan `admin@smart.com` / `password123`
- [ ] Check logs tidak ada error
- [ ] Test CRUD operations (create lead, product, dll)
- [ ] Session persists setelah refresh

---

## 🆘 Need Help?

**Render Docs:**
- https://render.com/docs/deploy-php
- https://render.com/docs/databases

**Laravel on Render:**
- https://render.com/docs/deploy-laravel

**Community:**
- Render Community: https://community.render.com

---

**Happy deploying! 🚀**

Render.com jauh lebih cocok untuk Laravel dibanding Vercel. Setup sekali, auto-deploy selamanya!
