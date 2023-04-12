FROM php:8.1-apache as base

RUN pecl install mongodb && a2enmod rewrite
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

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