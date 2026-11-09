#!/usr/bin/env bash
# Hentikan proses jika terjadi error
set -o errexit

echo "--- 1. Installing Dependencies ---"
composer install --no-dev --optimize-autoloader

echo "--- 2. Caching Configuration ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- 3. Running Database Migrations & Seeders ---"
# Menyiapkan database SQLite bawaan agar aplikasi langsung siap pakai secara gratis
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force