# Comandos Úteis - Servidor

## MySQL

```bash
# Conectar no MySQL
mysql -u dispatcher_user -pDisp@tch3R9182 dispatcher_control

# Ver todas as tabelas
SHOW TABLES;

# Ver estrutura de uma tabela
DESCRIBE nome_da_tabela;

# Consultar dados
SELECT * FROM users LIMIT 10;

# Sair do MySQL
exit;
```

## Logs

```bash
# Ver últimos 50 erros
tail -n 50 storage/logs/laravel.log | grep -i error

# Ver todos os logs recentes
tail -n 100 storage/logs/laravel.log

# Acompanhar logs em tempo real
tail -f storage/logs/laravel.log

# Procurar por erro específico
grep -i "erro_aqui" storage/logs/laravel.log
```

## Laravel

```bash
cd /var/www/dispatcher-control

# Limpar todos os caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Recriar cache de rotas (importante após mudanças)
php artisan route:cache

# Ver rotas disponíveis
php artisan route:list

# Executar migrations
php artisan migrate

# Executar seeders
php artisan db:seed
```

## Git

```bash
cd /var/www/dispatcher-control

# Atualizar código do servidor
git pull origin main

# Ver status
git status

# Ver últimas alterações
git log --oneline -10
```

## Arquivos e Permissões

```bash
# Ver permissões
ls -la storage/
ls -la bootstrap/cache/

# Corrigir permissões
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## Serviços

```bash
# Status do Apache
systemctl status apache2

# Reiniciar Apache
systemctl restart apache2

# Status do MySQL
systemctl status mysql

# Ver processos PHP
ps aux | grep php
```

## Ambiente

```bash
# Ver variáveis do .env
grep "MAIL_" /var/www/dispatcher-control/.env
grep "DB_" /var/www/dispatcher-control/.env

# Fazer backup do .env
cp /var/www/dispatcher-control/.env /var/www/dispatcher-control/.env.backup
```

