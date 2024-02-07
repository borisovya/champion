FROM php:8.1-fpm-alpine

MAINTAINER https://champion.partners/

WORKDIR /var/www/artec

USER root
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk update && apk add \
    build-base \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    oniguruma-dev \
    nano \
    curl

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

USER www
COPY --chown=www:www .. /var/www

EXPOSE 9000
CMD ["php-fpm"]