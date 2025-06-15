FROM php:8.3-fpm-alpine

# Update package index and upgrade installed packages to reduce vulnerabilities
RUN apk update && apk upgrade --no-cache && \
	apk add --no-cache --virtual .build-deps curl && \
	rm -rf /var/cache/apk/*

# Install dependencies
RUN apk add --no-cache \
    git \
    curl \ 
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    supervisor \
    nginx \
    nodejs \
    npm \ 
    yarn 

# Install PHP extensions
RUN  docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    gd \
    zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

 # Copy application files
COPY . .

# Set permissions for storage and bootstrap/cache directories
RUN chown -R 82:82 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


# Install composer dependencies
USER www-data
RUN composer install --no-dev --no-interaction --prefer-dist
USER root

# Copy Nginx and Supervisor configuration 
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#Set migrations
COPY .docker/run-migrations.sh /usr/local/bin/run-migrations.sh
RUN chmod +x /usr/local/bin/run-migrations.sh
#Set entrypoints
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]


# Expose port
EXPOSE 80

# Start Supervisor 
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
