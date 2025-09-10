# 1. PHP base image
FROM php:8.2-cli

# 2. System dependencies + MySQL support
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

# 7. Fix storage/cache permissions
RUN chmod -R 777 storage bootstrap/cache

# 8. Expose port
EXPOSE 8000

# 9. CMD: clear caches, migrate DB, serve
CMD bash -c "echo 'Waiting for DB...' && \
    until php -r 'new PDO(\"mysql:host=${MYSQLHOST};port=${MYSQLPORT}\", \"${MYSQLUSER}\", \"${MYSQLPASSWORD}\");' >/dev/null 2>&1; do sleep 2; done && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
