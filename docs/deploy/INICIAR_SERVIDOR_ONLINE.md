# 🚀 Como Iniciar/Configurar o Servidor Online

Em servidores online, você não precisa "iniciar" o servidor manualmente - ele já está rodando. O que você precisa fazer é **configurar o servidor web** para servir sua aplicação Laravel.

---

## 📋 Tipos de Servidor

### 1. Servidor Compartilhado (cPanel, Hostinger, etc.)
- ✅ Servidor web já está rodando
- ✅ Apenas configure o DocumentRoot
- ✅ Não precisa iniciar nada

### 2. VPS/Dedicado
- ⚠️ Pode precisar configurar Apache/Nginx
- ⚠️ Pode precisar iniciar serviços

---

## 🔧 Configuração em Servidor Compartilhado

### Passo 1: Estrutura de Pastas

O servidor compartilhado geralmente tem esta estrutura:
```
/home/usuario/
├── public_html/          ← DocumentRoot (aqui vão os arquivos públicos)
└── projeto/             ← Pasta do projeto (opcional)
```

### Passo 2: Opção A - Colocar Tudo em public_html

**Se você tem acesso apenas à pasta `public_html/`:**

1. **Envie os arquivos da pasta `public/` para `public_html/`**
   ```
   public_html/
   ├── index.php
   ├── css/
   ├── js/
   ├── fonts/
   └── ...
   ```

2. **Envie o resto do projeto para uma pasta acima (se possível)**
   ```
   /home/usuario/
   ├── public_html/       ← Arquivos de public/
   ├── app/
   ├── config/
   ├── database/
   ├── resources/
   ├── routes/
   ├── vendor/
   ├── .env
   └── ...
   ```

3. **Ajuste o `public/index.php`** para apontar para a pasta correta:
   ```php
   // Se o projeto está em /home/usuario/ e public_html aponta para public/
   // O index.php deve ter:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

### Passo 3: Opção B - Configurar DocumentRoot

**Se você tem acesso ao painel (cPanel) ou pode configurar VirtualHost:**

1. **Configure o DocumentRoot para apontar para `public/`**
   - No cPanel: Configurações do Domínio → DocumentRoot
   - Ou via .htaccess na raiz

2. **Crie .htaccess na raiz do projeto:**
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```

---

## 🔧 Configuração em VPS/Dedicado

### Apache

#### 1. Criar VirtualHost

Crie o arquivo: `/etc/apache2/sites-available/advocacia.conf`

```apache
<VirtualHost *:80>
    ServerName seudominio.com.br
    ServerAlias www.seudominio.com.br
    
    DocumentRoot /var/www/advocacia/public
    
    <Directory /var/www/advocacia/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/advocacia_error.log
    CustomLog ${APACHE_LOG_DIR}/advocacia_access.log combined
</VirtualHost>
```

#### 2. Habilitar Site

```bash
# Habilitar site
sudo a2ensite advocacia.conf

# Habilitar mod_rewrite (se não estiver)
sudo a2enmod rewrite

# Reiniciar Apache
sudo systemctl restart apache2
```

#### 3. Verificar Status

```bash
# Verificar se Apache está rodando
sudo systemctl status apache2

# Verificar se site está habilitado
sudo apache2ctl -S
```

### Nginx

#### 1. Criar Configuração

Crie o arquivo: `/etc/nginx/sites-available/advocacia`

```nginx
server {
    listen 80;
    server_name seudominio.com.br www.seudominio.com.br;
    root /var/www/advocacia/public;
    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 2. Habilitar Site

```bash
# Criar link simbólico
sudo ln -s /etc/nginx/sites-available/advocacia /etc/nginx/sites-enabled/

# Testar configuração
sudo nginx -t

# Reiniciar Nginx
sudo systemctl restart nginx
```

#### 3. Verificar Status

```bash
# Verificar se Nginx está rodando
sudo systemctl status nginx

# Verificar configuração
sudo nginx -t
```

---

## 🔍 Verificar se Está Funcionando

### 1. Testar no Navegador

Acesse: `http://seudominio.com.br` ou `https://seudominio.com.br`

**Deve mostrar:**
- ✅ Página de login
- ✅ Sem erro 500
- ✅ CSS/JS carregam

### 2. Verificar Logs

**Apache:**
```bash
tail -f /var/log/apache2/advocacia_error.log
```

**Nginx:**
```bash
tail -f /var/log/nginx/error.log
```

**Laravel:**
```bash
tail -f storage/logs/laravel.log
```

### 3. Testar PHP

