# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources/ resources/
RUN npm run build

# Stage 2: Install PHP dependencies
FROM composer:latest AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Stage 3: Final image
FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libonig-dev libxml2-dev libsqlite3-dev libicu-dev libzip-dev \
    libfreetype6-dev libjpeg-dev pkg-config \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_sqlite mbstring exif pcntl bcmath gd intl zip \
    && apt-get purge -y libpng-dev libonig-dev libxml2-dev libsqlite3-dev libicu-dev libzip-dev \
    libfreetype6-dev libjpeg-dev pkg-config \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY --from=vendor /app/vendor/ vendor/
COPY --from=frontend /app/public/build/ public/build/
COPY . .

RUN mkdir -p bootstrap/cache && chmod -R 775 storage bootstrap/cache && chmod +x docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["bash", "docker-entrypoint.sh"]
