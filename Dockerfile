# --- Étape 1 : compilation des assets front (Tailwind/Vite) ---
    FROM node:20-alpine AS assets

    WORKDIR /app
    
    COPY package*.json ./
    RUN npm install
    
    COPY . .
    RUN npm run build
    
    # --- Étape 2 : image finale PHP + Nginx ---
    FROM richarvey/nginx-php-fpm:3.1.6
    
    # Copie tout le code du projet
    COPY . .
    
    # Récupère les assets compilés à l'étape 1 (écrase le dossier public/build vide)
    COPY --from=assets /app/public/build ./public/build
    
    # Configuration de l'image de base
    ENV SKIP_COMPOSER=1
    ENV WEBROOT=/var/www/html/public
    ENV PHP_ERRORS_STDERR=1
    ENV RUN_SCRIPTS=1
    ENV REAL_IP_HEADER=1
    ENV PHP_MEM_LIMIT=256
    ENV COMPOSER_ALLOW_SUPERUSER=1
    ENV APP_ENV=production
    
    # Installation des dépendances PHP
    RUN composer install --no-dev --optimize-autoloader --no-interaction
    
    # Droits d'écriture nécessaires pour Laravel (storage, cache)
    RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
    # Rend notre script de pré-démarrage exécutable (migrations, cache),
    # il relaie ensuite vers le vrai script de démarrage natif de l'image (nginx + php-fpm)
    RUN chmod +x /var/www/html/pre-start.sh
    
    CMD ["/var/www/html/pre-start.sh"]