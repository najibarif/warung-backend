# Aplikasi Warung - Backend API

Proyek ini adalah pusat inti logika (*Core API*) untuk ekosistem Aplikasi Warung. Dibangun menggunakan framework **Laravel**, backend ini menyediakan API otentikasi, manajemen produk, kasir/POS, hingga rekam data.

## 🛠 Teknologi Utama
- **Framework**: Laravel 11/12
- **Database**: MongoDB (via driver `mongodb/laravel-mongodb`)
- **Autentikasi**: Laravel Sanctum (Token-based API Auth)

---

## ⚙️ Cara Menjalankan di Lokal (Development)

Untuk tahap *development*, pastikan **MongoDB Community Server** lokal Anda berjalan di *port* default (`27017`).

1. Konfigurasi file `.env` untuk database lokal:
   ```env
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=warung
   ```
2. Jalankan perintah instalasi (jika baru di-*clone*):
   ```bash
   composer install
   ```
3. Suntikkan data awal (Admin, Kategori, Produk Sampel):
   ```bash
   php artisan db:seed
   ```
4. Jalankan *server lokal*:
   ```bash
   php artisan serve
   ```
   *API akan dapat diakses secara lokal di `http://127.0.0.1:8000/api`*

---

## 🚀 Persiapan Deployment (Production)

Saat aplikasi siap dinaikkan ke internet:
1. Pastikan server VPS Anda memiliki instalasi **PHP MongoDB Extension** (`mongodb.so`).
2. Hubungkan domain production Anda (misal: `https://warung-backend.nue.dom.my.id`).
3. Pada file `.env` di server, sesuaikan koneksi `DB_HOST` dan `DB_PASSWORD` jika Anda menggunakan database cloud (seperti MongoDB Atlas) atau ubah sesuai environment VPS Anda.
4. Client/Frontend harus memanggil rute dari `https://warung-backend.nue.dom.my.id/api`.

---

## 💡 Perintah Penting (Cheat Sheet)
- Membersihkan seluruh *cache* (dibutuhkan jika Anda baru mengubah file konfigurasi atau `.env`):
  ```bash
  php artisan optimize:clear
  ```
- Menambah library baru:
  ```bash
  composer require <nama-paket>
  ```
