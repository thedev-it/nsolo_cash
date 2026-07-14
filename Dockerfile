FROM richarvey/nginx-php-fpm:3.1.6

# Copie tout le code du projet dans l'image
COPY . .

# Configuration de l'image de base
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV PHP_MEM_LIMIT=256
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=production

# On force composer install DIRECTEMENT dans le build de l'image,
# au lieu de compter sur un script deploy.sh détecté automatiquement.
# Si composer install échoue, le build Docker échoue immédiatement
# et Render affichera l'erreur clairement dans les logs de build.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build des assets front (Tailwind/Vite) — nécessite Node dans l'image de base,
# ce qui est le cas pour richarvey/nginx-php-fpm
RUN npm install && npm run build

# Droits d'écriture nécessaires pour Laravel (storage, cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Rend notre script de pré-démarrage exécutable (migrations, cache),
# il relaie ensuite vers le vrai script de démarrage natif de l'image (nginx + php-fpm)
RUN chmod +x /var/www/html/pre-start.sh

CMD ["/var/www/html/pre-start.sh"]