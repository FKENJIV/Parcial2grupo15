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

# Copy rest of the app FIRST
COPY . .

# Copy compiled assets from node builder AFTER (so they don't get overwritten)
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Copy PHP-FPM configuration
RUN cp conf/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# Ensure proper permissions for Laravel
RUN chown -R nginx:nginx /var/www/html \
 && chmod -R 755 /var/www/html/storage \
 && chmod -R 755 /var/www/html/bootstrap/cache

# Copy scripts to /scripts/ where the entrypoint expects them
COPY scripts/00-laravel-deploy.sh /scripts/00-laravel-deploy.sh
RUN chmod +x /scripts/00-laravel-deploy.sh

# Copy nginx conf and remove default
RUN rm -f /etc/nginx/sites-enabled/default \
 && cp conf/nginx/nginx-site.conf /etc/nginx/sites-available/default.conf \
 && ln -sf /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

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