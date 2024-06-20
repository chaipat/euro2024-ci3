FROM alpine:edge

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"

# Add application
WORKDIR /var/www/project

RUN apk add --no-cache --update -X http://dl-cdn.alpinelinux.org/alpine/edge/testing \
    curl \
    supervisor \
    nginx \
    php7 \
    php7-fpm \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-iconv \
    php7-redis \
    php7-openssl \
    php7-phar \
    php7-session \
    php7-sockets \
    php7-pcntl \
    php7-simplexml \
    php7-posix \
    php7-zlib \
    php7-zip \
    php7-exif \
    php7-fileinfo \
    php7-tokenizer \
    php7-xml \
    php7-xmlwriter \
    php7-xmlreader \
    php7-opcache \
    && rm -rf /var/cache/apk/*

RUN ln -s /usr/bin/php7 /usr/bin/php

COPY .docker/php/opcache.ini /etc/php7/conf.d/custom_opcache.ini
COPY .docker/php/custom.ini /etc/php7/conf.d/custom.ini

# Setup Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;

# Setup
COPY .docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
