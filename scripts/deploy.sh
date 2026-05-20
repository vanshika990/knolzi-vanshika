#!/bin/bash

cd /var/www/html/knolzi-vanshika

echo "Deployment Started"

sudo chown -R ubuntu:ubuntu /var/www/html/knolzi-vanshika

composer install --no-interaction

php artisan optimize:clear

php artisan config:cache

sudo chmod -R 775 storage bootstrap/cache

sudo systemctl restart apache2

echo "Deployment Finished"

