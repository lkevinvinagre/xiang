#!/bin/sh
chown -R 82:82 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
exec "$@"