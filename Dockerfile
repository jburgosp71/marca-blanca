FROM composer as backend
WORKDIR /app

COPY composer.json /app/
RUN composer install

FROM php:7.2-apache

COPY src/*.php /var/www/html/
COPY configs /var/www/configs/
COPY src/css /var/www/html/css/
COPY src/templates /var/www/html/templates/
RUN chmod -R 755 /var/www/html/
RUN chmod -R 755 /var/www/configs/

COPY --from=backend /app /var/www/html/