# Start from a PHP image with Apache
FROM php:8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install latest composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# Set working directory
RUN chwown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000
