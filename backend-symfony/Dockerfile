# backend-symfony/Dockerfile
FROM php:8.2-cli-alpine

# Packages nécessaires
RUN apk add --no-cache bash git unzip curl libzip-dev icu-dev zlib-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache \
    && pecl install apcu || true \
    && docker-php-ext-enable apcu || true

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app

# Copier composer et installer dépendances
COPY backend-symfony/composer.json backend-symfony/composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader --no-progress

# Copier tout le projet
COPY backend-symfony/ ./

RUN cp .env.example .env

RUN mkdir -p config/jwt \
    && openssl genpkey -algorithm RSA -out config/jwt/private.pem -aes256 -pass pass:${JWT_PASSPHRASE} \
    && openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin pass:${JWT_PASSPHRASE}

# Générer autoload
RUN composer dump-autoload --optimize && composer run-script post-install-cmd || true

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
