<div align="center">

# Sistem Informasi Admin Kos
**Sistem Manajemen Kos Modern Berbasis Laravel 12**

[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.4-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

</div>

---

## 📖 Tentang Aplikasi

**Sistem Informasi Admin Kos** adalah aplikasi berbasis web yang dirancang khusus untuk mempermudah operasional dan administrasi rumah kos. Dibangun untuk menangani tugas-tugas harian secara efisien, sistem ini menyediakan fitur untuk mengelola penyewa, mencatat pengeluaran, menangani pembayaran, dan membuat laporan keuangan.

### ✨ Fitur Utama

- **Role-Based Access Control (RBAC):** Sistem login yang aman dengan pembagian hak akses (Admin dan Pegawai), menggunakan `spatie/laravel-permission`.
- **Manajemen Pegawai & Admin:** Pengelolaan data akun staf dengan validasi NIK (Nomor Induk Kependudukan) dan pembuatan kode pegawai unik.
- **Manajemen Penyewa (Tenant):** Memantau data penyewa aktif maupun riwayat penyewa, alokasi kamar, dan masa sewa.
- **Pencatatan Pengeluaran & Keuangan:** Fitur untuk mencatat pengeluaran operasional agar perhitungan laba/pendapatan bersih lebih akurat.
- **Transaksi & Pembayaran:** Pencatatan transaksi pembayaran sewa secara real-time yang sudah disesuaikan dengan zona waktu **WIB (Asia/Jakarta)**.
- **Export Data & Laporan:** Fitur pembuatan laporan yang dapat diunduh dalam format **PDF** (`barryvdh/laravel-dompdf`) dan **Excel** (`maatwebsite/excel` & `spatie/simple-excel`).
- **UI/UX Modern:** Tampilan antarmuka yang responsif, bersih, dan interaktif yang dibangun menggunakan **Tailwind CSS**, **Alpine.js**, dan Laravel Blade.

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8.2+, Laravel 12.0
- **Frontend:** Laravel Blade, Tailwind CSS, Alpine.js, Vite
- **Database:** MySQL
- **Package/Library Tambahan:**
  - `laravel/breeze` (Autentikasi & Login UI)
  - `spatie/laravel-permission` (Manajemen Role & Permission)
  - `barryvdh/laravel-dompdf` (Cetak Laporan PDF)
  - `maatwebsite/excel` & `spatie/simple-excel` (Export/Import Excel)

---

## 🚀 Cara Instalasi

Untuk menjalankan aplikasi ini secara lokal di komputer kamu, ikuti langkah-langkah berikut:

### Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & npm
- Database MySQL (bisa pakai XAMPP, Laragon, dll)

### Langkah-langkah

1. **Clone repository ini**
   ```bash
   git clone https://github.com/Minnn3/sistem-informasi-admin-kos.git
   cd project-kos
   ```

2. **Install dependency PHP**
   ```bash
   composer install
   ```

3. **Install package NPM (Frontend)**
   ```bash
   npm install
   ```

4. **Pengaturan Environment**
   Salin file konfigurasi environment dan sesuaikan pengaturan database kamu:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` lalu ubah konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_kamu (create database terlebih dahulu)
   DB_USERNAME=root
   DB_PASSWORD=
   
   APP_TIMEZONE=Asia/Jakarta
   ```

5. **Generate Application Key Laravel**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeder**
   Jalankan perintah ini untuk membuat tabel-tabel di database sekaligus memasukkan data awal (seperti Role dan akun Admin):
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Asset Frontend**
   ```bash
   npm run build
   ```

8. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   *Aplikasi sekarang bisa diakses melalui browser di alamat `http://localhost:8000`.*

---

## 🔐 Akun Login Default

Jika kamu sudah menjalankan perintah seeder pada langkah instalasi di atas, kamu bisa login dengan akun admin bawaan:

- **Email:** `admin@admin.com`
- **Password:** `password123` *(atau sesuai dengan password di file `RoleSeeder.php`)*

*(Catatan: Jangan lupa ubah password akun ini jika akan di-online-kan / masuk fase production).*

---

## 📜 Lisensi

Proyek ini adalah perangkat lunak sumber terbuka (open-source) yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT). Siapa pun boleh memakai dan memodifikasi.

---

> Dibuat dengan ❤️ menggunakan [Laravel](https://laravel.com) dan [Google Antigravity]
 
> AMN
