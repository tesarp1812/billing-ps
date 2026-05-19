# PS Backend API

Backend Laravel untuk aplikasi billing/POS PlayStation. Project ini disiapkan sebagai REST API standalone yang bisa dipakai frontend Vue dari repository/domain berbeda.

## Tech Stack

- PHP 8.2+
- Laravel 11
- Laravel Sanctum personal access token
- PostgreSQL atau MySQL

## Setup Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Default seed user:

| Email | Password |
| --- | --- |
| admin@test.com | password |
| cashier@test.com | password |

API berjalan di:

```text
http://localhost:8000/api
```

## Environment Penting

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.example.com
FRONTEND_URL=https://app.example.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ps_backend
DB_USERNAME=postgres
DB_PASSWORD=secret

CORS_ALLOWED_ORIGINS=https://app.example.com
CORS_ALLOWED_METHODS=GET,POST,PUT,PATCH,DELETE,OPTIONS
CORS_ALLOWED_HEADERS=Accept,Authorization,Content-Type,X-Requested-With
```

Untuk deployment, jangan gunakan `CORS_ALLOWED_ORIGINS=*` jika API memakai authorization header. Isi domain frontend yang valid.

## Format Response API

Success:

```json
{
  "success": true,
  "message": "Success message",
  "data": {}
}
```

Error:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

Semua endpoint `/api/*` mengembalikan JSON, termasuk validation error, unauthenticated, not found, method not allowed, rate limit, dan server error.

## Authentication

Login menggunakan Sanctum bearer token, bukan session Laravel.

```http
POST /api/auth/login
Accept: application/json
Content-Type: application/json
```

```json
{
  "email": "admin@test.com",
  "password": "password",
  "device_name": "vue-frontend"
}
```

Gunakan token dari response:

```http
Authorization: Bearer {access_token}
```

Endpoint auth:

- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`

## Endpoint Utama

- `GET /api/health`
- `GET /api/dashboard/summary`
- `GET /api/stations`
- `POST /api/stations/{station}/start`
- `POST /api/stations/{station}/pause`
- `POST /api/stations/{station}/stop`
- `POST /api/stations/{station}/add-time`
- `GET /api/products`
- `POST /api/checkout`
- `GET /api/transactions`
- `GET /api/reports/daily`
- `GET /api/settings`
- `PUT /api/settings`
- `POST /api/settings/reset`

## Postman

Import dua file ini:

- `ps-backend.postman_collection.json`
- `ps-backend.postman_environment.json`

Pilih environment `PS Backend API - Local`, jalankan request `Auth / Login`, lalu token otomatis disimpan ke variable `token`.

## Production Checklist

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan:

- `APP_DEBUG=false`
- `APP_KEY` sudah dibuat
- `APP_URL` menunjuk domain API
- `CORS_ALLOWED_ORIGINS` hanya berisi domain frontend
- web server mengarah ke folder `public`
- permission `storage` dan `bootstrap/cache` bisa ditulis
- scheduler/queue dikonfigurasi bila nanti ada job async

Frontend Vue lama masih ada di repository ini, tetapi backend API tidak bergantung pada build frontend untuk berjalan. Untuk deployment API standalone, cukup deploy kode Laravel, dependency Composer, database, dan konfigurasi web server.
