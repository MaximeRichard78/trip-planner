FROM dunglas/frankenphp:1-php8.4-alpine

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    opcache \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN echo "APP_ENV=prod" > /app/.env && \
    echo "APP_SECRET=placeholder" >> /app/.env && \
    echo "DATABASE_URL=postgresql://placeholder:placeholder@localhost:5432/placeholder" >> /app/.env && \
    echo "DEFAULT_URI=http://localhost:8080" >> /app/.env

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

RUN php bin/console cache:warmup --env=prod --no-debug || true

ENV SERVER_NAME=:8080
EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]