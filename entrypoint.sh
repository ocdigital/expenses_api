#!/bin/bash

composer install  # Adicionando o comando composer install

php artisan cache:clear
php artisan config:clear
php artisan config:cache

php artisan key:generate

php artisan horizon &

chmod -R 777 /var/www/html/storage

php artisan queue:work --daemon &

apache2-foreground
