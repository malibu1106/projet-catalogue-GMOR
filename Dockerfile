# Utilisez l'image de base souhaitée pour votre application PHP avec Apache
FROM php:8.3-apache

# Mettre à jour le système et installer les dépendances nécessaires
RUN apt-get update && apt-get upgrade -y

# Installer les extensions PHP nécessaires (mysqli, pdo, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# Définir le fuseau horaire à Europe/Paris
RUN ln -snf /usr/share/zoneinfo/Europe/Paris /etc/localtime && echo Europe/Paris > /etc/timezone

# Exposer le port 80
EXPOSE 80
