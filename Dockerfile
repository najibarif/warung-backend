FROM php:8.2-cli

# Install dependencies yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy seluruh file project
COPY . .

# Install dependencies Laravel (tanpa file dev)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Beri akses ke folder storage (Penting!)
RUN chmod -R 777 storage bootstrap/cache

# Jalankan server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
