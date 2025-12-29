#!/bin/sh
set -e

# 1️⃣ SAFETY NET — must be first
mkdir -p storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 2️⃣ Optional wait (DB, etc)
sleep 10s

# 3️⃣ Laravel setup
php artisan migrate --force
php artisan storage:link || true

# 4️⃣ Start PHP (IMPORTANT)
exec php-fpm
