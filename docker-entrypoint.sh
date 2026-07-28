#!/bin/bash
set -e

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

touch database/database.sqlite
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=8000
