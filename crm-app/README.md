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

## ☁️ Deploy di Replit + Cloud Postgres

### Ringkas
- Platform: Replit (PHP 8.2 + Laravel)
- Database: Cloud Postgres (Neon/Supabase/Railway/Render Postgres)

### 1) Persiapan Replit
- File sudah disiapkan: [.replit](.replit), [replit.nix](replit.nix), [start-replit.sh](start-replit.sh)
- Nix config memuat `php82` dan ekstensi `pdo_pgsql` untuk Postgres.
- Jika Anda mengubah `replit.nix`, klik Rebuild/Restart environment di Replit agar dependensi terpasang ulang.

### 2) Set Replit Secrets (Environment Variables)
Tambahkan secrets berikut di Replit (Tools → Secrets):
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<url-replit-anda>`
- `DB_CONNECTION=pgsql`
- `DB_HOST=<host dari provider Postgres>`
- `DB_PORT=5432` (atau sesuai provider)
- `DB_DATABASE=<nama database>`
- `DB_USERNAME=<user database>`
- `DB_PASSWORD=<password database>`
- `SESSION_DRIVER=database` (disarankan)

Catatan:
- `APP_KEY` akan dibuat otomatis oleh script start jika belum ada.
- `SESSION_DRIVER=database` akan otomatis membuat migration sessions (via start script) jika belum ada.

### 3) Ambil Kredensial dari Provider
- Neon: pada Project → Connection Details → “Direct Connection”, ambil host, db, user, password.
- Supabase: Project Settings → Database → Connection string (pilih “URI” atau “Non-psql clients”) lalu petakan ke variabel di atas.

### 4) Jalankan Migrasi (sekali saat setup)
Buka Shell di Replit dan jalankan:
```
php artisan migrate --force
```

Catatan: start script juga menjalankan migrate dan akan membuat migration sessions jika `SESSION_DRIVER=database` dan migration belum ada.

### 5) Menjalankan Aplikasi
- Klik Run di Replit. Script [start-replit.sh](start-replit.sh) akan:
   - Install composer dependencies (jika belum)
   - Generate `APP_KEY` (jika belum)
   - Cache config/route/view
   - Membuat migration sessions jika `SESSION_DRIVER=database` dan belum ada
   - Menjalankan migrasi
   - Menjalankan Laravel pada host `0.0.0.0` dan port Replit (`PORT`)
- Akses via URL publik Replit Anda (atau Preview di Replit).

### Troubleshooting Replit
- "could not find driver" (pdo_pgsql): pastikan [replit.nix](replit.nix) mengandung `php82Extensions.pdo_pgsql`, lalu Rebuild environment.
- "SQLSTATE[08006]": cek `DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD` sudah benar dan database dapat diakses publik.
- "APP_KEY missing": jalankan `php artisan key:generate --force` di Shell (script start juga mencoba otomatis).

## 📊 Entity Relationship Diagram

ERD tersedia dalam file: `drawio/er_diagram.drawio`

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
