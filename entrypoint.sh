#!/bin/sh

echo "Aguardando 60 segundos para o MySQL iniciar..."
sleep 60

php artisan key:generate
php artisan migrate --force
php artisan db:seed --force

exec "$@"