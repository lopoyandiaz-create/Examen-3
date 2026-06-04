FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libsqlite3-dev nodejs npm \
    && docker-php-ext-install zip pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache

RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 10000

CMD php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=10000