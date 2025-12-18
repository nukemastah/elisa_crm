#!/usr/bin/env bash
set -e

# Ensure composer deps
if [ ! -d "vendor" ]; then
  echo "Installing Composer dependencies..."
  composer install --no-dev --optimize-autoloader
fi

# Ensure app key
if ! grep -q "^APP_KEY=base64:" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force || true
fi

# Cache config/views/routes for performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations if DB available (optional)
# php artisan migrate --force || true

# Start Laravel on Replit port
php artisan serve --host=0.0.0.0 --port=${PORT:-3000}
