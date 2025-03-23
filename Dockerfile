# Gunakan base image FrankenPHP dengan PHP 8.3
FROM dunglas/frankenphp:php8.3

# Install dependencies sistem yang diperlukan
RUN apt-get update && apt-get install -y \
    zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libsqlite3-dev sqlite3 libzip-dev unzip \
    libonig-dev libxml2-dev libssl-dev libcurl4-openssl-dev \
    git curl nano supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP secara manual
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install sockets
RUN docker-php-ext-install zip
RUN docker-php-ext-install dom

# Set working directory
WORKDIR /app

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
