FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git libzip-dev unzip libicu-dev libonig-dev libxml2-dev supervisor \
    && docker-php-ext-install pdo pdo_mysql intl zip

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/log/supervisor /etc/supervisor/conf.d

WORKDIR /var/www/html
