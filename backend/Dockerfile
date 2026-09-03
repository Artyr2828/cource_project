FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

ENV APP_ENV=prod
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data var

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]