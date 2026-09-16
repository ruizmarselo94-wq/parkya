FROM php:8.3-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY src/ /var/www/html/

# Sin .env: en Render las variables (DB_HOST, DB_PASS, etc.) se configuran
# como Environment Variables del servicio, no como archivo dentro de la imagen.

WORKDIR /var/www/html
