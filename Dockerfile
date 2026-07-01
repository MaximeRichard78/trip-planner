# Dockerfile
FROM dunglas/frankenphp:1-php8.4

# Extensions PHP nécessaires (pdo_pgsql pour Supabase)
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    opcache \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Config FrankenPHP / Caddy
ENV SERVER_NAME=:80
ENV APP_ENV=prod

COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

RUN php bin/console cache:clear --env=prod --no-debug || true

EXPOSE 80