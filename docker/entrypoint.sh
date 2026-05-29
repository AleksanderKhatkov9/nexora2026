#!/bin/sh
set -e

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d node_modules ] || [ ! -d node_modules/vue ] || [ ! -d node_modules/vue-router ]; then
  echo "Installing npm dependencies..."
  if [ -f package-lock.json ]; then
    npm ci --no-audit --no-fund
  else
    npm install --no-audit --no-fund
  fi
fi

if [ ! -f public/build/manifest.json ]; then
  echo "Building frontend assets (Vite + Vue)..."
  npm run build
fi

if [ ! -e public/storage ]; then
  echo "Creating public/storage symlink..."
  php artisan storage:link
fi

exec php-fpm
