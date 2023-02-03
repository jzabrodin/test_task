FROM php:8.1-fpm

RUN apt-get update && apt-get install -y zlib1g-dev libcurl4-openssl-dev g++ git libicu-dev zip curl libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql iconv curl\
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install pcov && \
    docker-php-ext-enable pcov

WORKDIR /var/www/project

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer