### Dockerfile multietapa para aplicación Laravel
### - etapa 'node_builder' construye los assets frontend con Node
### - etapa 'composer_builder' instala las dependencias PHP (vendor)
### - la imagen final ejecuta la aplicación usando el servidor integrado de PHP (se enlaza a $PORT)

############################################################
# Etapa node: construir assets frontend (Vite)
############################################################
FROM node:20-bullseye as node_builder
WORKDIR /app

# Copiar los archivos de package primero para acelerar la caché de npm install
COPY package*.json ./
RUN npm ci --silent

# Copiar el resto de la aplicación y construir los assets
COPY . .
RUN npm run build

############################################################
# Etapa composer: instalar dependencias PHP
############################################################
FROM php:8.3-cli-bullseye as composer_builder
WORKDIR /app

# Dependencias del sistema necesarias para extensiones PHP comunes y Composer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Copiar archivos de la aplicación desde la etapa node (incluye los assets construidos)
COPY --from=node_builder /app /app

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias PHP (producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

############################################################
# Imagen final: runtime PHP
############################################################
FROM php:8.3-cli-bullseye
WORKDIR /var/www/html

# Dependencias del sistema requeridas en tiempo de ejecución (soporte Postgres, etc.)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpq-dev \
    unzip \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Copiar la aplicación (con vendor) y los assets públicos construidos
COPY --from=composer_builder /app /var/www/html
COPY --from=node_builder /app/public /var/www/html/public

# Asegurar que los directorios storage y bootstrap/cache son escribibles por el proceso web
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Exponer el puerto (Render provee $PORT en tiempo de ejecución)
EXPOSE 8080

# Usar variables de entorno para configuración; Render las proveerá.
ENV APP_ENV=production

# Comando por defecto: ejecutar el servidor integrado de Laravel y enlazar a $PORT (Render establece PORT)
# Esto es simple y funciona bien para el servicio web de Render. Para producción con alta carga,
# considera reemplazar por nginx + php-fpm.
CMD ["sh", "-lc", "php artisan key:generate --force 2>/dev/null || true; php artisan config:cache 2>/dev/null || true; php artisan route:cache 2>/dev/null || true; php artisan view:cache 2>/dev/null || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
