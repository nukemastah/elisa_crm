# crm-app (Laravel 11 skeleton)

Minimal Laravel 11 application skeleton for PT.Smart CRM. This folder contains migrations (converted from `database/schema.sql`), models, controllers, simple session-based auth, and blade views to exercise the required flows.

Quick start (after cloning/pushing repository):

1. From repo root, start PostgreSQL:

```bash
docker compose up -d
```

2. Install dependencies inside `crm-app`:

```bash
cd crm-app
composer install
cp .env.example .env
php artisan key:generate
```

3. Run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

4. Serve the app:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Login credentials created by seeder:
- admin@example.com / password
- manager@example.com / password
- sales@example.com / password

Notes:
- This is a minimal scaffold to meet the required pages and flows. For production use, replace the simple auth with Laravel's official authentication (Breeze/Jetstream), add validation, policies, and tests.
