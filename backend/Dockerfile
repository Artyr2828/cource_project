FROM php:8.5-fpm

WORKDIR /var/www

COPY . /var/www/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN apt-get update && apt-get install -y \
    libpq-dev \
    nginx \
    && docker-php-ext-install pdo_pgsql

COPY ./nginx/nginx.conf /etc/nginx/conf.d/default.conf

RUN  mkdir -p /var/www/var \ 
    && chown -R www-data /var/www/var

COPY ./docker/entrypoint.sh /var/www/
RUN chmod +x /var/www/entrypoint.sh
ENTRYPOINT [ "/var/www/entrypoint.sh" ]
CMD ["php-fpm"] 
