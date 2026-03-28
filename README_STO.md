# Stock Taking Opname Application

Aplikasi Stock Taking Opname untuk industri manufaktur stamping yang mencakup raw material, WIP (Work In Progress), dan finish part.

## Fitur Utama

### Admin
- **Dashboard**: Menampilkan statistik dan sesi stock taking terbaru
- **User Management**: Mengelola user yang akan melakukan stock taking
- **Item Management**: Mengelola master data item (raw material, WIP, finish part)
- **Session Management**: Membuat dan mengelola sesi stock taking

### User
- **My Sessions**: Melihat sesi stock taking yang ditugaskan
- **Stock Taking Form**: Melakukan input stock taking dengan dropdown item otomatis sesuai kategori

## Instalasi & Setup

1. Clone repository dan install dependencies:
```bash
composer install
npm install
```

2. Copy file .env dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

3. Setup database di file .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sto_app
DB_USERNAME=root
DB_PASSWORD=
```

4. Jalankan migration dan seeder:
```bash
php artisan migrate:fresh --seed
```

5. Build assets dan jalankan server:
```bash
npm run build
php artisan serve
```

## Default User Credentials

### Admin
- Email: admin@sto.com
- Password: password

### Regular Users
- Email: user1@sto.com / user2@sto.com
- Password: password

## Cara Penggunaan

### Untuk Admin

1. **Login sebagai Admin**
   - Gunakan kredensial admin@sto.com / password

2. **Menambah User Baru**
   - Buka menu "Users"
   - Klik "Add New User"
   - Isi form dan pilih role (admin/user)
   - Klik "Create User"

3. **Menambah Item Baru**
   - Buka menu "Items"
   - Klik "Add New Item"
   - Isi kode, nama, kategori (raw_material/wip/finish_part), deskripsi, dan satuan
   - Klik "Create Item"

4. **Membuat Sesi Stock Taking**
   - Buka menu "Sessions"
   - Klik "Create New Session"
   - Pilih user yang akan melakukan stock taking
   - Pilih kategori (raw material, WIP, atau finish part)
   - Tentukan tanggal jadwal
   - Tambahkan notes jika perlu
   - Klik "Create Session"
   - System akan generate kode sesi otomatis (contoh: STO-20260112-0001)

5. **Melihat Hasil Stock Taking**
   - Buka menu "Sessions"
   - Filter berdasarkan status/kategori jika perlu
   - Klik "View" pada sesi yang ingin dilihat
   - Lihat detail variance antara sistem quantity dan actual quantity

### Untuk User

1. **Login sebagai User**
   - Gunakan kredensial yang diberikan admin

2. **Melihat Sesi yang Ditugaskan**
   - Setelah login, Anda akan melihat daftar sesi stock taking yang ditugaskan
   - Status: Pending, In Progress, atau Completed

3. **Melakukan Stock Taking**
   - Klik "Open" pada sesi dengan status "Pending"
   - Klik "Start Stock Taking"
   - Dropdown item akan otomatis terisi sesuai kategori sesi (contoh: jika kategori Raw Material, hanya item raw material yang tampil)
   - Isi data untuk setiap item:
     - **System Quantity**: Jumlah di sistem/record
     - **Actual Quantity**: Jumlah hasil perhitungan fisik
     - **Remarks**: Catatan tambahan (opsional)
   - Klik "Add More Item" untuk menambah item lain
   - Setelah selesai input semua item, klik "Complete Stock Taking"
   - Variance akan dihitung otomatis (Actual - System)

4. **Melihat History**
   - Sesi dengan status "Completed" bisa dilihat detailnya
   - Namun tidak bisa diubah lagi

## Struktur Database

### users
- id, name, email, password, role (admin/user)

### items
- id, code, name, category (raw_material/wip/finish_part), description, unit

### stock_taking_sessions
- id, session_code, user_id, category, status (pending/in_progress/completed), scheduled_date, started_at, completed_at, notes

### stock_taking_details
- id, stock_taking_session_id, item_id, system_quantity, actual_quantity, variance (computed), remarks

## Item Categories

1. **raw_material**: Bahan baku lembar (steel plate, aluminum sheet, dll)
2. **wip**: Work In Progress part (part yang sudah di-stamping tapi belum finish)
3. **finish_part**: Part yang sudah selesai dan siap dikirim

## Fitur Khusus

- **Auto-generated Session Code**: STO-YYYYMMDD-XXXX
- **Dropdown Filter by Category**: Item di form stock taking otomatis terfilter sesuai kategori sesi
- **Auto-calculated Variance**: Variance dihitung otomatis dari (Actual Quantity - System Quantity)
- **Role-based Access**: Admin bisa manage semua, User hanya bisa akses sesi mereka sendiri
- **Session Protection**: Sesi yang sudah completed tidak bisa diubah atau dihapus

## Development Commands

```bash
# Format code dengan Laravel Pint
vendor/bin/pint --dirty

# Run migrations
php artisan migrate

# Fresh migration dengan seed
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Tech Stack

- Laravel 12
- PHP 8.3
- MySQL
- Blade Templates
- Laravel Breeze (Authentication)
- Vite

## Support

Untuk pertanyaan atau issue, silahkan hubungi tim development.
