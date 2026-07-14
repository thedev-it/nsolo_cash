FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Configuration de l'image
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV PHP_MEM_LIMIT=256
ENV COMPOSER_ALLOW_SUPERUSER=1

# Nom du script de déploiement lancé par l'image au démarrage
ENV APP_ENV=production

RUN chmod +x /var/www/html/deploy.sh

CMD ["/start.sh"]