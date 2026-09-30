# Stage 1: Ambil Composer dari Docker Hub
FROM docker.io/library/composer:latest AS composer_stage

# Stage 2: Main PHP Image
FROM docker.io/library/php:8.4-cli

# Install dependensi sistem dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copy Composer dari Stage 1
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

WORKDIR /app

# [POIN 3] Salin composer.json & composer.lock DULUAN agar layer cache bekerja
COPY composer.json composer.lock ./

# Install dependensi PHP
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Salin seluruh sisa kode aplikasi
COPY . .

# Optimize autoload
RUN composer dump-autoload --optimize

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]