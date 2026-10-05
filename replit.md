# Preventia Field Intelligence

Laravel administrator dashboard for GPS-based pharmaceutical field activity monitoring, powered by Firebase Realtime Database.

## Run & Operate

- `cd preventia-laravel && php artisan serve --host=0.0.0.0 --port=$PORT` — run the Laravel app
- `cd preventia-laravel && php artisan route:list` — inspect application routes
- `cd preventia-laravel && php artisan view:cache` — validate and cache Blade views
- Required env: `FIREBASE_DATABASE_URL` and optional `FIREBASE_DATABASE_AUTH_TOKEN`

## Stack

- Laravel 11, PHP 8.2, Blade templates
- Firebase Realtime Database REST API
- Plain JavaScript and CSS in `preventia-laravel/public`

## Where things live

- `preventia-laravel/routes/web.php` — public and administrator-only routes
- `preventia-laravel/app/Http/Controllers` — auth and dashboard controllers
- `preventia-laravel/app/Services/FirebaseDatabase.php` — Firebase REST adapter and preview dataset
- `preventia-laravel/resources/views/dashboard/sections` — separate dashboard page sections
- `preventia-laravel/public/css/admin.css` — dashboard and login styles
- `preventia-laravel/public/js/admin.js` — vanilla JS interactions

## Architecture decisions

- The dashboard uses Firebase REST calls directly through Laravel's HTTP client; no Node build step is needed.
- If Firebase auth is not configured or the protected database rejects access, the UI explicitly labels demo mode instead of silently hiding the connection state.
- Administrator sessions use Laravel's server session with a configured username/password; the default development account is `nica` / `preventia-admin`.

## Product

- Administrator login for `nica`
- Overview of visits, attendance, representatives, facilities, alerts, and territory coverage
- Visit history search, status filtering, and record detail modal
- Representative directory, facility cards, predictive analytics, alerts queue, and Firebase setup screen

## User preferences

- Keep the frontend in Blade, HTML, CSS, and plain JavaScript; do not introduce Node or TypeScript for application functionality.

## Gotchas

- Live data requires a Firebase Realtime Database auth token when the database rules are not public.