FROM php:8.3-fpm-alpine

RUN apk update && apk upgrade --no-cache && \
    apk add --no-cache \
        git curl \
        libpng-dev freetype-dev jpeg-dev \
        libzip-dev zip unzip \
        supervisor nginx \
        nodejs npm yarn \
        php83-pcntl

RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ && \
    docker-php-ext-install pdo_mysql bcmath gd zip

RUN curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
COPY public/build /var/www/html/public/build
COPY .env.example .env

RUN mkdir -p storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwX storage bootstrap/cache

RUN chmod -R ug+rwX storage bootstrap/cache

USER www-data
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
USER root

RUN chmod +x /usr/local/bin/run-migrations.sh /usr/local/bin/entrypoint.sh

COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .docker/run-migrations.sh /usr/local/bin/run-migrations.sh
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh


ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
