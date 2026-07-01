FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    opcache \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

# Créer un .env minimal pour éviter l'erreur PathException au build
RUN echo "APP_ENV=prod\nAPP_SECRET=placeholder" > /app/.env

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

# Préchauffer le cache sans planter si .env incomplet
RUN php bin/console cache:warmup --env=prod --no-debug || true

# Port 8080 : pas besoin de droits root sur Render free tier
ENV SERVER_NAME=:8080
EXPOSE 8080