FROM php:7.2.19-cli
MAINTAINER PH2M <contact@ph2m.com>

COPY php.ini /usr/local/etc/php/conf.d/zz-conf.ini

RUN build_packages="libpng-dev libfreetype6-dev libjpeg62-turbo-dev libxml2-dev libxslt1-dev libmemcached-dev libicu-dev" \
    && apt-get update && apt-get install -y $build_packages \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


ENV BIN_DIR "/usr/local/bin"
RUN apt-get update \
    && apt-get install -y cron git mysql-client sudo \
    && curl --retry 10 --retry-delay 3 https://getcomposer.org/installer | php -- --install-dir=$BIN_DIR --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

CMD ["php", "-a"]