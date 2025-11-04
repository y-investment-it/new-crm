# CRM Y

A Laravel 10 + Vue 3 CRM starter tailored for shared hosting deployments (Hostinger) with committed Vite build assets.

## Requirements

- PHP 8.2+
- Composer 2
- Node.js 18+
- MySQL 8

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

Default admin credentials: `admin@example.com / password`

## Running locally

```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000/admin/dashboard` after logging in.

## Deployment (Hostinger shared hosting)

1. Build assets locally (`npm run build`) so that `public/build` is committed.
2. Upload repository to hosting; set document root to `public/`.
3. Update `.env` with production database credentials and `APP_URL`.
4. Run optimizers on the server:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

5. Ensure `storage` and `bootstrap/cache` are writable.

## Features

- Role-based admin area under `/admin`
- Leads management with bulk re-assignment modal (Vue component)
- Inventory (products & collections) seed data
- Basic analytics dashboard and reports
- Arabic/English localization with RTL support

## Testing

```bash
php artisan test
```

*(tests require full Laravel dependencies installed)*
