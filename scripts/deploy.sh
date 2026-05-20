#!/bin/bash

cd /var/www/html/knolzi-vanshika

echo "Deployment started"

composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan optimize:clear

php artisan config:cache

php artisan route:cache

sudo chown -R www-data:www-data storage bootstrap/cache

sudo chmod -R 775 storage bootstrap/cache

sudo systemctl restart apache2

echo "Deployment completed"
