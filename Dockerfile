FROM php:8.2-fpm

# Install nginx + dependencies
RUN apt-get update && apt-get install -y nginx

# Copy project
COPY . /var/www

# Copy nginx config
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Set working dir
WORKDIR /var/www

# Start both services
CMD service nginx start && php-fpm
