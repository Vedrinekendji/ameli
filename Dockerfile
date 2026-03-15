FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    default-libmysqlclient-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php bin/console asset-map:compile --env=prod \
    && php bin/console cache:clear --env=prod

EXPOSE ${PORT:-8080}

CMD for i in 1 2 3 4 5; do php bin/console doctrine:migrations:migrate --no-interaction --env=prod && break || sleep 5; done && php -S 0.0.0.0:${PORT:-8080} -t public/
