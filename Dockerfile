FROM composer as backend
WORKDIR /app

COPY composer.json /app/
RUN composer install

FROM php:7.2-apache

COPY src/index.php /var/www/html/
COPY src/css /var/www/html/css/
COPY src/templates /var/www/html/templates/
RUN chmod -R 755 /var/www/html/

COPY --from=backend /app /var/www/html/