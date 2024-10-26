FROM php:8.3-apache
COPY --from=composer /usr/bin/composer /usr/bin/composer
# RUN docker-php-ext-install sockets
RUN apt-get update
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install pdo_mysql zip

COPY . /var/www/html/

RUN composer update --ignore-platform-req=ext-sockets