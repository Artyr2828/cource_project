#!/bin/sh
set -e
if [ -d "/etc/secrets" ]; then
    chmod 600 /etc/secrets/*.pem 2>/dev/null || true
    chown -R www-data:www-data /etc/secrets 2>/dev/null || true
fi
php bin/console doctrine:migrations:migrate --no-interaction

exec "$@"