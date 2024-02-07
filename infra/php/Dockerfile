FROM php:8.2-fpm

MAINTAINER https://champion.partners

ENV TZ=UTC
ENV DEBIAN_FRONTEND="noninteractive"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && \
    apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        libzip-dev \
        libbz2-dev \
        libcurl4-openssl-dev \
        libicu-dev \
        libldb-dev \
        libldap2-dev \
        libonig-dev \
        libpq-dev \
        libedit-dev \
        libxml2-dev \
        cron \
        zip \
        unzip \
        net-tools \
        libc-client-dev \
        libkrb5-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install bz2 \
    && docker-php-ext-install curl \
    && docker-php-ext-install intl \
    && docker-php-ext-install ldap \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install soap \
    && docker-php-ext-install xml \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

