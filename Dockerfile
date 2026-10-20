FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libicu-dev libonig-dev libzip-dev libpng-dev libxml2-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

COPY . .

RUN rm -rf bootstrap/cache/* \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}"]
