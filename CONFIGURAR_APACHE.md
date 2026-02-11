# 🔧 Configurar Apache para Sistema de Advocacia

## ⚠️ Problema: Erro 404 após login

Se você está usando Apache (porta 80) e recebe erro 404 após fazer login, siga estas instruções.

## 📋 Passo 1: Verificar mod_rewrite

O Apache precisa do módulo `mod_rewrite` habilitado.

### XAMPP (Windows)

1. Abra o arquivo: `C:\xampp\apache\conf\httpd.conf`
2. Procure por: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remova o `#` para descomentar:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Procure por `<Directory "C:/xampp/htdocs">` e altere para:
   ```apache
   <Directory "C:/xampp/htdocs">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```
5. Reinicie o Apache no XAMPP Control Panel

### WAMP (Windows)

1. Clique no ícone do WAMP na bandeja
2. Vá em: Apache → Apache Modules → **rewrite_module** (deve estar marcado)
3. Se não estiver marcado, clique para habilitar
4. Reinicie todos os serviços

## 📋 Passo 2: Configurar DocumentRoot

O DocumentRoot do Apache deve apontar para a **raiz do projeto** (não para `public/`).

### XAMPP

1. Abra: `C:\xampp\apache\conf\httpd.conf`
2. Procure por `DocumentRoot` e altere para:
   ```apache
   DocumentRoot "C:/Users/joelv/OneDrive/Documentos/Repositorios/Projeto_Advocacia"
   <Directory "C:/Users/joelv/OneDrive/Documentos/Repositorios/Projeto_Advocacia">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```
3. Reinicie o Apache

### WAMP

1. Clique no ícone do WAMP
2. Vá em: Tools → Edit httpd.conf
3. Procure por `DocumentRoot` e altere para o caminho do seu projeto
4. Reinicie o Apache

## 📋 Passo 3: Verificar .htaccess

O arquivo `.htaccess` na raiz do projeto deve estar correto. Verifique se existe e contém:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Servir arquivos estáticos diretamente
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^(.*)$ - [L]
    
    # Redirecionar para index.php se não for arquivo ou diretório
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

## 📋 Passo 4: Testar

1. Acesse: `http://localhost/` (sem porta)
2. Deve carregar a página de login
3. Faça login
4. Deve redirecionar para `http://localhost/admin` sem erro 404

## 🔍 Verificar se está funcionando

Execute no terminal (na pasta do projeto):

```powershell
# Verificar se mod_rewrite está habilitado
php -r "echo 'Verifique manualmente no httpd.conf se mod_rewrite está habilitado';"

# Testar se .htaccess existe
Test-Path .htaccess
```

## ⚠️ Alternativa: Usar Servidor PHP Built-in

Se não conseguir configurar o Apache, use o servidor PHP built-in:

```powershell
php -S localhost:8000 router.php
```

E acesse: `http://localhost:8000`

## 🆘 Problemas Comuns

### Erro 403 Forbidden
- Verifique `AllowOverride All` no httpd.conf
- Verifique permissões da pasta do projeto

### Erro 404 Not Found
- Verifique se `mod_rewrite` está habilitado
- Verifique se `.htaccess` existe na raiz
- Verifique se DocumentRoot aponta para a raiz do projeto

### Assets (CSS/JS) não carregam
- Verifique se a pasta `assets/` existe na raiz
- Verifique permissões: `chmod -R 755 assets`

