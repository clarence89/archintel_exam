FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql mysqli

RUN a2enmod rewrite

COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
