#!/bin/bash

set -e  # Exit immediately if a command exits with a non-zero status.

# Run Migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Run Currencies Fetch
echo "Fetching currencies..."
php artisan app:fetch-currencies --no-interaction
php artisan app:fetch-currencies-rate --no-interaction

# Clear Cache
echo "Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Start cron in the background
echo "Starting cron..."
cron &

# Set permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www
chmod -R 775 /var/www
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Start supervisord
echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
