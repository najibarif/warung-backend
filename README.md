# Toko Nabil API

Backend API untuk aplikasi Toko Nabil (warung/toko sembako).

## Tech Stack

- Laravel 12
- Sanctum (token auth)
- MySQL / TiDB
- Vercel (deployment)

## Setup Lokal

```bash
cp .env.example .env
# edit .env sesuai database kamu
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## API Endpoints

Lihat [routes/api.php](routes/api.php) untuk daftar lengkap endpoint.

## Deploy ke Vercel

Pastikan environment variables berikut sudah diset di Vercel Dashboard:
- `APP_KEY`
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `CORS_ALLOWED_ORIGINS`
