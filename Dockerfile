FROM php:8.2-cli

RUN pecl install pcov && docker-php-ext-enable pcov
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
