# dev stage
FROM php:8.3-fpm AS dev

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/



RUN apt-get update && apt-get install -y \
    git unzip libpq-dev bash vim \
    && rm -rf /var/lib/apt/lists/*

#RUN docker-php-ext-install pdo pdo_pgsql
RUN install-php-extensions pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer


COPY php.ini /usr/local/etc/php/
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/

# копирование кода необязательно, монтируем volume
WORKDIR /var/www/app

# prod stage
FROM php:8.3-fpm AS prod

RUN apt-get update && apt-get install -y libpq5 \
    && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/app

COPY --from=dev /var/www/app /var/www/app