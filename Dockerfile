# Perbaikan Dockerfile
FROM dunglas/frankenphp:php8.3

# Install dependencies
#RUN apt-get update && apt-get install -y \
#    zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
#    libicu-dev libsqlite3-dev sqlite3 libzip-dev unzip \
#    git curl nano supervisor \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install pcntl gd intl pdo_mysql pdo_sqlite sockets zip \
#    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pcntl gd intl pdo_mysql pdo_sqlite sockets zip


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm chokidar-cli

# Set working directory
#WORKDIR /var/www/html

# Copy seluruh file Laravel
COPY . /app

# Install dependencies dan generate APP_KEY
RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    chmod -R 777 storage bootstrap/cache

# Copy script untuk menunggu database
#COPY wait-for-db.sh /usr/local/bin/wait-for-db.sh
#RUN chmod +x /usr/local/bin/wait-for-db.sh

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

# Expose port 8000 untuk FrankenPHP
#EXPOSE 8000

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
#CMD wait-for-db.sh && php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000
