# Stage 1: Build the node.js application
FROM node:20-alpine as build

# Set working directory
WORKDIR /var/www

# Copy package.json and package-lock.json
COPY package*.json ./

# Install npm dependencies
RUN npm ci && npm cache clean --force

# Copy the application files
COPY . .

# Build the application
RUN npm run build

# Use the official PHP image as a base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    zip \
    sqlite3 \
    libsqlite3-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    nginx \
    supervisor \
    cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring pcntl bcmath

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader && \
    composer dump-autoload && \
    composer clear-cache

# Copy the built application from the build stage
COPY --from=build /var/www/public /var/www/public

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www

RUN chown -R www-data:www-data ./storage ./bootstrap/cache ./database && \
    chmod -R 755 ./storage ./bootstrap/cache ./database

# Copy cron job file
COPY cronfile /etc/cron.d/cronfile

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/cronfile

# Specify where to store cron logs
RUN touch /var/log/cron.log
RUN chmod 0666 /var/log/cron.log

# Apply cron job
RUN crontab /etc/cron.d/cronfile

# Create a supervisor configuration file
RUN echo '[supervisord]' > /etc/supervisor/supervisord.conf && \
    echo 'nodaemon=true' >> /etc/supervisor/supervisord.conf && \
    echo '[program:php-fpm]' >> /etc/supervisor/supervisord.conf && \
    echo 'command=/usr/local/sbin/php-fpm' >> /etc/supervisor/supervisord.conf && \
    echo '[program:nginx]' >> /etc/supervisor/supervisord.conf && \
    echo 'command=/usr/sbin/nginx -g "daemon off;"' >> /etc/supervisor/supervisord.conf

# Expose ports
EXPOSE 80 9000

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
