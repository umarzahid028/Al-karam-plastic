# 1. PHP base image
FROM php:8.2-cli

# 2. System dependencies + MySQL extension
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip

# 3. Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /app

# 5. Copy project files
COPY . .

# 6. Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# 7. Fix permissions
RUN chmod -R 777 storage bootstrap/cache

# 8. Expose app port
EXPOSE 8000

# 9. Run Laravel server
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan serve --host 0.0.0.0 --port $PORT
