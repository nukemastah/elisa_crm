# Data Dictionary - CRM Application PT. Smart ISP

## Overview
Dokumen ini berisi penjelasan lengkap tentang struktur database, field types, constraints, dan relationships yang digunakan dalam aplikasi CRM PT. Smart.

---

## 1. Table: `users`

**Deskripsi:** Menyimpan data user yang dapat login ke sistem dan melakukan assignment atau approval.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap user |
| name | VARCHAR | 255 | NO | - | - | Nama lengkap user |
| email | VARCHAR | 255 | NO | - | UNIQUE | Email user (digunakan untuk login) |
| email_verified_at | TIMESTAMP | - | YES | NULL | - | Tanggal verifikasi email |
| password | VARCHAR | 255 | NO | - | - | Password ter-hash (bcrypt) |
| remember_token | VARCHAR | 100 | YES | NULL | - | Token untuk "remember me" functionality |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `email`

**Relationships:**
- Has Many → `leads` (assigned_to)
- Has Many → `projects` (manager_id)

**Sample Data:**
```sql
INSERT INTO users (name, email, password) VALUES
('Admin User', 'admin@smart.com', '$2y$12$...'),
('Sales Manager', 'manager@smart.com', '$2y$12$...'),
('Sales Person', 'sales@smart.com', '$2y$12$...');
```

---

## 2. Table: `leads`

**Deskripsi:** Menyimpan data calon customer (prospect) yang akan diproses oleh divisi sales.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap lead |
| name | VARCHAR | 255 | NO | - | - | Nama lengkap calon customer |
| phone | VARCHAR | 255 | YES | NULL | - | Nomor telepon |
| email | VARCHAR | 255 | YES | NULL | - | Email calon customer |
| address | TEXT | - | YES | NULL | - | Alamat lengkap |
| source | VARCHAR | 255 | YES | NULL | - | Sumber lead (website, referral, ads, etc) |
| status | VARCHAR | 255 | NO | 'new' | - | Status lead (new, contacted, qualified, lost) |
| assigned_to | BIGINT | - | YES | NULL | FOREIGN KEY → users(id) | User yang bertanggung jawab |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `assigned_to` REFERENCES `users(id)` ON DELETE SET NULL

**Relationships:**
- Belongs To → `users` (assigned_to)
- Has Many → `projects`
- Has One → `customers`

**Status Values:**
- `new` - Lead baru masuk
- `contacted` - Sudah dihubungi
- `qualified` - Qualified untuk jadi project
- `lost` - Tidak jadi customer

**Sample Data:**
```sql
INSERT INTO leads (name, phone, email, address, source, status) VALUES
('PT. Maju Jaya', '08123456789', 'info@majujaya.com', 'Jl. Sudirman No. 123', 'website', 'new'),
('CV. Berkah Abadi', '08234567890', 'admin@berkah.com', 'Jl. Thamrin No. 45', 'referral', 'contacted');
```

---

## 3. Table: `products`

**Deskripsi:** Master data produk layanan internet yang ditawarkan oleh PT. Smart.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap produk |
| code | VARCHAR | 255 | NO | - | UNIQUE | Kode produk (ex: PKG-001) |
| name | VARCHAR | 255 | NO | - | - | Nama produk/paket |
| description | TEXT | - | YES | NULL | - | Deskripsi detail produk |
| monthly_price | DECIMAL | 12,2 | NO | 0.00 | - | Harga bulanan dalam IDR |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `code`

**Relationships:**
- Has Many → `projects`
- Has Many → `customer_services`

**Sample Data:**
```sql
INSERT INTO products (code, name, description, monthly_price) VALUES
('PKG-HOME-10', 'Home 10 Mbps', 'Paket internet rumahan 10 Mbps unlimited', 250000.00),
('PKG-HOME-20', 'Home 20 Mbps', 'Paket internet rumahan 20 Mbps unlimited', 350000.00),
('PKG-BIZ-50', 'Business 50 Mbps', 'Paket internet bisnis 50 Mbps dengan SLA', 1500000.00);
```

---

## 4. Table: `projects`

