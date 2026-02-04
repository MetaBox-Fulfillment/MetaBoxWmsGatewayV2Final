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
# Egy könnyű, Nginx-szel előre konfigurált PHP image-et használunk
FROM serversideup/php:8.2-fpm-nginx
WORKDIR /var/www/html

# Szükséges PHP kiterjesztések telepítése (ha a Laravelnek kell extra)
# Az alpine alapú image-eknél ez villámgyors
USER root
RUN apt-get update && apt-get install -y libsqlite3-dev && docker-php-ext-install pdo_mysql

# Másoljuk át a vendor-t az első szakaszból
COPY --from=vendor /app/vendor ./vendor

# Másoljuk át a kódunkat
COPY . .

# Laravel jogosultságok beállítása
RUN chown -R www-data:www-data storage bootstrap/cache

# Alapértelmezett környezeti változók (a .env felülírja ezeket)
ENV PHP_OPCACHE_ENABLE=1