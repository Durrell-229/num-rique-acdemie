# Étape 1 : Builder avec Composer et Node
FROM php:8.2-cli AS build

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /app
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer dépendances JS et compiler les assets
RUN npm install && npm run build

---

# Étape 2 : Image finale avec Apache
FROM php:8.2-apache

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installer extensions PHP nécessaires
RUN docker-php-ext-install pdo_mysql mbstring bcmath gd

# Copier les fichiers depuis l’étape build
COPY --from=build /app /var/www/html

# Définir le dossier public comme racine Apache
WORKDIR /var/www/html
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
