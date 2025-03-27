# Gunakan base image FrankenPHP dengan PHP 8.3
FROM dunglas/frankenphp:php8.3

# Install dependencies sistem yang diperlukan
RUN apt-get update
RUN apt-get install -y librdkafka-dev
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y libjpeg-dev
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y libicu-dev
RUN apt-get install -y libsqlite3-dev
RUN apt-get install -y sqlite3
RUN apt-get install -y libzip-dev
RUN apt-get install -y unzip
RUN apt-get install -y libonig-dev
RUN apt-get install -y libxml2-dev
RUN apt-get install -y libssl-dev
RUN apt-get install -y libcurl4-openssl-dev
RUN apt-get install -y git
RUN apt-get install -y curl
RUN apt-get install -y nano
RUN apt-get install -y supervisor
RUN apt-get install -y libpq-dev
RUN pecl install redis
RUN docker-php-ext-enable redis
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

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
RUN docker-php-ext-install pdo_pgsql

# Set working directory
WORKDIR /app

# Jalankan FrankenPHP dengan konfigurasi Laravel Octane
ENTRYPOINT ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
