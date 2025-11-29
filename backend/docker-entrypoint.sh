#!/bin/sh

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not set, generating new key..."
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Clear and cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP server on the port provided by Render (defaults to 8000)
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

