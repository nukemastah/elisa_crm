# CRM Application - PT. Smart ISP

Customer Relationship Management system untuk PT. Smart (Internet Service Provider) yang membantu divisi sales dalam mengelola calon customer (leads), produk layanan internet, project penjualan, dan customer berlangganan.

## 📋 Informasi Project

**Tanggal Pengerjaan:**
- Mulai: 15 Desember 2025
- Selesai: 18 Desember 2025

**Stack Teknologi:**
- Laravel 11
- PHP 8.5
- PostgreSQL 14
- Simple.css Framework
- Vanilla JavaScript

## 🚀 Fitur Aplikasi

### 1. Authentication
- ✅ Halaman login dengan session-based authentication
- ✅ Logout functionality
- ✅ Middleware untuk proteksi route

### 2. Manajemen Leads (Calon Customer)
- ✅ List semua leads dengan informasi lengkap
- ✅ Tambah lead baru (nama, email, phone, address, source, status)
- ✅ Edit data lead
- ✅ Status tracking (new, contacted, qualified, lost)
 - ✅ **Assignment** - Optional assignment ke user tertentu

### 3. Master Produk (Layanan Internet)
- ✅ List semua produk layanan
- ✅ Tambah produk baru (code, name, description, monthly_price)
- ✅ Edit data produk
- ✅ Format harga dengan pemisah ribuan

### 4. Manajemen Project
- ✅ Proses konversi lead menjadi project
- ✅ Assignment produk ke project
- ✅ Estimasi biaya project
 - ✅ **Approval Manager** - Approve/Reject langsung dari list
- ✅ Status tracking (pending, approved, rejected, completed)
 - ✅ **Approval Notes** - Catatan untuk manager (optional)

### 5. Customer Berlangganan
- ✅ List customer yang sudah berlangganan
- ✅ Tambah customer baru dengan link ke lead
- ✅ **Multiple Services** - Customer dapat berlangganan beberapa layanan sekaligus
- ✅ Detail layanan per customer (start_date, end_date, monthly_fee, status)
- ✅ Tracking tanggal bergabung (joined_at)

### 6. User Interface
- ✅ Responsive design dengan sidebar navigation
- ✅ Burger menu untuk mobile view
- ✅ Consistent color scheme (purple primary, red cancel)
- ✅ Clean and modern UI dengan good contrast
 - ✅ Dashboard menampilkan ringkasan Leads & Projects dalam tabel (read-only)

## 📁 Struktur Database

### Tables:
1. **users** - User untuk login dan assignment
2. **leads** - Calon customer
3. **products** - Master produk layanan internet
4. **projects** - Project penjualan dengan approval workflow
5. **customers** - Customer yang sudah berlangganan
6. **customer_services** - Pivot table untuk customer-product (many-to-many)
7. **approvals** - History approval (optional, untuk tracking)

Lihat **DATA_DICTIONARY.md** untuk detail lengkap struktur database.

