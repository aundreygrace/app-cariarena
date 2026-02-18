# Gunakan PHP 8.3 FPM
FROM php:8.3-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd mbstring exif pcntl bcmath zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua kode aplikasi
COPY . .

# Install dependencies PHP
RUN composer install --optimize-autoloader --no-dev

# Jangan cache config agar Laravel baca env runtime dari Railway
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan view:cache

# Expose port yang akan digunakan Railway
EXPOSE ${PORT}

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}

# Jalankan Laravel di port dari Railway
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
