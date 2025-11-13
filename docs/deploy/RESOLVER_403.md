# 🔧 Como Resolver Erro 403 (Forbidden)

O erro 403 geralmente ocorre quando o servidor web não consegue acessar os arquivos ou quando há problemas de configuração.

## 🔍 Diagnóstico Rápido

Execute o script de diagnóstico:

```bash
php scripts/diagnosticar-403.php
```

## ✅ Soluções Comuns

### 1. DocumentRoot Não Está Apontando para `public/`

**Problema:** O servidor está tentando acessar a raiz do projeto ao invés de `public/`.

**Solução A - Criar .htaccess na raiz:**

Crie um arquivo `.htaccess` na **raiz do projeto** (não em `public/`):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Solução B - Configurar no cPanel:**

1. Acesse o cPanel
2. Vá em **Configurações do Domínio** ou **Subdomínios**
3. Configure o **DocumentRoot** para apontar para: `public_html/public` ou `adv.joelsouza.com.br/public`

**Solução C - Configurar VirtualHost (VPS):**

```apache
<VirtualHost *:80>
    ServerName adv.joelsouza.com.br
    DocumentRoot /home2/hotel631/adv.joelsouza.com.br/public
    
    <Directory /home2/hotel631/adv.joelsouza.com.br/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 2. Permissões Incorretas

**Problema:** Arquivos ou pastas não têm permissões de leitura.

**Solução:**

```bash
# Corrigir permissões da pasta public
chmod 755 public
chmod 644 public/index.php
chmod -R 755 public

# Corrigir permissões do .htaccess
chmod 644 public/.htaccess
```

### 3. .htaccess Bloqueando Acesso

**Problema:** Regras no `.htaccess` estão bloqueando o acesso.

**Verificar `public/.htaccess`:**

O arquivo deve conter apenas as regras do Laravel (já está correto no projeto).

**Verificar `.htaccess` na raiz:**

Se houver um `.htaccess` na raiz do projeto, verifique se não contém:
- `Deny from all`
- `Require all denied`

### 4. Servidor Compartilhado (cPanel)

**Estrutura correta:**

```
/home2/hotel631/
└── adv.joelsouza.com.br/
    ├── public/          ← DocumentRoot deve apontar aqui
    │   ├── index.php
    │   ├── .htaccess
    │   ├── css/
    │   └── js/
    ├── app/
    ├── config/
    ├── .env
    └── ...
```

**Configurar no cPanel:**

1. **Opção 1 - Via .htaccess na raiz:**
   - Crie `.htaccess` na raiz com: `RewriteRule ^(.*)$ public/$1 [L]`

2. **Opção 2 - Via Configurações:**
   - Acesse **Configurações do Domínio**
   - Altere o **DocumentRoot** para: `public_html/public`

### 5. Verificar Módulos do Apache

**Problema:** Módulo `mod_rewrite` não está habilitado.

**Solução (VPS):**

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🚀 Passos para Resolver

### Passo 1: Executar Diagnóstico

```bash
php scripts/diagnosticar-403.php
```

### Passo 2: Criar .htaccess na Raiz (se necessário)

```bash
cd /home2/hotel631/adv.joelsouza.com.br
nano .htaccess
```

Cole:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Passo 3: Corrigir Permissões

```bash
chmod 755 public
chmod 644 public/index.php
chmod 644 public/.htaccess
chmod -R 755 public
```

### Passo 4: Verificar Configuração

Execute o diagnóstico novamente:

```bash
php scripts/diagnosticar-403.php
```

### Passo 5: Testar no Navegador

Acesse: `https://adv.joelsouza.com.br`

## 📋 Checklist

- [ ] DocumentRoot aponta para `public/`
- [ ] `.htaccess` existe em `public/` com regras do Laravel
- [ ] `.htaccess` na raiz redireciona para `public/` (se necessário)
- [ ] Permissões: `public/` = 755, `public/index.php` = 644
- [ ] Módulo `mod_rewrite` habilitado (Apache)
- [ ] Nenhum `.htaccess` bloqueando acesso

## 🔗 Scripts Relacionados

- `scripts/diagnosticar-403.php` - Diagnóstico de erro 403
- `scripts/diagnosticar-erros.php` - Diagnóstico geral
- `docs/deploy/INICIAR_SERVIDOR_ONLINE.md` - Configuração do servidor

