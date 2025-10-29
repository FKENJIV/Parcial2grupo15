# Stage 1: build assets with Node 20 (required for Vite 7 and laravel-vite-plugin 2.0)
FROM node:20 AS node_builder
WORKDIR /app

# Install composer and dependencies to get vendor files (needed for Flux CSS)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy package manifests and install
COPY package*.json ./
RUN npm ci

# Copy source and build assets
COPY . .
RUN composer dump-autoload --optimize --no-dev
RUN npm run build

# Stage 2: final image (nginx + php-fpm)
FROM richarvey/nginx-php-fpm:3.1.6

# Copy compiled assets from node builder (ajusta la ruta si Vite genera en otra carpeta)
COPY --from=node_builder /app/public /var/www/html/public

# Copy rest of the app
COPY . .

# Copy nginx conf and make deploy scripts executable
RUN mkdir -p /etc/nginx/conf.d \
 && cp -r conf/nginx/* /etc/nginx/conf.d/ || true \
 && chmod +x ./scripts/*.sh || true

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]