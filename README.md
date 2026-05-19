# PS Backend API

Laravel REST API untuk billing/POS PlayStation. Repository ini sudah dibersihkan menjadi backend API only; frontend Vue dipisahkan ke repository lain.

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Base URL lokal:

```text
http://localhost:8000/api
```

Default seed user:

| Email | Password |
| --- | --- |
| admin@test.com | password |
| cashier@test.com | password |

## API Auth

Login:

```http
POST /api/auth/login
Accept: application/json
Content-Type: application/json
```

```json
{
  "email": "admin@test.com",
  "password": "password",
  "device_name": "postman"
}
```

Gunakan token:

```http
Authorization: Bearer {access_token}
```

## Endpoint

- `GET /api/health`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`
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

Semua response API menggunakan format:

```json
{
  "success": true,
  "message": "Success message",
  "data": {}
}
```

```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

## Environment Production

Jangan commit `.env`. Isi env production di dashboard Render/Koyeb/Docker secret.

Minimal:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://api.example.com
FRONTEND_URL=https://app.example.com

DB_CONNECTION=pgsql
DB_HOST=your-supabase-pooler-host
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxx
DB_PASSWORD=secret
DB_SCHEMA=public
DB_SSLMODE=require

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
CORS_ALLOWED_ORIGINS=https://app.example.com
```

Untuk Supabase pooler, gunakan port `6543` dan `DB_SSLMODE=require`.

## Postman

Import:

- `ps-backend.postman_collection.json`
- `ps-backend.postman_environment.json`

Pilih environment `PS Backend API - Local`, jalankan `02 Auth / Login - Save Token`, lalu endpoint protected otomatis memakai `{{token}}`.

## Docker

Build:

```bash
docker build -t ps-backend-api .
```

Run:

```bash
docker run --rm -p 8000:8000 --env-file .env ps-backend-api
```

Migrasi database di container:

```bash
docker run --rm --env-file .env ps-backend-api php artisan migrate --force
docker run --rm --env-file .env ps-backend-api php artisan db:seed --class=UserSeeder --force
```

## Render

Gunakan `render.yaml` atau buat Web Service Docker manual.

Checklist:

- Set `APP_KEY` di Render secret.
- Set `APP_DEBUG=false`.
- Set semua env database Supabase.
- Set `CORS_ALLOWED_ORIGINS` ke domain frontend.
- Health check: `/api/health`.
- Jalankan migration dengan Render Shell atau job manual:

```bash
php artisan migrate --force
php artisan db:seed --class=UserSeeder --force
```

## Production Commands

Jika deploy tanpa Docker:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Pastikan `storage` dan `bootstrap/cache` writable oleh user runtime.
