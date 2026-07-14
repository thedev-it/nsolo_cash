#!/usr/bin/env bash
set -e

echo "Clearing old caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching config for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link || true

echo "Running migrations..."
php artisan migrate --force

echo "Handing off to the base image's server startup script..."
exec /start.sh