FROM php:apache

ARG UNAME=www-data
ARG UGROUP=www-data
ARG UID=1000
ARG GID=1000
RUN usermod  --uid $UID $UNAME
RUN groupmod --gid $GID $UGROUP

RUN docker-php-ext-install -j$(nproc) mysqli pdo_mysql
RUN a2enmod rewrite

COPY admin/ /var/www/html/