# -------------------------
# Base image
# -------------------------
FROM php:8.2-cli

# -------------------------
# Set working directory
# -------------------------
WORKDIR /var/www/html

# -------------------------
# Install system dependencies + PDF/Image Libraries
# -------------------------
RUN apt-get update && apt-get install -y \
    zip unzip git sqlite3 libsqlite3-dev libzip-dev libmariadb-dev \
    libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# -------------------------
# Install Composer Binary
# -------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------
# Copy project files
# -------------------------
COPY . .

# -------------------------
# Setup Permissions
# -------------------------
# We create fonts folder specifically for DomPDF
RUN mkdir -p storage/framework/{sessions,views,cache} storage/fonts bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# -------------------------
# Install PHP dependencies
# -------------------------
# This creates the 'vendor' folder that was missing
RUN composer install --no-dev --no-scripts --optimize-autoloader

# -------------------------
# Expose port
# -------------------------
EXPOSE 10000

# -------------------------
# Start Script
# -------------------------
CMD mkdir -p /tmp/views /tmp/fonts && \
    php artisan config:clear && \
    php artisan package:discover --ansi && \
    php artisan migrate --force && \
    php -S 0.0.0.0:10000 -t public