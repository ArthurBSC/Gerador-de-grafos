# Use PHP 8.1 CLI (mais simples para Railway)
FROM php:8.1-cli

# Verify PHP version
RUN php --version

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json ./

# Remove any existing composer.lock and update composer
RUN rm -f composer.lock
RUN composer self-update --no-interaction
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Copy application code
COPY . .

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create SQLite database if it doesn't exist
RUN touch database/database.sqlite

# Create startup script with debug
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'echo "=== INICIANDO SISTEMA GERADOR DE GRAFOS ==="' >> /start.sh \
    && echo 'echo "Porta: $PORT"' >> /start.sh \
    && echo 'echo "Diretório: $(pwd)"' >> /start.sh \
    && echo 'echo "Arquivos:"' >> /start.sh \
    && echo 'ls -la' >> /start.sh \
    && echo 'echo "=== CONFIGURANDO AMBIENTE ==="' >> /start.sh \
    && echo 'cp .env.railway .env' >> /start.sh \
    && echo 'echo "=== GERANDO CHAVE ==="' >> /start.sh \
    && echo 'php artisan key:generate --force' >> /start.sh \
    && echo 'echo "=== EXECUTANDO MIGRAÇÕES ==="' >> /start.sh \
    && echo 'php artisan migrate --force' >> /start.sh \
    && echo 'echo "=== CACHEANDO CONFIGURAÇÕES ==="' >> /start.sh \
    && echo 'php artisan config:cache' >> /start.sh \
    && echo 'php artisan route:cache' >> /start.sh \
    && echo 'php artisan view:cache' >> /start.sh \
    && echo 'echo "=== TESTANDO HEALTHCHECK ==="' >> /start.sh \
    && echo 'php artisan route:list | grep health' >> /start.sh \
    && echo 'echo "=== INICIANDO SERVIDOR ==="' >> /start.sh \
    && echo 'php artisan serve --host 0.0.0.0 --port $PORT' >> /start.sh \
    && chmod +x /start.sh

# Expose port from Railway
EXPOSE $PORT

# Start PHP built-in server
CMD ["/start.sh"]

# Railway Deploy - Force Cache Invalidation
