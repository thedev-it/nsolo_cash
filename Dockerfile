
# --- Étape 1 : compilation des assets front (Tailwind/Vite) ---
    FROM node:20-alpine AS assets

    WORKDIR /app
    
    COPY package*.json ./
    RUN npm install
    
    COPY . .
    RUN npm run build
    
    # --- Étape 2 : image finale PHP + Nginx ---
    FROM richarvey/nginx-php-fpm:3.1.6
    
    COPY . .
    
    COPY --from=assets /app/public/build ./public/build
    
    COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default.conf
    
    ENV SKIP_COMPOSER=1
    ENV WEBROOT=/var/www/html/public
    ENV PHP_ERRORS_STDERR=1
    ENV RUN_SCRIPTS=1
    ENV REAL_IP_HEADER=1
    ENV PHP_MEM_LIMIT=256
    ENV COMPOSER_ALLOW_SUPERUSER=1
    ENV APP_ENV=production
    
    RUN composer install --no-dev --optimize-autoloader --no-interaction
    
    RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
    RUN chmod +x /var/www/html/pre-start.sh
    
    CMD ["/var/www/html/pre-start.sh"]