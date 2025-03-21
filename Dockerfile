# Perbaikan Dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libsqlite3-dev sqlite3 libzip-dev unzip \
    git curl nano supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pcntl gd intl pdo_mysql pdo_sqlite sockets zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Swoole
RUN pecl install swoole && docker-php-ext-enable swoole sockets pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm chokidar-cli

# Set working directory
WORKDIR /var/www/html

# Copy seluruh file Laravel
COPY . .

# Install dependencies dan generate APP_KEY
RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    chmod -R 777 storage bootstrap/cache

CMD sleep 10 && exec php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000 --watch
