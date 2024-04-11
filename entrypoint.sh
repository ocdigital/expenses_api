#!/bin/bash

# Definir permissões na pasta de armazenamento
chmod -R 777 /var/www/html/storage

# Instalar dependências PHP com o Composer
composer install

# Copiar o arquivo de exemplo de configuração .env
cp .env.example .env

# Limpar cache e configurar a aplicação
php artisan key:generate


# Iniciar o Horizon para processamento de filas
php artisan horizon &


# Iniciar o trabalhador da fila de forma assíncrona
php artisan queue:work --daemon &

php artisan config:clear

# Iniciar o servidor Apache
apache2-foreground
