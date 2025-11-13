# 🔄 Reinstalar Tudo no Servidor - Guia Completo

Este guia mostra como deletar tudo no servidor e instalar novamente do zero.

## ⚠️ ATENÇÃO: Backup Primeiro!

Antes de deletar, faça backup:

1. **Backup do banco de dados** (via phpMyAdmin ou SSH)
2. **Backup do arquivo `.env`** (copie as configurações)
3. **Backup de arquivos importantes** (se houver uploads em `storage/app/public`)

---

## 🗑️ Passo 1: Deletar Tudo no Servidor

### Via SSH:

```bash
# Conectar ao servidor
ssh hotel631@hotelalphavilleguaxupe.com.br

# Ir para a pasta do projeto
cd ~/adv.joelsouza.com.br

# Listar o que tem (para conferir)
ls -la

# Deletar TUDO (cuidado!)
rm -rf *

# Deletar arquivos ocultos também
rm -rf .*

# Verificar se está vazio
ls -la
```

### Via FTP/SFTP:

1. Conecte via FileZilla ou similar
2. Navegue até `adv.joelsouza.com.br`
3. Selecione **TODOS** os arquivos e pastas
4. Delete tudo
5. Verifique se a pasta está vazia

---

## 📤 Passo 2: Enviar Arquivos Novamente

### Opção A: Via Git (se o repositório estiver configurado)

```bash
# No servidor
cd ~/adv.joelsouza.com.br

# Clonar o repositório
git clone https://github.com/seu-usuario/Projeto_Advocacia.git .

# Ou fazer pull se já existe .git
git pull origin main
```

### Opção B: Via FTP/SFTP

1. **Envie TODOS os arquivos do projeto local**
2. **EXCETO:**
   - `node_modules/` (não é necessário)
   - `.git/` (opcional, mas recomendado manter)
   - `storage/logs/*.log` (opcional)

3. **Certifique-se de enviar:**
   - ✅ Toda a estrutura de pastas
   - ✅ `public/` (com todos os arquivos vendor)
   - ✅ `vendor/`
   - ✅ `app/`, `config/`, `database/`, `resources/`, `routes/`
   - ✅ `composer.json`, `composer.lock`
   - ✅ `.htaccess` (na raiz e em `public/`)

---

## ⚙️ Passo 3: Configurar .env

```bash
# No servidor
cd ~/adv.joelsouza.com.br

# Copiar env.example para .env
cp env.example .env

# Editar .env
nano .env
```

Configure:

```env
APP_NAME="Sistema de Advocacia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://adv.joelsouza.com.br

# Gerar APP_KEY (execute no servidor):
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
# Cole o resultado em APP_KEY=

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hotel631_advocacia
DB_USERNAME=hotel631_joeladv
DB_PASSWORD=sua_senha_aqui

SESSION_DRIVER=database
CACHE_STORE=database
```

---

## 📦 Passo 4: Instalar Dependências

**✅ RECOMENDADO: Executar Composer no Servidor**

```bash
# No servidor
cd ~/adv.joelsouza.com.br

# Instalar dependências do Composer
composer install --no-dev --optimize-autoloader
```

**Por que é melhor executar no servidor:**
- ✅ Muito mais rápido (não precisa enviar milhares de arquivos)
- ✅ Garante compatibilidade com PHP do servidor
- ✅ Evita problemas de permissões
- ✅ Mais seguro

**Se o servidor NÃO tem Composer:**
1. Instale o Composer no servidor (peça ao provedor ou instale via SSH)
2. Ou como último recurso: instale localmente e envie `vendor/` (não recomendado)

---

## 🗄️ Passo 5: Configurar Banco de Dados

### Opção A: Importar SQL (recomendado)

```bash
# No servidor, importar o SQL
mysql -u hotel631_joeladv -p hotel631_advocacia < database/sql/advocacia.sql
```

### Opção B: Criar Tabelas Manualmente

```bash
# Executar script de correção que cria tabelas faltantes
php scripts/corrigir-problemas.php
```

---

## 🔐 Passo 6: Configurar Permissões

```bash
# No servidor
cd ~/adv.joelsouza.com.br

# Permissões de storage e cache
chmod -R 775 storage bootstrap/cache

# Permissões de public
chmod -R 755 public
chmod 644 public/index.php
chmod 644 public/.htaccess

# Permissões de .htaccess na raiz
chmod 644 .htaccess
```

---

## 🌐 Passo 7: Configurar DocumentRoot

### Opção 1: Via cPanel (Recomendado)

1. Acesse cPanel
2. Vá em "Subdomínios" ou "Configurações do Domínio"
3. Encontre `adv.joelsouza.com.br`
4. Altere DocumentRoot para: `adv.joelsouza.com.br/public`
5. Salve

### Opção 2: Via .htaccess na Raiz

O arquivo `.htaccess` na raiz já deve estar presente. Verifique:

```bash
cat .htaccess
```

Deve conter:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## ✅ Passo 8: Verificar Instalação

```bash
# No servidor
cd ~/adv.joelsouza.com.br

# Executar diagnóstico
php scripts/diagnosticar-erros.php

# Executar diagnóstico de 403
php scripts/diagnosticar-403.php
```

---

## 🧪 Passo 9: Testar no Navegador

1. Acesse: `https://adv.joelsouza.com.br`
2. Deve carregar a página de login
3. Teste fazer login

---

## 📋 Checklist Final

- [ ] Todos os arquivos enviados
- [ ] `.env` configurado com APP_KEY e banco de dados
- [ ] Dependências instaladas (`vendor/` existe)
- [ ] Banco de dados importado ou tabelas criadas
- [ ] Permissões configuradas (storage, bootstrap/cache, public)
- [ ] DocumentRoot aponta para `public/` (via cPanel ou .htaccess)
- [ ] Diagnóstico não mostra erros
- [ ] Site carrega no navegador

---

## 🆘 Problemas Comuns

### Erro 403
- Verifique DocumentRoot aponta para `public/`
- Verifique `.htaccess` na raiz existe e está correto
- Execute: `php scripts/diagnosticar-403.php`

### Erro 500
- Verifique permissões: `chmod -R 775 storage bootstrap/cache`
- Verifique logs: `tail -f storage/logs/laravel.log`
- Verifique `.env` está configurado corretamente

### Arquivos CSS/JS não carregam
- Verifique se `public/css/vendor/` e `public/js/vendor/` existem
- Verifique permissões: `chmod -R 755 public`

---

## 🔗 Scripts Úteis

- `scripts/diagnosticar-erros.php` - Diagnóstico completo
- `scripts/diagnosticar-403.php` - Diagnóstico de erro 403
- `scripts/corrigir-problemas.php` - Corrige problemas comuns