Crie um arquivo `public/test.php`:
```php
<?php
phpinfo();
```

Acesse: `https://seudominio.com.br/test.php`

**Depois delete o arquivo por segurança!**

---

## 🛠️ Comandos Úteis

### Verificar Serviços

```bash
# Apache
sudo systemctl status apache2
sudo systemctl start apache2
sudo systemctl stop apache2
sudo systemctl restart apache2

# Nginx
sudo systemctl status nginx
sudo systemctl start nginx
sudo systemctl stop nginx
sudo systemctl restart nginx

# PHP-FPM (se usar Nginx)
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

### Verificar Portas

```bash
# Verificar se Apache/Nginx está escutando
sudo netstat -tulpn | grep :80
sudo netstat -tulpn | grep :443
```

### Verificar Permissões

```bash
# Verificar permissões
ls -la /var/www/advocacia/
ls -la /var/www/advocacia/storage/
ls -la /var/www/advocacia/bootstrap/cache/

# Corrigir permissões
sudo chown -R www-data:www-data /var/www/advocacia
sudo chmod -R 775 /var/www/advocacia/storage
sudo chmod -R 775 /var/www/advocacia/bootstrap/cache
```

---

## 🔐 Configurar HTTPS (SSL)

### Let's Encrypt (Gratuito)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache  # Para Apache
sudo apt install certbot python3-certbot-nginx   # Para Nginx

# Gerar certificado
sudo certbot --apache -d seudominio.com.br -d www.seudominio.com.br
# ou
sudo certbot --nginx -d seudominio.com.br -d www.seudominio.com.br

# Renovação automática (já configurado)
sudo certbot renew --dry-run
```

### No cPanel

1. Acesse: SSL/TLS Status
2. Selecione o domínio
3. Clique em "Run AutoSSL"
4. Aguarde alguns minutos

---

## 🐛 Troubleshooting

### Erro 403 Forbidden

**Causa**: Permissões incorretas

**Solução**:
```bash
sudo chown -R www-data:www-data /var/www/advocacia
sudo chmod -R 755 /var/www/advocacia
sudo chmod -R 775 /var/www/advocacia/storage
sudo chmod -R 775 /var/www/advocacia/bootstrap/cache
```

### Erro 500 Internal Server Error

**Verificar**:
```bash
# 1. Logs do servidor
tail -f /var/log/apache2/error.log
# ou
tail -f /var/log/nginx/error.log

# 2. Logs do Laravel
tail -f storage/logs/laravel.log

# 3. Permissões
ls -la storage/
ls -la bootstrap/cache/

# 4. .env
cat .env | grep APP_KEY
```

### Site não carrega

**Verificar**:
```bash
# 1. Serviço está rodando?
sudo systemctl status apache2
# ou
sudo systemctl status nginx

# 2. DocumentRoot está correto?
# Verifique no VirtualHost

# 3. PHP está funcionando?
php -v

# 4. Porta 80/443 está aberta?
sudo ufw status
```

### Assets não carregam (404)

**Causa**: DocumentRoot não aponta para `public/`

**Solução**:
- Verifique se DocumentRoot = `/caminho/para/projeto/public`
- Ou use .htaccess na raiz para redirecionar

---

## 📝 Checklist de Configuração

### Servidor Compartilhado
- [ ] Arquivos enviados para servidor
- [ ] DocumentRoot configurado para `public/`
- [ ] `.env` configurado
- [ ] Permissões corretas (775 para storage, bootstrap/cache)
- [ ] Banco de dados importado
- [ ] Site acessível no navegador

### VPS/Dedicado
- [ ] Apache ou Nginx instalado e rodando
- [ ] VirtualHost configurado
- [ ] DocumentRoot aponta para `public/`
- [ ] PHP-FPM configurado (se Nginx)
- [ ] mod_rewrite habilitado (se Apache)
- [ ] Permissões corretas
- [ ] Firewall configurado (portas 80, 443)
- [ ] SSL/HTTPS configurado
- [ ] Site acessível

---

## 🎯 Resumo Rápido

### Servidor Compartilhado:
1. Envie arquivos via FTP
2. Configure DocumentRoot para `public/` (via .htaccess ou painel)
3. Configure `.env`
4. Acesse o site

### VPS/Dedicado:
1. Configure VirtualHost (Apache) ou server block (Nginx)
2. Aponte DocumentRoot para `public/`
3. Reinicie o servidor web
4. Configure permissões
5. Acesse o site

**O servidor web já está rodando - você só precisa configurá-lo para servir sua aplicação!** 🚀

