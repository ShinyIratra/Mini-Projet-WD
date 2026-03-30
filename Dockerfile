FROM php:8.2-apache

# Installer les dépendances pour PostgreSQL
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet (optionnel si monté via docker-compose, mais bonne pratique)
COPY . /var/www/html/

# Ajuster les permissions pour s'assurer que PHP peut écrire si nécessaire
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer l'output buffering pour éviter les erreurs "headers already sent" dues aux BOM locaux récurrents
RUN echo "output_buffering = On" > /usr/local/etc/php/conf.d/output_buffering.ini

EXPOSE 80

CMD ["apache2-foreground"]
