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
# Setup Permissions IMMEDIATELY
# -------------------------
# We create these now so they exist even if the COPY command 
# hasn't brought in the local folders yet.
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# -------------------------
# Install Composer
# -------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------
# Copy project files
# -------------------------
COPY . .

# -------------------------
# Re-assert Permissions
# -------------------------
# This ensures that files copied from your local machine 
# don't overwrite the permissions we set above.
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# -------------------------
# Install PHP dependencies
# -------------------------
# --no-scripts is CRITICAL here to prevent Laravel 
# from booting during the docker build phase.
RUN composer install --no-dev --no-scripts --optimize-autoloader

# -------------------------
# Expose port
# -------------------------
EXPOSE 10000

# -------------------------
# Start Script
# -------------------------
# We run these at runtime. The "mkdir" at the start is a 
# safety net for Render's ephemeral filesystem.
# Final startup command
CMD mkdir -p /tmp/views && \
    php artisan config:clear && \
    php artisan package:discover --ansi && \
    php artisan migrate --seed --force && \
    php -S 0.0.0.0:10000 -t public