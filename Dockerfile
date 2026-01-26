FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
  icu-dev \
  linux-headers \
  zip \
  libzip-dev \
  && docker-php-ext-install \
  intl \
  opcache \
  zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
