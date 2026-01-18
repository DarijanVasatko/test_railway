#!/usr/bin/env bash
set -e

cd laravel

# IMPORTANT: if public/storage exists as a folder, delete it first
rm -rf public/storage

php artisan storage:link

# permissions (safe)
chmod -R 775 storage bootstrap/cache || true

php artisan config:clear || true
php artisan cache:clear || true

php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
