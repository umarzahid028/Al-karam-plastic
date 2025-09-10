# 1. PHP base image
FROM php:8.2-cli

# 2. System dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# 3. Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /app

# 5. Copy project files
COPY . .

# 6. Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# 7. Expose app port
EXPOSE 8000

# 8. Run Laravel server (runtime artisan clears)
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan serve --host 0.0.0.0 --port $PORT
