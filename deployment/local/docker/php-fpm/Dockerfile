FROM php:8.1-fpm

RUN apt-get update && apt-get -y install cron

RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libxml2-dev \
    libicu-dev \
    libbz2-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

RUN docker-php-ext-install \
    gd \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    simplexml \
    zip

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

ARG uid
ARG gid

RUN groupadd -g $gid appgroup
RUN useradd -G www-data,root -u $uid -g $gid -d /home/appuser appuser
RUN mkdir -p /home/appuser/.composer && \
    chown -R appuser:appgroup /home/appuser && \
    su appuser

RUN chown -R appuser:appgroup /home/appuser
WORKDIR /var/www/app