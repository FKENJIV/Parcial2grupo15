### Dockerfile multietapa para aplicación Laravel

############################################################
# Etapa composer: instalar dependencias PHP (genera /app/vendor)
############################################################
FROM php:8.3-cli-bullseye as composer_builder
WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

COPY . ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

############################################################
# Etapa node: construir assets frontend (Vite)
############################################################
FROM node:20-bullseye as node_builder
WORKDIR /app

COPY package*.json ./
RUN npm ci --silent

COPY . .
COPY --from=composer_builder /app/vendor ./vendor

# Compilar los assets de Vite
RUN npm run build \
 && echo "--- public/build contents ---" && ls -la public/build

############################################################
# Imagen final: runtime PHP
############################################################
FROM php:8.3-cli-bullseye
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpq-dev \
    unzip \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Copiar la app PHP (vendor incluido)
COPY --from=composer_builder /app /var/www/html

# Copiar solo los assets generados (para no sobreescribir public/)
COPY --from=node_builder /app/public/build /var/www/html/public/build

RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 8080
ENV APP_ENV=production

CMD ["sh", "-lc", "php artisan key:generate --force 2>/dev/null || true; php artisan config:cache 2>/dev/null || true; php artisan route:cache 2>/dev/null || true; php artisan view:cache 2>/dev/null || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
