# Deployment ke Vercel - Langkah-Langkah

## Persiapan Database
Karena Vercel adalah serverless, Anda perlu database PostgreSQL cloud:

### Opsi Database (pilih salah satu):
1. **Neon** (Gratis): https://neon.tech
2. **Supabase** (Gratis): https://supabase.com
3. **Railway** (Gratis dengan limit): https://railway.app
4. **Vercel Postgres** (Paid): https://vercel.com/docs/storage/vercel-postgres

## Langkah Deploy

### 1. Install Vercel CLI
```bash
npm install -g vercel
```

### 2. Setup Database Cloud
- Buat database PostgreSQL di salah satu provider di atas
- Catat connection string (contoh: `postgresql://user:pass@host:5432/dbname`)

### 3. Generate APP_KEY
```bash
php artisan key:generate --show
```
Copy outputnya (contoh: `base64:xxx...`)

### 4. Deploy ke Vercel
```bash
cd /home/bayu/Documents/Archive/bayu/Magang/crm-app
vercel
```

Ikuti prompt:
- Set up and deploy? **Y**
- Which scope? Pilih account Anda
- Link to existing project? **N**
- Project name? **crm-app** (atau nama lain)
- Directory? **./** (enter)
- Override settings? **N**

### 5. Set Environment Variables di Vercel Dashboard

Buka: https://vercel.com/[your-username]/[project-name]/settings/environment-variables

Tambahkan variable berikut:

```
APP_NAME="PT.Smart CRM"
APP_KEY=base64:xxx... (dari step 3)
APP_DEBUG=false
APP_URL=https://your-app.vercel.app

DB_CONNECTION=pgsql
DB_HOST=your-database-host
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

LOG_CHANNEL=stderr
```

### 6. Redeploy Setelah Set Env Variables
```bash
vercel --prod
```

### 7. Run Migrations di Database Cloud
Gunakan Vercel CLI atau koneksi langsung:
```bash
# Via psql (install dulu: sudo apt install postgresql-client)
psql "postgresql://user:pass@host:5432/dbname" -f database/migrations/*.sql

# Atau via Laravel Tinker di Vercel (tidak recommended untuk production)
```

**Cara lebih mudah**: Hubungkan ke database cloud Anda dengan tool seperti DBeaver/pgAdmin dan jalankan migrations secara manual.

### 8. Seed Database (Opsional)
Untuk membuat admin user, koneksi ke database dan jalankan:
```sql
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES ('Admin', 'admin@smart.com', '$2y$12$LmxCT4Z7q1K3x5d...bcrypt-hash...', NOW(), NOW());
```

Atau gunakan psql:
```bash
psql "your-connection-string"
\i database/seeders/sql-export.sql  # jika ada
```

## Catatan Penting

### Limitasi Vercel untuk Laravel:
1. **Serverless**: Setiap request adalah cold start
2. **No persistent storage**: File uploads harus ke S3/Cloudinary
3. **Timeout**: Max 10 detik per request (hobby plan)
4. **Read-only filesystem**: Kecuali `/tmp`

### File yang Sudah Dibuat:
- `vercel.json` - Konfigurasi routing dan functions (bukan builds!)
- `api/index.php` - Entry point untuk Vercel serverless dengan document root yang benar
- `.vercelignore` - File yang tidak perlu di-upload

### Troubleshooting Common Errors:

**404 NOT_FOUND Error:**
- Pastikan `api/index.php` ada dan bisa diakses
- Check bahwa routing di `vercel.json` menggunakan `functions` bukan `builds`
- Verifikasi static assets (CSS/JS) ada di folder `public/`
- Cek Vercel logs: `vercel logs [deployment-url]`

**500 Internal Server Error:**
- Check Vercel logs di dashboard atau via CLI
- Pastikan APP_KEY sudah di-set di environment variables
- Verifikasi semua dependencies ada di `composer.json`

**Database Connection Error:**
- Pastikan IP Vercel diizinkan di database cloud Anda (biasanya allow all: 0.0.0.0/0)
- Check connection string format yang benar
- Untuk PostgreSQL: `postgresql://user:pass@host:5432/dbname?sslmode=require`

**Session/Auth Issues:**
- HARUS gunakan `SESSION_DRIVER=cookie` (bukan file, karena serverless)
- Set `SESSION_SECURE_COOKIE=true` untuk HTTPS
- Pastikan `APP_KEY` sudah benar

**Static Assets Not Loading (CSS/JS):**
- Check bahwa file ada di `public/` folder
- Verifikasi routing regex di `vercel.json` match dengan ekstensi file Anda
- Clear Vercel cache: redeploy dengan `vercel --prod --force`

### Alternative: Deploy di Platform Lain
Jika Vercel terlalu kompleks, pertimbangkan:
- **Laravel Forge + DigitalOcean** (Paid tapi lebih stabil)
- **Heroku** (Mudah untuk Laravel)
- **Railway** (Gratis, support PHP native)
- **Fly.io** (Gratis tier bagus)
