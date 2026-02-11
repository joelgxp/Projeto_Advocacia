# 🧪 Como Testar o Servidor

## ✅ Status Atual

Você está usando o **servidor PHP built-in** (não Apache). Isso é perfeito para desenvolvimento local!

## 🚀 Iniciar o Servidor

### Opção 1: Usando o Script PowerShell

```powershell
.\scripts\iniciar-servidor-test.ps1
```

### Opção 2: Comando Manual

```powershell
php -S localhost:8000 router.php
```

## 🧪 Testar o Sistema

### 1. Acessar a Página de Login

Abra no navegador:
```
http://localhost:8000
```

Ou:
```
http://localhost:8000/login
```

### 2. Fazer Login

**Credenciais:**
- **Usuário:** `admin` ou `admin@advocacia.com`
- **Senha:** `admin123`

### 3. Verificar Redirecionamento

Após o login, você deve ser redirecionado para:
```
http://localhost:8000/admin
```

Se aparecer erro 404, verifique:
- ✅ O servidor está rodando?
- ✅ Você acessou `http://localhost:8000` (não `http://localhost`)?
- ✅ O router.php está sendo usado?

## 🔍 Verificar se Está Funcionando

### Teste 1: Página de Login
```
http://localhost:8000/login
```
Deve mostrar o formulário de login estilizado.

### Teste 2: Assets (CSS/JS)
```
http://localhost:8000/assets/css/vendor/bootstrap.min.css
```
Deve mostrar o conteúdo do CSS (não erro 404).

### Teste 3: Dashboard Admin
Após fazer login, deve carregar:
```
http://localhost:8000/admin
```

## ⚠️ Problemas Comuns

### Erro: "Address already in use"
A porta 8000 já está em uso. Faça:
```powershell
# Parar processos PHP
Get-Process | Where-Object {$_.ProcessName -eq "php"} | Stop-Process -Force

# Ou use outra porta
php -S localhost:8001 router.php
```

### Erro 404 após login
1. Verifique se o servidor está rodando com `router.php`
2. Verifique os logs em `application/logs/`
3. Teste acessar diretamente: `http://localhost:8000/index.php/admin`

### CSS/JS não carregam
1. Verifique se a pasta `assets/` existe na raiz
2. Teste acessar diretamente: `http://localhost:8000/assets/css/style.css`
3. Limpe o cache do navegador (Ctrl+Shift+R)

## 📝 Checklist

- [ ] Servidor iniciado com `php -S localhost:8000 router.php`
- [ ] Página de login carrega em `http://localhost:8000`
- [ ] CSS/JS carregam corretamente
- [ ] Login funciona com credenciais admin
- [ ] Redirecionamento para `/admin` funciona
- [ ] Dashboard admin carrega sem erros

## 🆘 Se Ainda Não Funcionar

1. **Verifique os logs:**
   ```powershell
   Get-Content application/logs/log-*.php -Tail 50
   ```

2. **Teste o router:**
   ```powershell
   php scripts/verificar-apache.php
   ```

3. **Teste acesso direto:**
   - `http://localhost:8000/index.php/login` (deve funcionar)
   - `http://localhost:8000/login` (deve funcionar se router.php estiver OK)

