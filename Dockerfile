# Use PHP 8.1 with Nginx (mais estável para Railway)
FROM php:8.1-fpm

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
    nginx \
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
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create SQLite database if it doesn't exist
RUN touch database/database.sqlite

# Configure Nginx
RUN echo 'server {' > /etc/nginx/sites-available/default \
    && echo '    listen $PORT;' >> /etc/nginx/sites-available/default \
    && echo '    server_name localhost;' >> /etc/nginx/sites-available/default \
    && echo '    root /var/www/html/public;' >> /etc/nginx/sites-available/default \
    && echo '    index index.php;' >> /etc/nginx/sites-available/default \
    && echo '    location / {' >> /etc/nginx/sites-available/default \
    && echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/sites-available/default \
    && echo '    }' >> /etc/nginx/sites-available/default \
    && echo '    location ~ \.php$ {' >> /etc/nginx/sites-available/default \
    && echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/sites-available/default \
    && echo '        fastcgi_index index.php;' >> /etc/nginx/sites-available/default \
    && echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/sites-available/default \
    && echo '        include fastcgi_params;' >> /etc/nginx/sites-available/default \
    && echo '    }' >> /etc/nginx/sites-available/default \
    && echo '    location ~ \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {' >> /etc/nginx/sites-available/default \
    && echo '        expires 1y;' >> /etc/nginx/sites-available/default \
    && echo '        add_header Cache-Control "public, immutable";' >> /etc/nginx/sites-available/default \
    && echo '    }' >> /etc/nginx/sites-available/default \
    && echo '}' >> /etc/nginx/sites-available/default

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
    && echo 'echo "=== CONFIGURANDO NGINX ==="' >> /start.sh \
    && echo 'sed -i "s/\$PORT/$PORT/g" /etc/nginx/sites-available/default' >> /start.sh \
    && echo 'echo "=== INICIANDO SERVIÇOS ==="' >> /start.sh \
    && echo 'php-fpm -D' >> /start.sh \
    && echo 'nginx -g "daemon off;"' >> /start.sh \
    && chmod +x /start.sh

# Expose port from Railway
EXPOSE $PORT

# Start services
CMD ["/start.sh"]

# Railway Deploy - Force Cache Invalidation