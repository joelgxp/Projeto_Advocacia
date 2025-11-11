# 🚀 Guia de Deploy - Sistema de Advocacia

## 📋 Pré-requisitos

### No ambiente local (para compilar):
- ✅ Node.js 18+ e NPM
- ✅ Composer
- ✅ PHP 8.2+

### No servidor de produção:
- ✅ PHP 8.2+
- ✅ Composer
- ✅ MySQL/MariaDB
- ❌ **NÃO precisa de Node.js** (assets já compilados)

---

## 🔧 Deploy Automatizado

### Windows (PowerShell)

```powershell
.\deploy.ps1
```

### Linux/Mac (Bash)

```bash
chmod +x deploy.sh
./deploy.sh
```

O script automaticamente:
1. ✅ Verifica Node.js e Composer
2. ✅ Instala dependências NPM (se necessário)
3. ✅ **Compila assets com Vite** (`npm run build`)
4. ✅ Instala dependências Composer para produção
5. ✅ Gera cache do Laravel (se .env existir)

---

## 📦 Deploy Manual

### Passo 1: Compilar Assets (Local)

```bash
# Instalar dependências (primeira vez)
npm install

# Compilar assets para produção
npm run build
```

Isso cria a pasta `public/build/` com os arquivos otimizados.

### Passo 2: Preparar para Produção (Local)

```bash
# Instalar dependências Composer (sem dev)
composer install --no-dev --optimize-autoloader
```

### Passo 3: Enviar para Servidor

Envie todos os arquivos, **incluindo**:
- ✅ `public/build/` (importante!)
- ✅ `vendor/`
- ✅ Todo o código PHP
- ✅ `.env.example` (renomeie para `.env` no servidor)

**NÃO envie:**
- ❌ `node_modules/` (não é necessário)
- ❌ `.env` (configure no servidor)
- ❌ `storage/logs/*.log`

### Passo 4: Configurar no Servidor

```bash
# 1. Copiar .env.example para .env
cp .env.example .env

# 2. Editar .env com as configurações do servidor
nano .env

# 3. Gerar chave da aplicação
php artisan key:generate

# 4. Instalar dependências (se não enviou vendor/)
composer install --no-dev --optimize-autoloader

# 5. Executar migrations
php artisan migrate

# 6. Executar seeders (opcional)
php artisan db:seed

# 7. Gerar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Configurar permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔍 Verificação Pós-Deploy

### 1. Verificar se assets estão carregando

Acesse o site e verifique no console do navegador (F12):
- ✅ CSS deve carregar de `/build/assets/app-xxx.css`
- ✅ JS deve carregar de `/build/assets/app-xxx.js`
- ❌ Não deve ter erros 404

### 2. Verificar cache

```bash
# Verificar cache de configuração
php artisan config:show

# Verificar rotas
php artisan route:list
```

### 3. Verificar logs

```bash
# Ver erros recentes
tail -f storage/logs/laravel.log
```

---

## 🐛 Troubleshooting

### Assets não carregam (404)

**Problema**: `public/build/` não foi enviado ou não foi compilado.

**Solução**:
```bash
# No local, compilar novamente
npm run build

# Verificar se public/build/ existe
ls -la public/build/

# Enviar public/build/ para o servidor
```

### Erro: "Vite manifest not found"

**Problema**: Arquivo `public/build/manifest.json` não existe.

**Solução**:
```bash
# Compilar assets
npm run build

# Verificar se manifest.json foi criado
ls public/build/manifest.json
```

### Erro de permissões

**Problema**: Laravel não consegue escrever em `storage/` ou `bootstrap/cache/`.

**Solução**:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Assets antigos sendo servidos

**Problema**: Cache do navegador ou do Laravel.

**Solução**:
```bash
# Limpar cache do Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recompilar assets (gera novos hashes)
npm run build
```

---

## 📝 Checklist de Deploy

### Antes do Deploy
- [ ] Testar localmente: `php artisan serve`
- [ ] Executar testes (se houver): `php artisan test`
- [ ] Verificar `.env.example` está atualizado
- [ ] Compilar assets: `npm run build`
- [ ] Verificar se `public/build/` foi criado

### Durante o Deploy
- [ ] Executar script de deploy: `.\deploy.ps1` ou `./deploy.sh`
- [ ] Enviar arquivos para servidor (FTP/SFTP/Git)
- [ ] Configurar `.env` no servidor
- [ ] Executar migrations: `php artisan migrate`
- [ ] Gerar cache: `php artisan config:cache`

### Após o Deploy
- [ ] Verificar se site está acessível
- [ ] Verificar se assets carregam (F12 no navegador)
- [ ] Testar login
- [ ] Verificar logs: `storage/logs/laravel.log`
- [ ] Testar funcionalidades principais

---

## 🔄 Atualizações Futuras

Para atualizar o sistema:

```bash
# 1. No local: compilar novos assets
npm run build

# 2. Enviar apenas arquivos alterados + public/build/

# 3. No servidor: atualizar dependências
composer install --no-dev --optimize-autoloader

# 4. Limpar e regenerar cache
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
```

---

## 📚 Comandos Úteis

```bash
# Desenvolvimento
npm run dev          # Watch mode (recompila automaticamente)

# Produção
npm run build        # Compila uma vez, otimizado

# Laravel
php artisan optimize # Otimiza tudo (config, route, view)
php artisan optimize:clear # Limpa todos os caches
```

---

## ⚠️ Importante

1. **Sempre compile assets antes de fazer deploy**
2. **Sempre envie `public/build/` para o servidor**
3. **Nunca envie `.env` para repositório Git**
4. **Configure `.env` no servidor com dados de produção**
5. **Use `composer install --no-dev` em produção**

---

## 🆘 Suporte

Se encontrar problemas:
1. Verifique os logs: `storage/logs/laravel.log`
2. Verifique permissões de arquivos
3. Verifique se `public/build/` existe
4. Limpe todos os caches e recompile

