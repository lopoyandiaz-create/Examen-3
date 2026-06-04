#!/bin/bash
composer install --no-dev
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan storage:link