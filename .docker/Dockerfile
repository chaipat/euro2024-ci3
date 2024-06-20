ARG BASE_IMAGE=074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:runtime-v1.0

FROM ${BASE_IMAGE}

ARG APP_ENV=production

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

WORKDIR /var/www/project/public

# Change env variable
RUN awk "{gsub(/_ENV development;/,\"_ENV ${APP_ENV};\")}1" /etc/nginx/nginx.conf > nginx.conf \
    && mv nginx.conf /etc/nginx/nginx.conf

COPY . /var/www/project/public

RUN mkdir -p uploads \
    && chmod -R 777 uploads \
    && mkdir -p data \
    && chmod -R 777 data


# RUN composer install --no-dev && composer clear-cache

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
