#!/usr/bin/env bash
# Render.com start script for Laravel

echo "🚀 Starting Laravel application..."

# Run migrations (optional, bisa dihapus kalau mau manual)
php artisan migrate --force

# Start PHP built-in server
php artisan serve --host=0.0.0.0 --port=$PORT
