FROM php:8.2-cli

# Install system deps + PHP extensions needed for Laravel + MySQL
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
  && docker-php-ext-install pdo pdo_mysql zip \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy app code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel needs these writable at runtime
RUN mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Serve the app
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
