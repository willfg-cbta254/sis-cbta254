#!/usr/bin/env bash
# Deploy a producción - sis-cbta254
# Uso en servidor Linux (Forge, Cloud, VPS, cPanel con SSH):
#   bash scripts/deploy.sh
# Requiere: php 8.3+, composer, npm, mysql configurado, .env ya existente

set -e
echo "==> [1/8] Mantenimiento ON"
php artisan down || true

echo "==> [2/8] Actualizar código (si usas git)"
# git pull origin main || true

echo "==> [3/8] Instalar dependencias PHP (sin dev, optimizado)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "==> [4/8] Instalar y compilar assets (Vite + Tailwind 4)"
npm ci --ignore-scripts || npm install --ignore-scripts
npm run build

echo "==> [5/8] Migraciones y optimizaciones Laravel"
php artisan migrate --force
php artisan storage:link || true
php artisan filament:upgrade

echo "==> [6/8] Cachear configuración para producción"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:optimize

echo "==> [7/8] Permisos (ajusta usuario según servidor)"
# chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "==> [8/8] Mantenimiento OFF"
php artisan up

echo "==> Deploy completado"
php artisan about --only=environment
php artisan about --only=drivers
