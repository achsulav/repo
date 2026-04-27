
FROM php:8.2-fpm

# Install Nginx + env tools
RUN apt-get update && apt-get install -y nginx gettext

# Copy project into container
COPY . /var/www

# Copy nginx config
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Set working directory
WORKDIR /var/www

# Expose (optional, Render ignores this but good practice)
EXPOSE 80

# Start both PHP-FPM and Nginx correctly for Render
CMD sh -c "\
envsubst '\$PORT' < /etc/nginx/sites-available/default > /etc/nginx/default.tmp && \
mv /etc/nginx/default.tmp /etc/nginx/sites-available/default && \
php-fpm -D && \
nginx -g 'daemon off;'"