**Deskripsi:** Menyimpan data project penjualan dari lead yang sedang diproses, termasuk workflow approval manager.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap project |
| lead_id | BIGINT | - | NO | - | FOREIGN KEY → leads(id) | Lead yang diproses menjadi project |
| product_id | BIGINT | - | YES | NULL | FOREIGN KEY → products(id) | Produk yang ditawarkan |
| estimated_fee | DECIMAL | 12,2 | NO | 0.00 | - | Estimasi biaya project |
| status | VARCHAR | 255 | NO | 'pending' | - | Status project |
| manager_approval | BOOLEAN | - | NO | FALSE | - | Flag approval dari manager |
| manager_id | BIGINT | - | YES | NULL | FOREIGN KEY → users(id) | Manager yang approve |
| approval_notes | TEXT | - | YES | NULL | - | Catatan dari manager |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `lead_id` REFERENCES `leads(id)` ON DELETE CASCADE
- FOREIGN KEY: `product_id` REFERENCES `products(id)` ON DELETE SET NULL
- FOREIGN KEY: `manager_id` REFERENCES `users(id)` ON DELETE SET NULL

**Relationships:**
- Belongs To → `leads`
- Belongs To → `products`
- Belongs To → `users` (manager)

**Status Values:**
- `pending` - Menunggu approval manager
- `approved` - Disetujui manager, siap jadi customer
- `rejected` - Ditolak manager
- `completed` - Project selesai, customer sudah dibuat

**Business Logic:**
1. Sales membuat project dari lead
2. Manager review dan approve/reject
3. Jika approved, sales bisa membuat customer
4. Status berubah menjadi completed

**Sample Data:**
```sql
INSERT INTO projects (lead_id, product_id, estimated_fee, status, manager_approval) VALUES
(1, 1, 250000.00, 'pending', false),
(2, 3, 1500000.00, 'approved', true);
```

---

## 5. Table: `customers`

**Deskripsi:** Menyimpan data customer yang sudah berlangganan layanan internet PT. Smart.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap customer |
| name | VARCHAR | 255 | NO | - | - | Nama customer (perusahaan/personal) |
| phone | VARCHAR | 255 | YES | NULL | - | Nomor telepon |
| email | VARCHAR | 255 | YES | NULL | - | Email customer |
| address | TEXT | - | YES | NULL | - | Alamat instalasi |
| joined_at | TIMESTAMP | - | NO | CURRENT_TIMESTAMP | - | Tanggal customer bergabung |
| lead_id | BIGINT | - | YES | NULL | FOREIGN KEY → leads(id) | Link ke lead asal (optional) |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `lead_id` REFERENCES `leads(id)` ON DELETE SET NULL

**Relationships:**
- Belongs To → `leads` (optional)
- Has Many → `customer_services`

**Sample Data:**
```sql
INSERT INTO customers (name, phone, email, address, lead_id, joined_at) VALUES
('PT. Maju Jaya', '08123456789', 'info@majujaya.com', 'Jl. Sudirman No. 123', 1, '2025-01-15');
```

---

## 6. Table: `customer_services`

**Deskripsi:** Pivot table untuk many-to-many relationship antara customers dan products. Satu customer bisa berlangganan beberapa layanan sekaligus.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik untuk setiap subscription |
| customer_id | BIGINT | - | NO | - | FOREIGN KEY → customers(id) | Customer yang berlangganan |
| product_id | BIGINT | - | YES | NULL | FOREIGN KEY → products(id) | Produk yang dilanggani |
| start_date | DATE | - | YES | NULL | - | Tanggal mulai berlangganan |
| end_date | DATE | - | YES | NULL | - | Tanggal akhir berlangganan |
| monthly_fee | DECIMAL | 12,2 | NO | 0.00 | - | Biaya bulanan (bisa custom) |
| status | VARCHAR | 255 | NO | 'active' | - | Status subscription |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp pembuatan record |
| updated_at | TIMESTAMP | - | YES | NULL | - | Timestamp update terakhir |

**Indexes:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `customer_id` REFERENCES `customers(id)` ON DELETE CASCADE
- FOREIGN KEY: `product_id` REFERENCES `products(id)` ON DELETE SET NULL

**Relationships:**
- Belongs To → `customers`
- Belongs To → `products`

**Status Values:**
- `active` - Subscription aktif
- `suspended` - Ditangguhkan (belum bayar, dll)
- `terminated` - Berhenti berlangganan

**Business Logic:**
- Customer bisa subscribe multiple products
- Setiap subscription punya start_date dan end_date sendiri
- Monthly_fee bisa custom (discount, promo, etc)
- Jika customer dihapus, semua services ikut terhapus (CASCADE)

**Sample Data:**
```sql
INSERT INTO customer_services (customer_id, product_id, start_date, end_date, monthly_fee, status) VALUES
(1, 1, '2025-01-15', '2026-01-15', 250000.00, 'active'),
(1, 2, '2025-02-01', '2026-02-01', 350000.00, 'active');
```

