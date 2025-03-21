# Perbaikan Dockerfile
FROM dunglas/frankenphp:php8.3

# Install dependencies
RUN install-php-extensions pcntl gd intl pdo_mysql pdo_sqlite sockets zip

# Copy all file Laravel
COPY . /app

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
