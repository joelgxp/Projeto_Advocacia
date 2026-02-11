# ⚡ Solução Rápida - Login Sem Estilo e Erro

## ✅ Problema Identificado

O `.env` tem `APP_URL=http://localhost` mas deveria ser `http://localhost:8000`

## 🔧 Correção Aplicada

O arquivo `.env` foi corrigido automaticamente.

## 📋 Próximos Passos

### 1. Verificar se o servidor está rodando

Abra o PowerShell e execute:
```powershell
Get-NetTCPConnection -LocalPort 8000
```

Se não retornar nada, inicie o servidor:
```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
php -S localhost:8000 router.php
```

**DEIXE O TERMINAL ABERTO!**

### 2. Limpar cache do navegador

No navegador, pressione:
- `Ctrl + Shift + R` (Windows/Linux)
- `Cmd + Shift + R` (Mac)

Isso força o recarregamento sem cache.

### 3. Acessar novamente

1. Acesse: `http://localhost:8000`
2. A página de login deve aparecer com estilo (Bootstrap)
3. Faça login:
   - Usuário: `admin` ou `admin@advocacia.com`
   - Senha: `admin123`

## 🔍 Verificar se Funcionou

### Teste 1: CSS carrega
Abra o DevTools (F12) → Aba Network
- Recarregue a página
- Procure por arquivos `.css`
- Devem ter Status 200 (não 404)

### Teste 2: Login funciona
- Preencha usuário e senha
- Clique em "Entrar"
- Deve redirecionar para o dashboard (não dar erro)

## 🆘 Se Ainda Não Funcionar

### Verificar se servidor está rodando:
```powershell
# Verificar porta
Get-NetTCPConnection -LocalPort 8000

# Se não estiver, iniciar:
php -S localhost:8000 router.php
```

### Verificar .env:
```powershell
Get-Content ".env" | Select-String "APP_URL"
```

Deve mostrar: `APP_URL=http://localhost:8000`

### Reiniciar tudo:
1. Feche o terminal do servidor (Ctrl+C)
2. Feche o navegador
3. Abra novo PowerShell
4. Execute: `php -S localhost:8000 router.php`
5. Abra novo navegador
6. Acesse: `http://localhost:8000`

## ✅ Checklist Final

- [ ] Servidor rodando na porta 8000
- [ ] `.env` tem `APP_URL=http://localhost:8000`
- [ ] Cache do navegador limpo
- [ ] CSS carrega (verificar no DevTools)
- [ ] Login funciona sem erro


