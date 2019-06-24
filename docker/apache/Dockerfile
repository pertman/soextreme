FROM php:7.2.19-apache
MAINTAINER PH2M <contact@ph2m.com>

COPY php.ini /usr/local/etc/php/conf.d/zz-conf.ini

RUN build_packages="libpng-dev libfreetype6-dev libjpeg62-turbo-dev libxml2-dev libxslt1-dev libmemcached-dev libicu-dev" \
    && apt-get update && apt-get install -y $build_packages \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite headers

CMD ["apache2-foreground"]