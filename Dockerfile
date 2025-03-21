# Gunakan base image FrankenPHP dengan PHP 8.3
FROM dunglas/frankenphp:php8.3

# Install dependencies yang diperlukan untuk Laravel
RUN install-php-extensions pcntl gd intl pdo_mysql pdo_sqlite sockets zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy file composer.json dan composer.lock terlebih dahulu
COPY composer.json composer.lock ./

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copy seluruh file Laravel
COPY . .

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Generate APP_KEY
RUN php artisan key:generate

# Clear cache
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan cache:clear

# Expose port 8000 untuk FrankenPHP
EXPOSE 8000

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
