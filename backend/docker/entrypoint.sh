#!/bin/sh
set -e
ls -la /etc/secrets || true
ls -la /etc/secrets/private.pem || true
ls -la /etc/secrets/public.pem || true
echo "=== JWT DEBUG ="

id

stat -Lc '%A %U %G %n' /etc/secrets/private.pem
stat -Lc '%A %U %G %n' /etc/secrets/public.pem

test -r /etc/secrets/private.pem \
    && echo "PRIVATE READABLE" \
    || echo "PRIVATE NOT READABLE"

test -r /etc/secrets/public.pem \
    && echo "PUBLIC READABLE" \
    || echo "PUBLIC NOT READABLE"

echo "================="

php bin/console doctrine:migrations:migrate --no-interaction
nginx
exec "$@"