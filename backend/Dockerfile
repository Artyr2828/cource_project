
FROM php:8.4-fpm-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
    libzip-dev \
    icu-dev

RUN docker-php-ext-install pdo pdo_pgsql zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

ENV APP_ENV=prod
RUN composer install --no-dev --no-scripts --prefer-dist --no-autoloader

COPY . .
RUN composer dump-autoload --no-dev --optimize

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-libs \
    postgresql-dev \
    libzip \
    libzip-dev \
    icu-libs \
    icu-dev \
    && docker-php-ext-install pdo pdo_pgsql zip opcache intl \
    && apk del postgresql-dev libzip-dev icu-dev

WORKDIR /var/www/html


COPY --from=builder /app /var/www/html

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]