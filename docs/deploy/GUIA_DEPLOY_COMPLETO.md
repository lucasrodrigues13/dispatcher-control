# Guia Completo de Deploy - Dispatcher Control

**Para Desenvolvedores Novos**  
**Servidor:** vmi2795941  
**URL:** https://app.abbrtransportandshipping.com

Este guia mostra **exatamente o que fazer** para fazer deploy da aplicação Dispatcher Control no servidor de produção.

---

## 📋 Pré-requisitos

Antes de começar, você precisa ter:

- [ ] Acesso SSH ao servidor (credenciais)
- [ ] Aplicação preparada localmente (com `composer install` e `npm run build`)
- [ ] Arquivo `.env.production` configurado (ou estar pronto para configurar)
- [ ] Repositório do código (se for via Git) ou arquivo compactado

---

## 🚀 Passo a Passo do Deploy

### PASSO 1: Preparar Aplicação Localmente (Sua Máquina)

**Antes de fazer upload, prepare a aplicação:**

```powershell
# No Windows (na raiz do projeto)
cd dispatcher-control

# Instalar dependências PHP (produção)
composer install --no-dev --optimize-autoloader

# Instalar dependências Node
npm install

# Compilar assets para produção
npm run build

# Verificar se o build foi criado
ls public/build/
```

**✅ Resultado esperado:**
- Diretório `vendor/` com dependências PHP
- Diretório `node_modules/` com dependências Node
- Diretório `public/build/` com assets compilados

---

### PASSO 2: Conectar ao Servidor via SSH

```bash
# No Windows (PowerShell) ou Linux/Mac
ssh root@vmi2795941

# Ou se tiver chave SSH configurada
ssh -i ~/.ssh/chave.pem root@vmi2795941
```

**Se for primeira conexão:** Digite `yes` para aceitar a fingerprint.

---

### PASSO 3: Verificar Ambiente do Servidor

**Execute estes comandos para verificar se tudo está pronto:**

```bash
# Verificar versões
php -v
apache2 -v
mysql --version

# Verificar se serviços estão rodando
systemctl status apache2 | head -3
systemctl status mysql | head -3

# Verificar se Composer está instalado
composer --version
```

**✅ Resultado esperado:**
- PHP 8.3.6 (ou superior)
- Apache 2.4.58 (ou superior)
- MySQL 8.0.43 (ou superior)
- Composer instalado
- Serviços rodando

**Se algum serviço não estiver rodando:**
```bash
# Iniciar Apache
systemctl start apache2

# Iniciar MySQL
systemctl start mysql
```

---

### PASSO 4: Preparar Banco de Dados

**Se o banco ainda não existe, crie:**

```bash
# Conectar ao MySQL como root/admin
mysql -u debian-sys-maint -pGeNSDwMkZZguaTAm

# Dentro do MySQL, execute:
```

```sql
-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS dispatcher_control CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar usuário (se não existir)
CREATE USER IF NOT EXISTS 'dispatcher_user'@'localhost' IDENTIFIED BY 'Disp@tch3R9182';

-- Dar permissões
GRANT ALL PRIVILEGES ON dispatcher_control.* TO 'dispatcher_user'@'localhost';
FLUSH PRIVILEGES;

-- Verificar se foi criado
SHOW DATABASES;

-- Sair do MySQL
EXIT;
```

**✅ Resultado esperado:**
- Banco `dispatcher_control` criado
- Usuário `dispatcher_user` criado
- Permissões concedidas

**Testar conexão:**
```bash
mysql -u dispatcher_user -pDisp@tch3R9182 dispatcher_control -e "SELECT 1;"
```

---

### PASSO 5: Fazer Backup da Aplicação Antiga (Se Existir)

**Antes de fazer deploy, faça backup:**

```bash
# Verificar se existe aplicação antiga
ls -la /var/www/ | grep -E "dispatcher|projeto-xambra"

# Se existir, fazer backup
if [ -d "/var/www/dispatcher-control" ]; then
    BACKUP_DIR="/root/backups"
    mkdir -p "$BACKUP_DIR"
    tar -czf "$BACKUP_DIR/app_backup_$(date +%Y%m%d_%H%M%S).tar.gz" -C /var/www dispatcher-control
    echo "✓ Backup criado em $BACKUP_DIR"
fi
```

---

### PASSO 6: Fazer Upload da Aplicação

**Opção A: Via Git (Recomendado se já está no servidor)**

```bash
# Ir para /var/www
cd /var/www

# Se já existe, fazer backup primeiro (ver passo 5)
# Se não existe, clonar ou atualizar
if [ -d "dispatcher-control" ]; then
    cd dispatcher-control
    git pull origin main
else
    git clone https://seu-repositorio.git dispatcher-control
    cd dispatcher-control
fi
```

