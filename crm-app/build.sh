#!/usr/bin/env bash
# Render.com build script for Laravel

echo "🔧 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔑 Generating application key..."
php artisan key:generate --force

echo "📦 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed!"