---

## 7. Table: `approvals` (Optional - untuk history)

**Deskripsi:** Menyimpan history approval untuk audit trail.

| Column Name | Data Type | Length | Nullable | Default | Constraint | Description |
|-------------|-----------|--------|----------|---------|------------|-------------|
| id | BIGINT | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik |
| project_id | BIGINT | - | NO | - | FOREIGN KEY → projects(id) | Project yang di-approve |
| manager_id | BIGINT | - | NO | - | FOREIGN KEY → users(id) | Manager yang approve |
| action | VARCHAR | 255 | NO | - | - | approved / rejected |
| notes | TEXT | - | YES | NULL | - | Catatan approval |
| created_at | TIMESTAMP | - | YES | NULL | - | Timestamp approval |
| updated_at | TIMESTAMP | - | YES | NULL | - | - |

---

## Database Constraints Summary

### Foreign Key Constraints:
1. `leads.assigned_to` → `users.id` (ON DELETE SET NULL)
2. `projects.lead_id` → `leads.id` (ON DELETE CASCADE)
3. `projects.product_id` → `products.id` (ON DELETE SET NULL)
4. `projects.manager_id` → `users.id` (ON DELETE SET NULL)
5. `customers.lead_id` → `leads.id` (ON DELETE SET NULL)
6. `customer_services.customer_id` → `customers.id` (ON DELETE CASCADE)
7. `customer_services.product_id` → `products.id` (ON DELETE SET NULL)

### Unique Constraints:
1. `users.email`
2. `products.code`

### Default Values:
1. `leads.status` = 'new'
2. `products.monthly_price` = 0.00
3. `projects.estimated_fee` = 0.00
4. `projects.status` = 'pending'
5. `projects.manager_approval` = false
6. `customer_services.monthly_fee` = 0.00
7. `customer_services.status` = 'active'
8. `customers.joined_at` = CURRENT_TIMESTAMP

---

## Data Validation Rules

### Users:
- Email harus unique dan format valid
- Password minimal 8 karakter

### Leads:
- Name wajib diisi
- Phone atau Email minimal salah satu harus ada
- Status harus salah satu dari: new, contacted, qualified, lost

### Products:
- Code harus unique
- Name wajib diisi
- Monthly_price tidak boleh negatif

### Projects:
- Lead_id wajib diisi (required)
- Estimated_fee tidak boleh negatif
- Manager_approval default false
- Status workflow: pending → approved/rejected → completed

### Customers:
- Name wajib diisi
- Joined_at default current timestamp

### Customer Services:
- Customer_id wajib diisi (required)
- Start_date harus sebelum end_date
- Monthly_fee tidak boleh negatif
- Status default 'active'

---

## Cascade Delete Behavior

### When deleted:
- **User** → leads.assigned_to = NULL, projects.manager_id = NULL
- **Lead** → projects deleted (CASCADE), customers.lead_id = NULL
- **Product** → projects.product_id = NULL, customer_services.product_id = NULL
- **Project** → No cascade (standalone)
- **Customer** → customer_services deleted (CASCADE)

---

## Performance Considerations

### Recommended Indexes:
1. `leads.status` - untuk filtering by status
2. `leads.assigned_to` - untuk query by sales person
3. `projects.status` - untuk filtering by status
4. `projects.manager_approval` - untuk quick lookup pending approvals
5. `customer_services.customer_id` - untuk lookup services by customer
6. `customer_services.status` - untuk filtering active/inactive services

### Query Optimization:
- Gunakan eager loading untuk relationships (with())
- Implement pagination untuk large datasets
- Consider adding composite indexes untuk frequent query combinations

---

## Audit Trail

Semua tables memiliki `created_at` dan `updated_at` untuk tracking:
- **created_at**: Kapan record pertama kali dibuat
- **updated_at**: Kapan record terakhir diupdate

Laravel automatically handles timestamps ini jika model memiliki `use HasTimestamps`.

---

## Data Retention Policy (Recommended)

1. **Leads** - Keep all records (history penting)
2. **Projects** - Keep all records (audit trail)
3. **Customers** - Keep active customers, soft delete inactive
4. **Customer Services** - Keep terminated services for 2 years
5. **Approvals** - Keep all approval history (compliance)

---

## Backup Strategy (Recommended)

1. Daily backup database lengkap
2. Weekly backup dengan compression
3. Monthly archive ke cold storage
4. Test restore procedure setiap bulan

---

**Last Updated:** 16 Desember 2025
**Version:** 1.0
**Database Version:** PostgreSQL 14
