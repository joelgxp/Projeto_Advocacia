# ⚡ Iniciar Servidor Online - Guia Rápido

## 🎯 Importante

**Em servidores online, você NÃO precisa "iniciar" o servidor - ele já está rodando!**

O que você precisa fazer é **configurar o servidor web** para servir sua aplicação.

---

## 📋 Servidor Compartilhado (cPanel, Hostinger, etc.)

### Passo 1: Enviar Arquivos

Envie todos os arquivos do projeto para o servidor via FTP.

### Passo 2: Configurar DocumentRoot

**Opção A - Via .htaccess na raiz:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Opção B - Via Painel:**
- No cPanel: Configurações do Domínio
- Configure DocumentRoot para apontar para `public/`

### Passo 3: Configurar .env

1. Copie `env.example` → `.env`
2. Configure: `APP_URL`, `DB_*`, etc.
3. Gere APP_KEY: `php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"`

### Passo 4: Acessar

Acesse: `https://seudominio.com.br`

**Pronto!** ✅

---

## 📋 VPS/Dedicado

### Apache

```bash
# 1. Criar VirtualHost
sudo nano /etc/apache2/sites-available/advocacia.conf
```

```apache
<VirtualHost *:80>
    ServerName seudominio.com.br
    DocumentRoot /var/www/advocacia/public
    
    <Directory /var/www/advocacia/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

```bash
# 2. Habilitar
sudo a2ensite advocacia.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Nginx

```bash
# 1. Criar configuração
sudo nano /etc/nginx/sites-available/advocacia
```

```nginx
server {
    listen 80;
    server_name seudominio.com.br;
    root /var/www/advocacia/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
# 2. Habilitar
sudo ln -s /etc/nginx/sites-available/advocacia /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## ✅ Verificar se Está Funcionando

```bash
# 1. Verificar serviços
sudo systemctl status apache2  # ou nginx

# 2. Testar no navegador
# https://seudominio.com.br

# 3. Verificar logs
tail -f storage/logs/laravel.log
```

---

## 🐛 Problemas Comuns

### Erro 500
```bash
chmod -R 775 storage bootstrap/cache
rm -f bootstrap/cache/*.php
```

### Erro 403
```bash
sudo chown -R www-data:www-data /var/www/advocacia
sudo chmod -R 755 /var/www/advocacia
```

### Site não carrega
```bash
# Verificar se serviço está rodando
sudo systemctl status apache2  # ou nginx

# Reiniciar se necessário
sudo systemctl restart apache2  # ou nginx
```

---

## 📚 Documentação Completa

Veja: `docs/deploy/INICIAR_SERVIDOR_ONLINE.md`

