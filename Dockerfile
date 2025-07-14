FROM php:8.2-fpm-bullseye

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    gnupg \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl

# Instala Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Confirma instalação
RUN node -v && npm -v

# Copy Composer from the official image
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]