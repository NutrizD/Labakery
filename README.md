# 🍰 Toko Kue - Laravel Project

Aplikasi web sederhana berbasis **Laravel** untuk manajemen toko kue.

## 🚀 Fitur

-   Autentikasi (Login & Register)
-   CRUD Produk Kue
-   Manajemen Karyawan
-   Transaksi Pembelian
-   Dashboard Admin

## 🛠️ Teknologi

-   Laravel, PHP
-   MySQL

## ⚙️ Cara Menjalankan

php artisan serve
🔑 Login Awal (Super Admin)
Setelah menjalankan php artisan migrate --seed, gunakan akun berikut untuk login:

Email: superadmin@gmail.com
Password: superadmin123

(akun ini dibuat otomatis oleh SuperAdminSeeder)

📂 Struktur Penting
app/Models → Model database

app/Http/Controllers → Controller

resources/views → Tampilan Blade

routes/web.php → Route aplikasi

🔒 Keamanan
Middleware autentikasi

CSRF Protection

Password terenkripsi
