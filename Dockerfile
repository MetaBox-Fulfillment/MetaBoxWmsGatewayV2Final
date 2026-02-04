# 1. SZAKASZ: Composer függőségek letöltése
FROM composer:latest AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# 2. SZAKASZ: A végleges futtató környezet
FROM serversideup/php:8.2-fpm-nginx
WORKDIR /var/www/html

# Szükséges PHP kiterjesztések telepítése Postgres-hez
USER root
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Másoljuk át a vendor-t
COPY --from=vendor /app/vendor ./vendor

# Másoljuk át a kódunkat
COPY . .

# Migrációs script létrehozása, ami indításkor lefut
RUN echo "#!/bin/sh\n\
echo 'Running migrations...'\n\
php artisan migrate --force\n\
" > /etc/entrypoint.d/99-migrate.sh && chmod +x /etc/entrypoint.d/99-migrate.sh

# Laravel jogosultságok beállítása
RUN chown -R www-data:www-data storage bootstrap/cache

# Alapértelmezett környezeti változók
ENV PHP_OPCACHE_ENABLE=1
