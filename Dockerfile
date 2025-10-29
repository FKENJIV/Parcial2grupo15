# Stage 1: Install PHP dependencies (for vendor/livewire/flux CSS)
FROM composer:latest AS php_dependencies
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Stage 2: Build assets with Node 20 (required for Vite 7 and laravel-vite-plugin 2.0)
FROM node:20 AS node_builder
WORKDIR /app

# Copy vendor from PHP stage (needed for Flux CSS reference)
COPY --from=php_dependencies /app/vendor ./vendor

# Copy package manifests and install
COPY package*.json ./
RUN npm ci

# Copy source and build assets
COPY . .
RUN npm run build

# Stage 3: final image (nginx + php-fpm)
FROM richarvey/nginx-php-fpm:3.1.6

# Copy compiled assets from node builder
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