# Stage 1 — builder con Node (asegura Node 18)
FROM node:18 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --legacy-peer-deps
COPY . .
RUN npm run build

# Stage 2 — imagen final (nginx + php)
FROM richarvey/nginx-php-fpm:3.1.6
# copia los assets construidos
COPY --from=node_builder /app/public/build /var/www/html/public/build
COPY . .

# copiar nginx conf y hacer ejecutables los scripts
RUN mkdir -p /etc/nginx/conf.d \
 && cp -r conf/nginx/* /etc/nginx/conf.d/ || true \
 && chmod +x ./scripts/*.sh || true

# variables de entorno y CMD como antes...
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