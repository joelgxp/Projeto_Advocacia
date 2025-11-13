# 🚀 Deploy Rápido - Servidor Online

## ⚡ Passos Rápidos

### 1️⃣ Preparar Localmente

```powershell
# Limpar cache
Remove-Item bootstrap/cache/*.php -Force

# Instalar dependências produção
composer install --no-dev --optimize-autoloader
```

### 2️⃣ Enviar para Servidor

**Envie TUDO, exceto:**
- ❌ `node_modules/`
- ❌ `.env` (configure no servidor)
- ❌ `.git/`

**✅ IMPORTANTE: Envie:**
- ✅ `public/css/vendor/` (Bootstrap, Font Awesome CSS)
- ✅ `public/js/vendor/` (Bootstrap, jQuery JS)
- ✅ `public/fonts/` (Font Awesome e Inter fonts)
- ✅ `vendor/` (Composer)
- ✅ Todo o resto do projeto

### 3️⃣ Configurar no Servidor

#### Via SSH:
```bash
cd /caminho/para/projeto
cp .env.example .env
nano .env  # Configure: APP_URL, DB_*, etc.
php artisan key:generate
php artisan migrate --force
chmod -R 775 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
```

#### Via Painel (cPanel):
1. Copie `env.example` → `.env`
2. Edite `.env` com dados do servidor
3. Use Terminal do cPanel para executar comandos acima

### 4️⃣ Configurar DocumentRoot

**O DocumentRoot deve apontar para `public/`**

**Opção 1: .htaccess na raiz**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Opção 2: Configurar VirtualHost**
```apache
DocumentRoot /caminho/para/projeto/public
```

### 5️⃣ Importar Banco

**Via phpMyAdmin:**
- Importe `database/sql/advocacia.sql`

**Ou via Terminal:**
```bash
mysql -u usuario -p banco < database/sql/advocacia.sql
```

### 6️⃣ Verificar

- ✅ Site carrega?
- ✅ Assets carregam? (F12 → Network)
- ✅ Login funciona?

---

## 🔧 Configuração .env

```env
APP_NAME="Sistema de Advocacia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br
APP_KEY=base64:GERAR_COM_php_artisan_key:generate

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_banco
DB_USERNAME=usuario_banco
DB_PASSWORD=senha_banco
```

---

## ⚠️ Problemas Comuns

### Erro 500
```bash
chmod -R 775 storage bootstrap/cache
php artisan key:generate
```

### Assets 404
- Verifique se DocumentRoot aponta para `public/`
- Verifique se `public/css/vendor/` existe

### Erro de Banco
- Verifique credenciais no `.env`
- Teste conexão: `php artisan tinker` → `DB::connection()->getPdo();`

---

## 📚 Documentação Completa

Veja: `docs/deploy/DEPLOY_SERVIDOR_ONLINE.md`

