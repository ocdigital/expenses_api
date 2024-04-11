#!/bin/bash

chmod -R 777 /var/www/html/storage

composer install

cp .env.example .env

php artisan key:generate

php artisan horizon &

php artisan queue:work --daemon &

php artisan config:clear

php artisan migrate

php artisan db:seed

apache2-foreground
