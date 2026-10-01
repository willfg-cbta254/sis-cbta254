# syntax=docker/dockerfile:1.7
# =============================================================================
# Dockerfile - sis-cbta254 (Laravel 13 + Filament 5 + PHP 8.4)
# Multi-stage: vendor (composer) + frontend (node/vite) + runtime (apache)
# =============================================================================

# ---------------------------------------------------------------------------
# Stage 1: Dependencias PHP con Composer
# ---------------------------------------------------------------------------
FROM composer:2.8 AS vendor

# ext-intl es requerida por filament/support - instalar en etapa composer
RUN apk add --no-cache icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

WORKDIR /app

# Copia solo los manifiestos para aprovechar cache de Docker
COPY composer.json composer.lock ./

# Instala dependencias sin scripts/autoloader (se hará al copiar el código)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# Copia el resto del código y genera autoloader optimizado
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# ---------------------------------------------------------------------------
# Stage 2: Assets frontend con Node + Vite
# ---------------------------------------------------------------------------
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

# Copia recursos necesarios para el build
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Necesario porque laravel-vite-plugin resuelve paths del proyecto
COPY --from=vendor /app/vendor ./vendor
COPY composer.json ./

RUN npm run build

# ---------------------------------------------------------------------------
# Stage 3: Runtime - PHP 8.4 + Apache
# ---------------------------------------------------------------------------
FROM php:8.4-apache AS app

ARG WWWGROUP=1000
ARG WWWUSER=1000
ENV DEBIAN_FRONTEND=noninteractive \
    APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# --- Dependencias del sistema ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        zip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
        default-mysql-client \
        cron \
        supervisor \
        gosu \
    && rm -rf /var/lib/apt/lists/*

# --- Extensiones PHP necesarias para Laravel + Filament + DomPDF ---
# gd (con freetype/jpeg), pdo_mysql, bcmath, intl, zip, exif, pcntl, opcache, sockets
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        exif \
        pcntl \
        sockets \
        gd \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# --- Configuración Apache ---
RUN a2enmod rewrite headers expires ssl \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configuración PHP para producción (ajustable vía variables de entorno)
COPY <<'PHPINI' /usr/local/etc/php/conf.d/99-laravel.ini
memory_limit=512M
upload_max_filesize=50M
post_max_size=50M
max_execution_time=120
max_input_vars=5000
expose_php=Off
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.save_comments=1
PHPINI

# --- Copia del código ---
# Copia vendor ya instalado (sin dev) y assets compilados
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Copia el resto del proyecto (excluye lo ignorado por .dockerignore)
COPY . .

# Asegura que el DocumentRoot exista y permisos correctos
# Limpia cache de bootstrap que pueda venir del host con paquetes dev (Boost)
RUN rm -f bootstrap/cache/*.php 2>/dev/null || true \
    && mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public \
    && rm -rf node_modules/.cache 2>/dev/null || true

# --- Entrypoint ---
COPY <<'ENTRYPOINT' /usr/local/bin/docker-entrypoint.sh
#!/bin/bash
set -e

# Helper para ejecutar artisan como www-data (evita logs/cache con owner root)
run_as_www() {
  if command -v gosu >/dev/null 2>&1; then
    gosu www-data "$@"
  elif command -v runuser >/dev/null 2>&1; then
    runuser -u www-data -- "$@"
  else
    su www-data -s /bin/bash -c "$*"
  fi
}

# Asegura estructura y permisos antes de cualquier artisan (evita Permission denied en laravel.log)
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
touch storage/logs/laravel.log 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache public 2>/dev/null || true
chmod -R 775 storage bootstrap/cache public 2>/dev/null || true
chmod 664 storage/logs/laravel.log 2>/dev/null || true

# Espera a MySQL si DB_HOST está definido (opcional)
if [ -n "$DB_HOST" ]; then
  echo "Esperando a MySQL en $DB_HOST:${DB_PORT:-3306}..."
  for i in {1..30}; do
    if mysqladmin ping -h "$DB_HOST" -P "${DB_PORT:-3306}" --silent 2>/dev/null; then
      echo "MySQL disponible."
      break
    fi
    echo "Intento $i/30 - MySQL no disponible, reintentando en 2s..."
    sleep 2
  done
fi

# Genera APP_KEY si no existe
if [ -z "$APP_KEY" ] || grep -q "APP_KEY=$" .env 2>/dev/null; then
  echo "Generando APP_KEY..."
  run_as_www php artisan key:generate --force || true
fi

# Link de storage (idempotente)
run_as_www php artisan storage:link 2>/dev/null || true

# Migraciones y cache - solo en producción (evita cache stale con opcache.validate_timestamps=0)
if [ "$APP_ENV" = "production" ]; then
  echo "Optimizando aplicación (production)..."
  rm -f bootstrap/cache/*.php 2>/dev/null || true
  run_as_www php artisan optimize:clear 2>/dev/null || true
  run_as_www php artisan migrate --force --no-interaction || echo "Advertencia: migrate falló"
  run_as_www php artisan config:cache || true
  run_as_www php artisan route:cache || true
  run_as_www php artisan view:cache || true
  run_as_www php artisan filament:upgrade 2>/dev/null || true
else
  echo "Entorno $APP_ENV - limpiando caches y ejecutando migraciones sin cache..."
  rm -f bootstrap/cache/*.php 2>/dev/null || true
  run_as_www php artisan optimize:clear 2>/dev/null || true
  run_as_www php artisan migrate --force --no-interaction || echo "Advertencia: migrate falló"
  run_as_www php artisan filament:upgrade 2>/dev/null || true
fi

# Corrige permisos en cada arranque (por volúmenes) - asegura que todo quede como www-data
chown -R www-data:www-data storage bootstrap/cache public 2>/dev/null || true
chmod -R 775 storage bootstrap/cache public 2>/dev/null || true
chmod 664 storage/logs/laravel.log 2>/dev/null || true

exec apache2-foreground
ENTRYPOINT
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- Usuario no root para Composer/artisan (opcional) ---
# Mantiene www-data como usuario de Apache, compatible con volúmenes host
RUN usermod -u $WWWUSER www-data 2>/dev/null || true \
    && groupmod -g $WWWGROUP www-data 2>/dev/null || true

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/up 2>/dev/null || curl -f http://localhost/ 2>/dev/null || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
