FROM php:8.3-apache

# Instalar extensiones requeridas
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev libssl-dev pkg-config \
    && docker-php-ext-install zip pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar la extensión MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalar la extensión XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Activar mod_rewrite de Apache (importante para Laravel)
RUN a2enmod rewrite

# Configurar Apache para Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html
