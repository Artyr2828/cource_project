#!/bin/sh
set -e
ls -la /etc/secrets || true
ls -la /etc/secrets/private.pem || true
ls -la /etc/secrets/public.pem || true
php bin/console doctrine:migrations:migrate --no-interaction
nginx
exec "$@"