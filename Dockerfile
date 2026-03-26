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
    zip unzip git sqlite3 libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip \
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
# Create storage & cache directories and set permissions
# -------------------------
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database

# -------------------------
# Install PHP dependencies
# -------------------------
RUN composer install --no-dev --optimize-autoloader

# -------------------------
# Clear & cache config to avoid build errors
# -------------------------
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan config:cache

# -------------------------
# Run migrations & seed database
# -------------------------
RUN php artisan migrate --force \
    && php artisan db:seed --force

# -------------------------
# Expose port
# -------------------------
EXPOSE 10000

# -------------------------
# Start PHP built-in server
# -------------------------
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]