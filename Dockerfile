# -------------------------
# Base image
# -------------------------
FROM php:8.2-cli

# -------------------------
# Set working directory
# -------------------------
WORKDIR /var/www/html

# -------------------------
# Install system dependencies
# -------------------------
RUN apt-get update && apt-get install -y \
    zip unzip git sqlite3 libsqlite3-dev libzip-dev libmariadb-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# -------------------------
# Install Composer
# -------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------
# Copy project files
# -------------------------
COPY . .

# -------------------------
# Setup Permissions BEFORE Composer
# -------------------------
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# -------------------------
# Install PHP dependencies
# -------------------------
# We use --no-scripts to prevent Laravel from trying to boot 
# before the environment is fully ready.
RUN composer install --no-dev --no-scripts --optimize-autoloader

# -------------------------
# Expose port
# -------------------------
EXPOSE 10000

# -------------------------
# Start Script
# -------------------------
# We run migrations at runtime, not build time, so we can connect to the DB.
CMD php artisan config:clear && \
    php artisan package:discover --ansi && \
    php artisan migrate --force && \
    php -S 0.0.0.0:10000 -t public