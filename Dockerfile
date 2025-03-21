# Perbaikan Dockerfile
FROM dunglas/frankenphp:php8.3

# Install dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libsqlite3-dev sqlite3 libzip-dev unzip \
    git curl nano supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pcntl gd intl pdo_mysql pdo_sqlite sockets zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy seluruh file Laravel
COPY . .

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Copy script untuk menunggu database
COPY wait-for-db.sh /usr/local/bin/wait-for-db.sh
RUN chmod +x /usr/local/bin/wait-for-db.sh

# Expose port 8000 untuk FrankenPHP
EXPOSE 8000

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
CMD wait-for-db.sh && php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000
