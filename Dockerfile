# Gunakan base image PHP dengan FPM dan Swoole
FROM php:8.2-fpm

# Update package manager dan install semua dependensi yang dibutuhkan
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libsqlite3-dev \
    sqlite3 \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
       pcntl \
       gd \
       intl \
       pdo_mysql \
       pdo_sqlite \
       sockets \
       zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Swoole
RUN pecl install swoole  \
    && docker-php-ext-enable swoole sockets pcntl

# Install Composer secara manual
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js dan npm (versi terbaru dari NodeSource)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm chokidar-cli

# Set working directory di dalam container
WORKDIR /var/www/html

# Copy semua file Laravel ke dalam container
COPY . .

# Mengatasi masalah "Git detected dubious ownership"
RUN git config --global --add safe.directory /var/www/html

# Install dependencies Composer (PHP 8.3 sudah kompatibel)
RUN composer install --no-dev --optimize-autoloader

# Install dependencies Node.js dan build frontend
RUN npm install && npm run build

# Beri izin akses pada storage dan bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Tunggu database siap, jalankan migration dan Laravel Octane langsung dalam Dockerfile
CMD sleep 10 && php artisan migrate --force && exec php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000 --watch