**Opção B: Via SCP (Upload de arquivo compactado)**

**Na sua máquina (Windows PowerShell):**

```powershell
# Criar arquivo compactado (excluindo node_modules, vendor, .git, etc.)
tar -czf dispatcher-control.tar.gz --exclude='node_modules' --exclude='vendor' --exclude='.git' --exclude='.env' .

# Fazer upload
scp dispatcher-control.tar.gz root@vmi2795941:/tmp/
```

**No servidor:**

```bash
# Extrair aplicação
cd /var/www
tar -xzf /tmp/dispatcher-control.tar.gz -C dispatcher-control 2>/dev/null || \
mkdir -p dispatcher-control && tar -xzf /tmp/dispatcher-control.tar.gz -C dispatcher-control

# Entrar no diretório
cd dispatcher-control

# Limpar arquivo temporário
rm /tmp/dispatcher-control.tar.gz
```

**Opção C: Via WinSCP (Interface Gráfica - Windows)**

1. Abra WinSCP
2. Conecte: `root@vmi2795941`
3. Navegue até `/var/www/`
4. Faça upload dos arquivos ou arraste o diretório do projeto

---

### PASSO 7: Configurar .env

**Criar arquivo `.env` de produção:**

```bash
cd /var/www/dispatcher-control

# Copiar .env.example ou criar novo
if [ -f ".env.example" ]; then
    cp .env.example .env
else
    touch .env
fi

# Editar .env
nano .env
```

**Configurar estas variáveis no `.env`:**

```env
APP_NAME="Dispatcher Control"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://app.abbrtransportandshipping.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dispatcher_control
DB_USERNAME=dispatcher_user
DB_PASSWORD=Disp@tch3R9182

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

**Salvar e sair:** `Ctrl+O` (salvar), `Enter`, `Ctrl+X` (sair)

**Gerar APP_KEY:**

```bash
cd /var/www/dispatcher-control
php artisan key:generate
```

**✅ Resultado esperado:**
- Arquivo `.env` criado e configurado
- `APP_KEY` gerado (aparece no arquivo como `base64:...`)

---

### PASSO 8: Instalar Dependências PHP

```bash
cd /var/www/dispatcher-control

# Instalar dependências (produção)
composer install --no-dev --optimize-autoloader --no-interaction

# Se não tiver Composer instalado globalmente:
# curl -sS https://getcomposer.org/installer | php
# php composer.phar install --no-dev --optimize-autoloader
```

**✅ Resultado esperado:**
- Diretório `vendor/` criado com dependências
- Autoload otimizado

---

### PASSO 9: Compilar Assets (Se Necessário)

**Se você fez upload via SCP sem os assets compilados:**

```bash
cd /var/www/dispatcher-control

# Instalar dependências Node (se não estiver instalado)
# curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
# apt-get install -y nodejs

# Instalar dependências
npm install

# Compilar assets para produção
npm run build
```

**✅ Resultado esperado:**
- Diretório `public/build/` com assets compilados

---

### PASSO 10: Configurar Permissões

**⚠️ MUITO IMPORTANTE - Execute estes comandos:**

```bash
cd /var/www/dispatcher-control

# Definir dono correto (www-data é o usuário do Apache)
chown -R www-data:www-data /var/www/dispatcher-control

# Dar permissões básicas
chmod -R 755 /var/www/dispatcher-control

# Dar permissões de escrita para storage e cache
chmod -R 775 storage bootstrap/cache

# Verificar permissões
ls -ld storage
ls -ld bootstrap/cache
```

**✅ Resultado esperado:**
- `storage`: `drwxrwxr-x` (775) | Dono: `www-data:www-data`
- `bootstrap/cache`: `drwxrwxr-x` (775) | Dono: `www-data:www-data`

---

### PASSO 11: Rodar Migrations

```bash
cd /var/www/dispatcher-control

# Rodar migrations (--force é necessário em produção)
php artisan migrate --force
```

**✅ Resultado esperado:**
- Mensagem: "Migrating: ..." para cada migration
- Mensagem final: "Migration completed successfully"
- Tabelas criadas no banco `dispatcher_control`

**Verificar se tabelas foram criadas:**
```bash
mysql -u dispatcher_user -pDisp@tch3R9182 dispatcher_control -e "SHOW TABLES;" | wc -l
# Deve mostrar um número > 0 (ex: 32)
```

---

### PASSO 12: Rodar Seeders (Se Necessário)

```bash
cd /var/www/dispatcher-control

