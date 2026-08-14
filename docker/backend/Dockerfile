FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql opcache \
    && rm -rf /var/lib/apt/lists/*


FROM base AS dev

RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


FROM dev AS vendor

WORKDIR /app
COPY backend/ .
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist


FROM base AS app

WORKDIR /var/www/html
COPY --from=vendor /app .

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && chown -R www-data:www-data storage bootstrap/cache

CMD ["sh", "-c", "php artisan migrate --force --seed && exec php-fpm"]
