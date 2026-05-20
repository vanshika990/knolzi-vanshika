#!/bin/bash

cd /var/www/html/knolzi-vanshika

echo "=============================="
echo "Deployment Started"
echo "=============================="

# Safe ownership
sudo chown -R ubuntu:www-data /var/www/html/knolzi-vanshika

# Install dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Create required Laravel folders
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Create log file if missing
touch storage/logs/laravel.log

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 775 storage/logs
sudo chmod 664 storage/logs/laravel.log

# Clear and rebuild Laravel cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart Apache
sudo systemctl restart apache2

echo "=============================="
echo "Deployment Finished Successfully"
echo "=============================="
