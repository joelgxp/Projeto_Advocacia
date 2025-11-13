# 🚀 Guia de Deploy - Sistema de Advocacia

## 📋 Pré-requisitos

### No seu computador (local):
- ✅ PHP 8.2+ (para testar)
- ✅ Composer (para instalar dependências)
- ✅ Acesso FTP/SFTP ao servidor ou Git configurado

### No servidor online:
- ✅ PHP 8.2+ (verificar com seu provedor)
- ✅ Composer (alguns servidores têm, outros não)
- ✅ MySQL/MariaDB
- ✅ Acesso SSH (recomendado) ou FTP/SFTP
- ✅ Pasta `public_html` ou `www` ou `htdocs` (depende do servidor)

---

## 🔧 Método 1: Deploy via FTP/SFTP (Servidor Compartilhado)

### Passo 1: Preparar o Projeto Localmente

```powershell
# 1. Limpar cache
Remove-Item bootstrap/cache/*.php -Force -ErrorAction SilentlyContinue

# 2. Instalar dependências Composer (produção)
composer install --no-dev --optimize-autoloader

# 3. Verificar se todos os arquivos vendor estão presentes
# (Bootstrap, Font Awesome, jQuery, Inter font já estão em public/)
```

### Passo 2: Configurar .env para Produção

Crie um arquivo `.env` com as configurações do servidor:

```env
APP_NAME="Sistema de Advocacia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

APP_KEY=base64:SUA_CHAVE_AQUI

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_banco
DB_PASSWORD=senha_banco

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

# API CNJ (se tiver)
API_CNJ_KEY=sua_chave_aqui
```

