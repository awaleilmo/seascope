#!/bin/sh

# Tunggu database siap sebelum migrate
sleep 10

# Jalankan migration dan seeding
php artisan migrate --force

# Jalankan Laravel Octane dengan FrankenPHP
exec php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000 --watch