# Rodar seeders específicos
php artisan db:seed --class=PlansSeeder --force

# Ou rodar todos os seeders
php artisan db:seed --force
```

**✅ Resultado esperado:**
- Dados iniciais criados no banco (planos, roles, etc.)

---

### PASSO 13: Otimizar Laravel (Cache)

```bash
cd /var/www/dispatcher-control

# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Limpar cache de aplicação (opcional)
php artisan cache:clear
```

**✅ Resultado esperado:**
- Cache criado em `bootstrap/cache/`
- Aplicação mais rápida

---

### PASSO 14: Configurar Apache

**Criar configuração do Apache:**

```bash
# Criar arquivo de configuração
nano /etc/apache2/sites-available/dispatcher-control.conf
```

**Cole este conteúdo:**

```apache
<VirtualHost *:80>
    ServerName app.abbrtransportandshipping.com
    ServerAlias www.app.abbrtransportandshipping.com
    
    # Redirecionar HTTP para HTTPS (descomente após configurar SSL)
    # RewriteEngine On
    # RewriteCond %{HTTPS} off
    # RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
    
    DocumentRoot /var/www/dispatcher-control/public

    <Directory /var/www/dispatcher-control/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dispatcher-control-error.log
    CustomLog ${APACHE_LOG_DIR}/dispatcher-control-access.log combined

    # PHP Configuration
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
</VirtualHost>

<VirtualHost *:443>
    ServerName app.abbrtransportandshipping.com
    ServerAlias www.app.abbrtransportandshipping.com
    
    DocumentRoot /var/www/dispatcher-control/public

    <Directory /var/www/dispatcher-control/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dispatcher-control-ssl-error.log
    CustomLog ${APACHE_LOG_DIR}/dispatcher-control-ssl-access.log combined

    # SSL Configuration (configurar depois com Certbot)
    # SSLEngine on
    # SSLCertificateFile /etc/letsencrypt/live/app.abbrtransportandshipping.com/fullchain.pem
    # SSLCertificateKeyFile /etc/letsencrypt/live/app.abbrtransportandshipping.com/privkey.pem
    # Include /etc/letsencrypt/options-ssl-apache.conf

    # PHP Configuration
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
</VirtualHost>
```

**Salvar e sair:** `Ctrl+O`, `Enter`, `Ctrl+X`

**Ativar site e módulos:**

```bash
# Ativar site
a2ensite dispatcher-control.conf

# Ativar mod_rewrite (necessário para Laravel)
a2enmod rewrite

# Ativar SSL (se for usar HTTPS)
a2enmod ssl

# Testar configuração
apache2ctl configtest
```

**✅ Resultado esperado:**
- Mensagem: "Syntax OK"
- Site ativado

**Reiniciar Apache:**

```bash
# Reiniciar Apache
systemctl reload apache2

# Ou se reload não funcionar:
systemctl restart apache2
```

**✅ Resultado esperado:**
- Apache reiniciado sem erros
- Site acessível

---

### PASSO 15: Testar Aplicação

**Verificar se está acessível:**

```bash
# Testar localmente no servidor
curl http://localhost

# Ou testar com o domínio
curl http://app.abbrtransportandshipping.com
```

**Verificar logs em caso de erro:**

```bash
# Logs do Laravel
tail -f /var/www/dispatcher-control/storage/logs/laravel.log

# Logs do Apache
tail -f /var/log/apache2/dispatcher-control-error.log
```

**Acessar no navegador:**
- Abra: https://app.abbrtransportandshipping.com
- Teste login/cadastro
- Teste criação de registros
- Verifique se uploads funcionam

---

### PASSO 16: Configurar SSL/HTTPS (Opcional mas Recomendado)

```bash
# Instalar Certbot
apt-get update
apt-get install certbot python3-certbot-apache -y

# Gerar certificado SSL
certbot --apache -d app.abbrtransportandshipping.com -d www.app.abbrtransportandshipping.com

# Testar renovação automática
certbot renew --dry-run
```

**Após configurar SSL, edite a configuração do Apache para habilitar HTTPS:**

```bash
nano /etc/apache2/sites-available/dispatcher-control.conf
```

**Descomente as linhas de SSL:**

```apache
SSLEngine on
SSLCertificateFile /etc/letsencrypt/live/app.abbrtransportandshipping.com/fullchain.pem
SSLCertificateKeyFile /etc/letsencrypt/live/app.abbrtransportandshipping.com/privkey.pem
Include /etc/letsencrypt/options-ssl-apache.conf
```

**E descomente o redirecionamento HTTP → HTTPS:**

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
```

**Reiniciar Apache:**

```bash
apache2ctl configtest
systemctl reload apache2
```

