FROM composer as backend
WORKDIR /app

COPY composer.json /app/
RUN composer install

FROM php:7.2-apache

#RUN docker-php-ext-install opcache
#COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#RUN apt-get update && apt-get install -y libmemcached11 libmemcachedutil2 libmemcached-dev libz-dev git \
#    && cd /root \
#    && git clone -b php7 https://github.com/php-memcached-dev/php-memcached \
#    && cd php-memcached \
#    && phpize \
#    && ./configure \
#    && make \
#    && make install \
#    && cd .. \
#    && rm -rf  php-memcached \
#    && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
#    && apt-get remove -y build-essential libmemcached-dev libz-dev \
#    && apt-get remove -y libmemcached-dev libz-dev \
#    && apt-get autoremove -y \
#    && rm -rf /var/lib/apt/lists/* \
#    && apt-get clean

COPY public /var/www/html/public/
COPY src /var/www/html/src/
COPY configs /var/www/html/configs/
RUN chmod -R 755 /var/www/html/

COPY --from=backend /app /var/www/html/

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
