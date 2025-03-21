<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirement php dan node
- PHP v 8.1 ke atas
- Node v 20 ke atas

## Install Composer & NPM
```
composer install
npm install
```

## Running Laravel
```
php artisan serve
```

## Running Node
 ```
 npm run dev
 ```

## Run Migration & Seeder
```
php artisan migrate --seed
```

## Create Model & Migration laravel
```
php artisan make:model NameModel -mc
```

## Install Mysql on Docker
```
docker run -d \
  --name mysql-container \
  -e MYSQL_ROOT_PASSWORD=YourPasswordKey \
  -e MYSQL_DATABASE=YourDatabase \
  -p 3306:3306 \
  -v mysql_data:/var/lib/mysql \
  mysql:8
```

## Install Composer on Docker
```
docker build -t myapp-composer -f Dockerfile.composer .
docker run --rm -v $(pwd):/app myapp-composer
```

## Install Node on Docker
```
docker build -t myapp-node -f Dockerfile.node .
docker run --rm -v $(pwd):/app myapp-node
```

