#!/bin/bash
# Script helper para corrigir permissões do Laravel dentro do container Docker
# Execute este script dentro do container: docker-compose exec dispatcher /app/fix-permissions.sh

chown -R application:application /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "Permissões corrigidas com sucesso!"

