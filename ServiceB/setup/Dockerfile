FROM php:8.1-apache as base

RUN apt-get update && apt-get install -y libssl-dev curl
RUN pecl install mongodb && docker-php-ext-enable mongodb && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /app/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir /app

COPY ../ /app

WORKDIR /app

# - - - - - - - - - - - - 
# commented out because composer was taking ages to install. Will investigate why later.

# FROM composer:2.4 as install

# ENV COMPOSER_ALLOW_SUPERUSER=1

# COPY ./composer.*/ /app

# RUN composer install

# - - - - - - - - - - - - 

# FROM base

# COPY --from=install vendor/ vendor/