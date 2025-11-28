# backend-symfony/Dockerfile
FROM php:8.2-cli-alpine

# Packages nécessaires
RUN apk add --no-cache bash git unzip curl libzip-dev icu-dev zlib-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache \
    && pecl install apcu || true \
    && docker-php-ext-enable apcu || true

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app

# Copier composer et installer dépendances
COPY backend-symfony/composer.json backend-symfony/composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader --no-progress

# Copier tout le projet
COPY backend-symfony/ ./

# Générer autoload
RUN composer dump-autoload --optimize && composer run-script post-install-cmd || true

EXPOSE 8000

CMD ["symfony", "serve", "--no-tls", "--port=8000", "--allow-http"]
