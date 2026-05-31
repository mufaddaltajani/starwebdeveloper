FROM php:8.3-fpm-alpine

# Install system dependencies required for our stack
# sqlite-dev is needed for our SQLite database
RUN apk update && apk add --no-cache \
    sqlite \
    sqlite-dev \
    linux-headers \
    $PHPIZE_DEPS

# Install required PHP extensions
# pdo_sqlite allows PHP to talk to SQLite safely
RUN docker-php-ext-install pdo pdo_sqlite

# Install and enable Redis extension for caching
RUN pecl install redis && docker-php-ext-enable redis

# Set working directory to match Nginx
WORKDIR /var/www/html