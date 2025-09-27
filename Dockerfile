# Use PHP 8.2 with built-in server (mais simples e confiável)
FROM php:8.2-cli

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

# Update composer and install dependencies
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
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create SQLite database if it doesn't exist
RUN touch database/database.sqlite

# Create startup script
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'echo "=== INICIANDO SISTEMA GERADOR DE GRAFOS ==="' >> /start.sh \
    && echo 'echo "Porta: $PORT"' >> /start.sh \
    && echo 'echo "=== CONFIGURANDO AMBIENTE ==="' >> /start.sh \
    && echo 'cat > .env << EOF' >> /start.sh \
    && echo 'APP_NAME="Sistema Gerador de Grafos"' >> /start.sh \
    && echo 'APP_ENV=production' >> /start.sh \
    && echo 'APP_KEY=' >> /start.sh \
    && echo 'APP_DEBUG=false' >> /start.sh \
    && echo 'APP_URL=https://gerador-de-grafos-production.up.railway.app' >> /start.sh \
    && echo 'FORCE_HTTPS=true' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'LOG_CHANNEL=stack' >> /start.sh \
    && echo 'LOG_LEVEL=error' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'DB_CONNECTION=sqlite' >> /start.sh \
    && echo 'DB_DATABASE=/var/www/html/database/database.sqlite' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'BROADCAST_DRIVER=log' >> /start.sh \
    && echo 'CACHE_DRIVER=file' >> /start.sh \
    && echo 'FILESYSTEM_DISK=local' >> /start.sh \
    && echo 'QUEUE_CONNECTION=sync' >> /start.sh \
    && echo 'SESSION_DRIVER=file' >> /start.sh \
    && echo 'SESSION_LIFETIME=120' >> /start.sh \
    && echo 'SESSION_SECURE_COOKIE=true' >> /start.sh \
    && echo 'SESSION_HTTP_ONLY=true' >> /start.sh \
    && echo 'SESSION_SAME_SITE=lax' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'MAIL_MAILER=log' >> /start.sh \
    && echo 'MAIL_HOST=mailpit' >> /start.sh \
    && echo 'MAIL_PORT=1025' >> /start.sh \
    && echo 'MAIL_USERNAME=null' >> /start.sh \
    && echo 'MAIL_PASSWORD=null' >> /start.sh \
    && echo 'MAIL_ENCRYPTION=null' >> /start.sh \
    && echo 'MAIL_FROM_ADDRESS="hello@example.com"' >> /start.sh \
    && echo 'MAIL_FROM_NAME="${APP_NAME}"' >> /start.sh \
    && echo 'EOF' >> /start.sh \
    && echo 'echo "=== GERANDO CHAVE ==="' >> /start.sh \
    && echo 'php artisan key:generate --force' >> /start.sh \
    && echo 'echo "=== VERIFICANDO BANCO DE DADOS ==="' >> /start.sh \
    && echo 'ls -la database/' >> /start.sh \
    && echo 'echo "=== CONFIGURANDO SESSÕES ==="' >> /start.sh \
    && echo 'mkdir -p storage/framework/sessions' >> /start.sh \
    && echo 'chmod -R 777 storage/' >> /start.sh \
    && echo 'echo "=== EXECUTANDO MIGRAÇÕES ==="' >> /start.sh \
    && echo 'php artisan migrate --force' >> /start.sh \
    && echo 'echo "=== LIMPANDO CACHES ==="' >> /start.sh \
    && echo 'php artisan cache:clear' >> /start.sh \
    && echo 'php artisan config:clear' >> /start.sh \
    && echo 'echo "=== CACHEANDO CONFIGURAÇÕES ==="' >> /start.sh \
    && echo 'php artisan config:cache' >> /start.sh \
    && echo 'php artisan route:cache' >> /start.sh \
    && echo 'php artisan view:cache' >> /start.sh \
    && echo 'echo "=== TESTANDO LARAVEL ==="' >> /start.sh \
    && echo 'php artisan --version' >> /start.sh \
    && echo 'echo "=== INICIANDO SERVIDOR PHP ==="' >> /start.sh \
    && echo 'php -S 0.0.0.0:$PORT -t public' >> /start.sh \
    && chmod +x /start.sh

# Expose port from Railway
EXPOSE $PORT

# Start PHP built-in server
CMD ["/start.sh"]

# Railway Deploy - Force Cache Invalidation