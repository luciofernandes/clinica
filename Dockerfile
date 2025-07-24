FROM php:8.3-fpm-bullseye

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl

# Instala Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /app

# Copia projeto
COPY . .

# Copia o entrypoint personalizado
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

# Comando padrão do container (Render espera que você exponha algo na 8000)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
