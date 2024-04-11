#!/bin/bash

# Definir permissões na pasta de armazenamento
chmod -R 777 /var/www/html/storage

# Instalar dependências PHP com o Composer
composer install

# Copiar o arquivo de exemplo de configuração .env
cp .env.example .env

# Limpar cache e configurar a aplicação
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan key:generate

# Aguardar o Redis estar pronto antes de iniciar o Horizon e a fila de trabalho
while ! nc -z redis 6379; do
  echo "Aguardando o Redis estar disponível..."
  sleep 1
done

# Iniciar o Horizon para processamento de filas
php artisan horizon &


# Iniciar o trabalhador da fila de forma assíncrona
php artisan queue:work --daemon &

# Iniciar o servidor Apache
apache2-foreground