**⚠️ IMPORTANTE**: Gere a APP_KEY (SEM ARTISAN):
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```
Copie o resultado e cole no `.env` na linha `APP_KEY=`

### Passo 3: Enviar Arquivos para o Servidor

**Envie TODOS os arquivos, EXCETO:**
- ❌ `node_modules/` (não é necessário)
- ❌ `.env` (configure diretamente no servidor)
- ❌ `.git/` (opcional)
- ❌ `storage/logs/*.log` (opcional)

**✅ Certifique-se de enviar:**
- ✅ `public/` (com todos os arquivos vendor: css/vendor, js/vendor, fonts/)
- ✅ `vendor/` (dependências Composer)
- ✅ `app/`, `config/`, `database/`, `resources/`, `routes/`
- ✅ `composer.json`, `composer.lock`
- ✅ `.htaccess` (se houver)

### Passo 4: Configurar no Servidor

#### 4.1. Estrutura de Pastas no Servidor

A estrutura depende do tipo de servidor:

**Servidor Compartilhado (cPanel):**
```
public_html/
├── index.php          (pasta public/)
├── css/               (pasta public/css/)
├── js/                (pasta public/js/)
├── fonts/             (pasta public/fonts/)
└── ..                 (outros arquivos na raiz)
```

**Servidor VPS/Dedicado:**
```
/var/www/html/
├── public/            (DocumentRoot aponta aqui)
├── app/
├── config/
└── ...
```

#### 4.2. Configurar DocumentRoot

O `DocumentRoot` do Apache/Nginx deve apontar para a pasta `public/`:

**Apache (.htaccess na raiz do projeto):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Ou configure o VirtualHost:**
```apache
<VirtualHost *:80>
    ServerName seudominio.com.br
    DocumentRoot /caminho/para/projeto/public
    
    <Directory /caminho/para/projeto/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Passo 5: Configurar no Servidor (via SSH ou Painel)

#### Via SSH (se tiver acesso):

```bash
# 1. Navegar até a pasta do projeto
cd /caminho/para/projeto

# 2. Copiar .env.example para .env
cp .env.example .env

# 3. Editar .env (use nano ou vi)
nano .env
# Configure: APP_URL, DB_*, etc.

# 4. Gerar APP_KEY (SEM ARTISAN)
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado no .env na linha APP_KEY=

# 5. Instalar dependências (se não enviou vendor/)
composer install --no-dev --optimize-autoloader

# 6. Importar banco de dados (ao invés de migrate)
# Via phpMyAdmin: Importe database/sql/advocacia.sql
# Ou via terminal:
mysql -u usuario -p nome_banco < database/sql/advocacia.sql

# 7. Configurar permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Limpar cache manualmente (SEM ARTISAN)
rm -f bootstrap/cache/*.php
```

#### Via Painel (cPanel/File Manager):

1. **Criar .env**: Copie `env.example` e renomeie para `.env`
2. **Editar .env**: Use o editor de arquivos do painel
   - Configure: `APP_URL`, `DB_*`, etc.
   - Gere APP_KEY: Use o Terminal do cPanel: `php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"`
   - Cole o resultado no `.env` na linha `APP_KEY=`
3. **Permissões**: Via File Manager, defina permissões 775 para `storage/` e `bootstrap/cache/`
4. **Importar Banco**: Via phpMyAdmin, importe `database/sql/advocacia.sql`
5. **Limpar Cache**: Via File Manager, delete arquivos em `bootstrap/cache/*.php`

### Passo 6: Importar Banco de Dados

**Via phpMyAdmin:**
1. Acesse phpMyAdmin no painel
2. Selecione o banco de dados
3. Vá em "Importar"
4. Selecione `database/sql/advocacia.sql` (se existir)
5. Clique em "Executar"

**Via Terminal:**
```bash
mysql -u usuario -p nome_banco < database/sql/advocacia.sql
```

---

## 🔧 Método 2: Deploy via Git (Recomendado)

### Passo 1: Configurar Repositório

```bash
# No servidor, clone o repositório
git clone https://github.com/seu-usuario/Projeto_Advocacia.git
cd Projeto_Advocacia
```

### Passo 2: Configurar no Servidor

```bash
# Instalar dependências
composer install --no-dev --optimize-autoloader

# Configurar .env
cp env.example .env
nano .env  # Configure as variáveis

# Gerar APP_KEY (SEM ARTISAN)
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado no .env na linha APP_KEY=

# Importar banco (ao invés de migrate)
mysql -u usuario -p nome_banco < database/sql/advocacia.sql

# Permissões
chmod -R 775 storage bootstrap/cache

# Limpar cache (SEM ARTISAN)
rm -f bootstrap/cache/*.php
```

### Passo 3: Atualizações Futuras

```bash
# No servidor
git pull origin main
composer install --no-dev --optimize-autoloader

# Se houver novas migrations, importe o SQL atualizado
# mysql -u usuario -p nome_banco < database/sql/advocacia.sql

# Limpar cache (SEM ARTISAN)
rm -f bootstrap/cache/*.php
```

---

## 🔍 Verificação Pós-Deploy

### 1. Verificar se o site está acessível
- Acesse: `https://seudominio.com.br`
- Deve carregar a página de login

### 2. Verificar Assets
- Abra o DevTools (F12) → Network
- Verifique se CSS/JS carregam de:
  - `/css/vendor/bootstrap.min.css`
  - `/css/vendor/fontawesome.min.css`
  - `/js/vendor/jquery.min.js`
  - `/js/vendor/bootstrap.bundle.min.js`

### 3. Verificar Logs
```bash
tail -f storage/logs/laravel.log
```

### 4. Testar Login
- Use as credenciais padrão
- Verifique se redireciona corretamente

---

## 🐛 Troubleshooting

### Erro 500 - Internal Server Error

**Causa**: Permissões ou .env incorreto

**Solução**:
```bash
# Verificar permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Verificar .env
cat .env | grep APP_KEY
# Se estiver vazio, gere: php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado no .env na linha APP_KEY=

# Limpar cache
rm -f bootstrap/cache/*.php
```

### Assets não carregam (404)

**Causa**: DocumentRoot não aponta para `public/`

**Solução**:
- Verifique se o DocumentRoot aponta para `public/`
- Ou use .htaccess na raiz para redirecionar

### Erro: "No application encryption key"

**Solução**:
```bash
# Gerar APP_KEY (SEM ARTISAN)
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado no .env na linha APP_KEY=
```

### Erro de Conexão com Banco

**Verifique no .env**:
- `DB_HOST` (geralmente `localhost` em servidores compartilhados)
- `DB_DATABASE` (nome do banco)
- `DB_USERNAME` e `DB_PASSWORD` (credenciais corretas)

### Limpar Cache (SEM ARTISAN)

**Como não usamos artisan**, limpe o cache manualmente:

```bash
# Limpar cache de configuração
rm -f bootstrap/cache/*.php

# Limpar cache de views (se houver)
rm -rf storage/framework/views/*
```

---

## 📝 Checklist de Deploy

### Antes do Deploy
- [ ] Testar localmente
- [ ] Verificar se `public/css/vendor/` e `public/js/vendor/` existem
- [ ] Verificar se `public/fonts/` existe
- [ ] Preparar `.env` com dados do servidor
- [ ] Gerar `APP_KEY`

### Durante o Deploy
- [ ] Enviar todos os arquivos (exceto node_modules, .env, .git)
- [ ] Configurar `.env` no servidor
- [ ] Configurar DocumentRoot para `public/`
- [ ] Configurar permissões (775 para storage, bootstrap/cache)

### Após o Deploy
- [ ] Verificar se site carrega
- [ ] Verificar se assets carregam (F12)
- [ ] Testar login
- [ ] Verificar logs
- [ ] Testar funcionalidades principais

---

## 🔐 Segurança em Produção

### 1. Configurar .env
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Alterar Senhas Padrão
- Altere todas as senhas dos usuários padrão
- Use senhas fortes

### 3. Configurar HTTPS
- Use certificado SSL
- Configure `APP_URL=https://seudominio.com.br`

### 4. Backup Regular
- Configure backup automático do banco
- Faça backup dos arquivos regularmente

---

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs: `storage/logs/laravel.log`
2. Verifique permissões de arquivos
3. Verifique configuração do .env
4. Verifique se DocumentRoot está correto

---

## 🎯 Resumo Rápido

```bash
# 1. Local: Preparar
composer install --no-dev --optimize-autoloader
Remove-Item bootstrap/cache/*.php -Force

# 2. Enviar arquivos para servidor (FTP/Git)

# 3. Servidor: Configurar
cp env.example .env
# Editar .env
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado no .env na linha APP_KEY=
mysql -u usuario -p nome_banco < database/sql/advocacia.sql
chmod -R 775 storage bootstrap/cache
rm -f bootstrap/cache/*.php
```

**Pronto!** Seu sistema está no ar! 🚀

