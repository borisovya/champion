FROM champion_backend_base

MAINTAINER https://champion.partners

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN chown -R www-data:www-data /var/www && \
    mkdir -p /run/php && \
    chown -R www-data:www-data /run/php

WORKDIR /var/www/champion

COPY . .

RUN composer install --prefer-dist --no-interaction

