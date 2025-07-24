#!/bin/bash

# Corrige permissões
echo "Corrigindo permissões de storage e cache..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

exec "$@"
