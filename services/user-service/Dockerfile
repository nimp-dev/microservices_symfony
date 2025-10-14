FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install RabbitMQ dependencies and AMQP extension
RUN apt-get update && apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app
COPY . .

# Install PHP deps
RUN composer install --no-interaction --prefer-dist --no-scripts || true

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm", "-F", "-R"]
