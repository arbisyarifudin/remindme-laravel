#!/bin/bash

# Install dependencies
composer install

# Copy .env.example to .env
cp .env.example .env

# Generate key
php artisan key:generate

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Install node dependencies
rm -rf node_modules
npm install

# Build assets
npm run build

# Run PHP-FPM in background
php-fpm
