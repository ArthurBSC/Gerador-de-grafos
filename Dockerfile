# Use PHP 8.1 with Apache (melhor para tipos MIME)
FROM php:8.1-apache

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

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache for Railway
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Create startup script
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'echo "=== INICIANDO SISTEMA GERADOR DE GRAFOS ==="' >> /start.sh \
    && echo 'echo "Porta: $PORT"' >> /start.sh \
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
    && echo 'echo "=== CONFIGURANDO APACHE ==="' >> /start.sh \
    && echo 'echo "Listen $PORT" >> /etc/apache2/ports.conf' >> /start.sh \
    && echo 'echo "<VirtualHost *:$PORT>" > /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "    DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "    <Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf' >> /start.sh \
    && echo 'echo "=== INICIANDO APACHE ==="' >> /start.sh \
    && echo 'apache2-foreground' >> /start.sh \
    && chmod +x /start.sh

# Expose port from Railway
EXPOSE $PORT

# Start Apache
CMD ["/start.sh"]

# Railway Deploy - Force Cache Invalidation