---

### PASSO 17: Configurar Crontab (Laravel Scheduler)

**Para tarefas agendadas do Laravel:**

```bash
# Editar crontab
crontab -e

# Adicionar esta linha (ajuste o caminho se necessário):
* * * * * cd /var/www/dispatcher-control && php artisan schedule:run >> /dev/null 2>&1
```

**Salvar e sair:** `Ctrl+O`, `Enter`, `Ctrl+X`

---

## ✅ Checklist Final

Antes de considerar o deploy concluído, verifique:

- [ ] Aplicação está em `/var/www/dispatcher-control`
- [ ] Arquivo `.env` configurado corretamente
- [ ] `APP_KEY` gerado
- [ ] Dependências PHP instaladas (`vendor/` existe)
- [ ] Assets compilados (`public/build/` existe)
- [ ] Permissões configuradas (storage e cache com 775)
- [ ] Dono correto (`www-data:www-data`)
- [ ] Migrations rodadas (tabelas criadas)
- [ ] Seeders rodados (dados iniciais criados)
- [ ] Cache otimizado (config, route, view)
- [ ] Apache configurado e ativo
- [ ] Site acessível no navegador
- [ ] Login/cadastro funcionando
- [ ] Uploads funcionando (se aplicável)
- [ ] SSL configurado (opcional mas recomendado)
- [ ] Crontab configurado (se necessário)

---

## 🔧 Comandos Úteis Pós-Deploy

### Ver Logs

```bash
# Logs do Laravel (em tempo real)
tail -f /var/www/dispatcher-control/storage/logs/laravel.log

# Logs do Apache
tail -f /var/log/apache2/dispatcher-control-error.log

# Logs de acesso
tail -f /var/log/apache2/dispatcher-control-access.log
```

### Limpar Cache

```bash
cd /var/www/dispatcher-control

# Limpar todos os caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar caches otimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Atualizar Aplicação (Futuro)

```bash
cd /var/www/dispatcher-control

# Se usar Git
git pull origin main

# Atualizar dependências
composer install --no-dev --optimize-autoloader

# Rodar novas migrations
php artisan migrate --force

# Recriar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar Apache (se necessário)
systemctl reload apache2
```

---

## 🐛 Troubleshooting Comum

### Erro 500 (Internal Server Error)

```bash
# Verificar permissões
ls -ld /var/www/dispatcher-control/storage
ls -ld /var/www/dispatcher-control/bootstrap/cache

# Corrigir permissões
chmod -R 775 /var/www/dispatcher-control/storage
chmod -R 775 /var/www/dispatcher-control/bootstrap/cache
chown -R www-data:www-data /var/www/dispatcher-control/storage
chown -R www-data:www-data /var/www/dispatcher-control/bootstrap/cache

# Verificar logs
tail -f /var/www/dispatcher-control/storage/logs/laravel.log
```

### Erro de Conexão com Banco

```bash
# Testar conexão
mysql -u dispatcher_user -pDisp@tch3R9182 dispatcher_control -e "SELECT 1;"

# Verificar credenciais no .env
cat /var/www/dispatcher-control/.env | grep DB_

# Verificar se MySQL está rodando
systemctl status mysql
```

### Página em Branco

```bash
# Ativar debug temporariamente (CUIDADO em produção)
# Editar .env e mudar APP_DEBUG=true temporariamente

# Verificar permissões do storage
ls -la /var/www/dispatcher-control/storage/

# Verificar logs do PHP
tail -f /var/log/apache2/error.log
```

### Apache não inicia

```bash
# Testar configuração
apache2ctl configtest

# Verificar erros de sintaxe
tail -f /var/log/apache2/error.log

# Verificar se porta 80/443 está em uso
netstat -tulpn | grep -E ":80|:443"
```

---

## 📚 Documentação Relacionada

- `docs/deploy/CREDENCIAIS_DEPLOY.md` - Credenciais e informações do servidor
- `docs/deploy/GUIA_DEPLOY_EXECUTADO.md` - O que foi executado no primeiro deploy
- `scripts/README_DEPLOY.md` - Scripts de deploy disponíveis

---

## 📝 Notas Importantes

1. **Nunca commite o arquivo `.env`** - Ele contém credenciais sensíveis
2. **Sempre faça backup** antes de fazer mudanças grandes
3. **Teste localmente** antes de fazer deploy em produção
4. **Verifique logs** quando algo não funcionar
5. **Mantenha dependências atualizadas** para segurança

---

**Guia criado em:** Novembro 2024  
**Última atualização:** Novembro 2024  
**Para dúvidas, consulte a documentação ou o time de desenvolvimento.**

