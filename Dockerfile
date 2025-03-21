# Perbaikan Dockerfile
FROM dunglas/frankenphp:php8.3

# Install dependencies
RUN install-php-extensions pcntl gd intl pdo_mysql pdo_sqlite sockets zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm chokidar-cli

# Set working directory
WORKDIR /app

# Copy file composer.json dan composer.lock terlebih dahulu
COPY composer.json composer.lock ./

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copy seluruh file Laravel
COPY . .

# Install dependencies dan generate APP_KEY
RUN composer install
RUN chmod -R 777 storage bootstrap/cache
CMD ./vendor/bin/sail up
CMD ./vendor/bin/sail artisan octane:install --server=frankenphp

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Generate APP_KEY
RUN php artisan key:generate

# Expose port 8000 untuk FrankenPHP
EXPOSE 8000

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
