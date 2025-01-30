FROM php:8.2-fpm

WORKDIR /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP ve gerekli eklentileri yükle
RUN docker-php-ext-install pdo pdo_mysql

# Proje dosyalarını kopyala
COPY html/lumen-app /var/www

# Composer bağımlılıklarını yükle
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

# Nginx kurulumu
RUN apt-get update && apt-get install -y nginx && apt-get clean -y

# Nginx konfigürasyonunu kopyala
COPY nginx.conf /etc/nginx/nginx.conf

# Portları aç
EXPOSE 80

# Servisleri başlat
CMD service nginx start && php-fpm
