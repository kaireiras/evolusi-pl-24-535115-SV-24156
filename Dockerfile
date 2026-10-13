# ==========================================
# STAGE 1: BUILDER (Tahap Membangun)
# ==========================================
FROM docker.io/library/composer:2.8 AS builder

WORKDIR /app

# Salin manifes dependensi terlebih dahulu
COPY composer.json composer.lock ./

# Pasang dependensi produksi (tanpa dev-dependencies)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Salin seluruh kode aplikasi (folder frontend terabaikan oleh .dockerignore)
COPY . .

# Buat classmap autoload produksi yang optimal
RUN composer dump-autoload --optimize --no-dev


# ==========================================
# STAGE 2: PRODUCTION RUNTIME (Tahap Berjalan)
# ==========================================
FROM docker.io/library/php:8.4-cli-alpine

# Pasang runtime packages & ekstensi PHP yang dibutuhkan Laravel
RUN apk add --no-cache \
    curl \
    libpng \
    libxml2 \
    oniguruma \
    && apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring pcntl bcmath \
    && apk del .build-deps

WORKDIR /app

# Salin hasil kompilasi dan dependensi dari Stage Builder
COPY --from=builder /app /app

# Buat direktori storage/cache yang diperlukan Laravel, buat user non-root, dan set hak akses
RUN mkdir -p /app/storage/framework/cache/data \
             /app/storage/framework/sessions \
             /app/storage/framework/views \
             /app/storage/logs \
             /app/bootstrap/cache \
    && addgroup -S appgroup \
    && adduser -S appuser -G appgroup \
    && chown -R appuser:appgroup /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Konfigurasi HEALTHCHECK diarahkan ke /up bawaan Laravel
HEALTHCHECK --interval=10s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://127.0.0.1:8000/up || exit 1

# Pindah ke user non-root
USER appuser

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]