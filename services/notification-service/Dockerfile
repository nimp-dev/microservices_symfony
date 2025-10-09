
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app
COPY . .

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm", "-F", "-R"]

# Install PHP deps
RUN composer install --no-interaction --prefer-dist --no-scripts || true