FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip sqlite3 libsqlite3-dev curl \
    && docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache database/database.sqlite
RUN chmod -R 775 storage bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD php artisan migrate --force && php artisan storage:link && apache2-foreground