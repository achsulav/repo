#!/bin/bash
set -e

# Replace $PORT in Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/default.tmp
mv /etc/nginx/default.tmp /etc/nginx/sites-available/default

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx on port $PORT..."
nginx -g 'daemon off;'
