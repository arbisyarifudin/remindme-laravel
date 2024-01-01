#!/bin/bash

composer install
php artisan migrate
php artisan db:seed
npm install
# npm run build
npm run dev

# Run PHP-FPM in background
php-fpm
