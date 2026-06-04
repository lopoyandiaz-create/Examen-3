FROM php:8.2-cli

RUN apt-get update && apt-get install -y unzip sqlite3 libsqlite3-dev curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

RUN touch database/database.sqlite
RUN php artisan storage:link

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}