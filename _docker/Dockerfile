FROM php:8.3-apache

# set default apache working directory
WORKDIR /var/www

# update package index and install necessary dependencies
RUN apt-get update && apt-get install -y \
    rpm \
    libzip-dev \
    unzip \
    libicu-dev

# install PHP (main) extensions
RUN docker-php-ext-install zip pdo_mysql mysqli pdo intl

# install OPCache
RUN docker-php-ext-install opcache

# install APCu
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# enable apache rewrite module
RUN a2enmod rewrite
