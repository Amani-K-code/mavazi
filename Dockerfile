# Use official PHP image with required extensions
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite DB file
RUN touch database/database.sqlite

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Run migrations and seed
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Expose port
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]