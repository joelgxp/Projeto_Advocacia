# 🔧 Corrigir Login Sem Estilo e Erro ao Fazer Login

## ⚠️ Problemas Identificados

1. **CSS não carrega** - Página de login sem estilização
2. **Erro ao fazer login** - "Não é possível acessar esse site"

## ✅ Solução 1: Verificar se o Servidor Está Rodando

### Passo 1: Verificar se o servidor está ativo

Abra o PowerShell e execute:
```powershell
Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue
```

Se não retornar nada, o servidor não está rodando.

### Passo 2: Iniciar o servidor

```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
php -S localhost:8000 router.php
```

**IMPORTANTE:** Deixe este terminal aberto!

## ✅ Solução 2: Verificar base_url

### Passo 1: Verificar .env

Abra o arquivo `.env` e verifique se tem:
```env
APP_BASEURL=http://localhost:8000/
```

Se não tiver, adicione esta linha.

### Passo 2: Limpar cache do navegador

Pressione `Ctrl + Shift + R` para forçar recarregamento sem cache.

## ✅ Solução 3: Testar Assets Manualmente

### No navegador, acesse diretamente:

1. **CSS Bootstrap:** `http://localhost:8000/assets/css/vendor/bootstrap.min.css`
   - Deve mostrar o código CSS
   - Se der 404, o problema é no router

2. **CSS Style:** `http://localhost:8000/assets/css/style.css`
   - Deve mostrar o código CSS

3. **JS jQuery:** `http://localhost:8000/assets/js/vendor/jquery.min.js`
   - Deve mostrar o código JavaScript

## ✅ Solução 4: Verificar Router

O `router.php` deve estar servindo os arquivos de `assets/`. 

Verifique se o arquivo existe:
```powershell
Test-Path "router.php"
```

## ✅ Solução 5: Usar URL Absoluta (Temporário)

Se o problema persistir, podemos modificar temporariamente o login.php para usar URLs absolutas.

## 🔍 Diagnóstico Rápido

Execute no PowerShell:
```powershell
# 1. Verificar se servidor está rodando
Get-NetTCPConnection -LocalPort 8000

# 2. Testar acesso ao CSS
Invoke-WebRequest -Uri "http://localhost:8000/assets/css/vendor/bootstrap.min.css" -UseBasicParsing

# 3. Verificar se router.php existe
Test-Path "router.php"

# 4. Verificar se assets existem
Test-Path "assets/css/vendor/bootstrap.min.css"
```

## 🆘 Se Nada Funcionar

### Opção 1: Reiniciar tudo

1. Feche todos os terminais
2. Abra um novo PowerShell
3. Execute:
```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
php -S localhost:8000 router.php
```
4. Acesse: `http://localhost:8000`
5. Pressione `Ctrl + Shift + R` para limpar cache

### Opção 2: Usar outra porta

Se a porta 8000 estiver com problema:
```powershell
php -S localhost:8080 router.php
```

E atualize o `.env`:
```env
APP_BASEURL=http://localhost:8080/
```

## 📝 Checklist

- [ ] Servidor está rodando (`php -S localhost:8000 router.php`)
- [ ] Terminal do servidor está aberto
- [ ] `.env` tem `APP_BASEURL=http://localhost:8000/`
- [ ] CSS acessível em `http://localhost:8000/assets/css/vendor/bootstrap.min.css`
- [ ] Cache do navegador limpo (`Ctrl + Shift + R`)
- [ ] Tentou fazer login novamente


