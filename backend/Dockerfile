# Stage 1: Build stage
FROM php:8.2-fpm-alpine AS builder

# Install system dependencies and PHP extensions needed for building
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (without dev dependencies for production)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy package files
COPY package.json package-lock.json* ./

# Install Node dependencies (including dev dependencies for build)
RUN npm ci || npm install

# Copy application code
COPY . .

# Complete Composer setup and run post-install scripts
RUN composer dump-autoload --optimize --classmap-authoritative \
    && composer run-script post-autoload-dump --no-interaction

# Build frontend assets
RUN npm run build

# Remove node_modules to reduce size
RUN rm -rf node_modules

# Stage 2: Production stage
FROM php:8.2-fpm-alpine

# Install only runtime dependencies
RUN apk add --no-cache \
    libpng \
    libzip \
    oniguruma \
    postgresql-libs \
    mysql-client

# Install PHP extensions (runtime only)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Set working directory
WORKDIR /var/www/html

# Copy built application from builder stage
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Set proper permissions
RUN chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 8000
EXPOSE 8000

# Switch to non-root user
USER www-data

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD php artisan --version || exit 1

# Start PHP built-in server (for Render)
CMD php artisan serve --host=0.0.0.0 --port=8000
