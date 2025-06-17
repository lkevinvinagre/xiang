#!/bin/sh
set -e

# wait until DB is reachable (dockerize, wait-for-it, or simple loop)
until php artisan migrate --force; do
  echo "Waiting for DB..."
  sleep 2
done

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