## 🔧 Instalasi & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- PostgreSQL 14
- Node.js & NPM (optional, untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd crm-app
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**

Edit file `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crm_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

5. **Setup Database dengan Docker (Recommended)**
```bash
docker run -d \
  --name crm_postgres \
  -e POSTGRES_DB=crm_db \
  -e POSTGRES_USER=postgres \
  -e POSTGRES_PASSWORD=postgres \
  -p 5432:5432 \
  postgres:14
```

6. **Jalankan Migration**
```bash
php artisan migrate
```

7. **Import Sample Data (Optional)**
```bash
# Jika ada file database/schema.sql
psql -U postgres -d crm_db -f database/schema.sql
```

8. **Buat User untuk Login**
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@smart.com',
    'password' => bcrypt('password123')
]);
```

9. **Jalankan Aplikasi**
```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

**Login Credentials:**
- Email: admin@smart.com
- Password: password123

## ☁️ Deploy di Render.com

### Ringkas
- Platform: Render.com (PHP 8.2 + Laravel via Docker)
- Database: PostgreSQL (included, free tier 90 hari)
- Web Service: Free tier 750 jam/bulan

### 1) Persiapan File
File sudah disiapkan:
- [render.yaml](render.yaml) - Blueprint untuk web service + database
- [Dockerfile](Dockerfile) - Container config untuk PHP 8.2 + Laravel

### 2) Deploy ke Render
1. Push kode ke GitHub (jika belum)
   ```bash
   git push origin main
   ```

2. Login ke [Render.com](https://render.com) (bisa pakai GitHub)

3. Klik **New Blueprint Instance**

4. Connect repository GitHub Anda (crm-app)

5. Render akan otomatis detect `render.yaml` dan create:
   - PostgreSQL database (`crm-db`)
   - Web service (`crm-app`) dengan auto-deploy dari GitHub

6. Tunggu build selesai (~5-10 menit pertama kali)

### 3) Set Environment Variables (Optional)
Render akan auto-set dari `render.yaml`, tapi Anda bisa tambahkan di Dashboard → Environment:
- `APP_KEY` akan di-generate otomatis saat build
- `APP_URL` akan di-set ke URL Render Anda
- `DATABASE_URL` auto-linked dari database `crm-db`

### 4) Buat User Admin (Sekali Saja)
Setelah deploy berhasil, buka Shell di Render Dashboard:
```bash
php artisan tinker
```
```php
\App\Models\User::create([
  'name' => 'Admin',
  'email' => 'admin@smart.com',
  'password' => bcrypt('password123')
]);
```

### 5) Akses Aplikasi
- URL publik: `https://crm-app-xxxx.onrender.com` (lihat di Render Dashboard)
- Login dengan admin@smart.com / password123

### Troubleshooting Render
- **Build failed**: Periksa Logs di Render Dashboard, pastikan `Dockerfile` valid.
- **Database connection error**: Pastikan `DATABASE_URL` sudah ter-link di Environment Variables.
- **Migration gagal**: Jalankan manual via Shell: `php artisan migrate --force`
- **App sleep di free tier**: Free tier sleep setelah 15 menit idle; akan bangun otomatis saat diakses (butuh ~30 detik).

### Auto-Deploy
- Setiap `git push` ke branch `main` akan trigger auto-deploy di Render.
- Lihat progress di Render Dashboard → Events.

Buka dengan aplikasi [draw.io](https://app.diagrams.net/) untuk melihat visualisasi hubungan antar table.

**Key Relationships:**
- Leads → Projects (One to Many)
- Products → Projects (One to Many)
 - Leads → Customers (One to Many, optional)
 - Customers → Customer Services → Products (Many to Many)
 - Users → Leads (Optional assignment field)
 - Users → Projects (Optional manager_id on approval)
 - Projects → Approvals (history log per decision)

## 🗂️ Dokumentasi Tambahan

- **DATA_DICTIONARY.md** - Penjelasan lengkap struktur database, field types, constraints, dan relationships
- **SYSTEM_ANALYST_GUIDE.md** - Panduan untuk System Analyst memahami flow aplikasi, business logic, dan use cases
- **database/schema.sql** - SQL dump untuk import database
- **drawio/er_diagram.drawio** - Entity Relationship Diagram

## 🎯 Use Case Flow

### 1. Lead Management Flow
```
Sales → Input Lead Baru → Assign ke Sales Person → Follow Up → Update Status
```

### 2. Project Creation Flow
```
Sales → Pilih Lead → Pilih Produk → Input Estimasi Fee → Submit Project → 
Manager Review → Approve/Reject → Jika Approved → Buat Customer
```

### 3. Customer Subscription Flow
```
Sales → Buat Customer Baru → Link ke Lead (optional) → 
Pilih Multiple Services → Set Start/End Date → Set Monthly Fee → Save
```

## 🔐 Best Practices yang Diterapkan

1. **Security**
   - Session-based authentication
   - Middleware protection untuk semua route
   - CSRF protection
   - SQL injection prevention via Eloquent ORM

2. **Code Quality**
   - MVC Architecture
   - Eloquent ORM untuk database abstraction
   - Resource Controllers
   - Route naming conventions
   - Blade templating dengan component reusability

3. **Database**
   - Foreign key constraints
   - Cascade delete untuk data integrity
   - Null on delete untuk optional relationships
   - Proper indexing (unique constraints)
   - Timestamps untuk audit trail

4. **UI/UX**
   - Responsive design
   - Consistent color scheme
   - Clear navigation
   - Form validation feedback
   - Mobile-friendly dengan burger menu

## 🚧 Future Improvements

- [ ] Role-based access control (RBAC)
- [ ] Dashboard dengan statistik dan charts
- [ ] Export data to Excel/PDF
- [ ] Email notifications untuk approval
- [ ] Activity log untuk audit trail
- [ ] Advanced search dan filtering
- [ ] Pagination untuk large datasets
- [ ] API endpoints untuk mobile app integration

## 📝 Notes

- Application menggunakan Simple.css framework untuk styling
- Semua form memiliki consistent button styling (purple submit, red cancel)
- Database menggunakan PostgreSQL 14 sesuai requirements
- Session-based authentication (simple, no need for Sanctum/Passport)
- Approval workflow implemented di Project management

## 🛠️ Troubleshooting

### Database Connection Error
```bash
# Check PostgreSQL is running
docker ps | grep crm_postgres

# Restart container
docker restart crm_postgres
```

### View Not Found
```bash
php artisan view:clear
php artisan config:clear
```

### Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
```

## 📧 Contact

Untuk pertanyaan atau masalah, silakan buka issue di repository ini.

## 📄 License

This project is proprietary software developed for PT. Smart ISP.
