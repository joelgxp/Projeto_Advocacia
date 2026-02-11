# 🚀 Como Iniciar o Servidor Local

## ✅ Método Recomendado: Servidor PHP Built-in

O servidor PHP built-in é a forma mais simples e confiável para desenvolvimento local.

### Iniciar o Servidor

```powershell
php -S localhost:8000 router.php
```

### Ou use o script automatizado:

```powershell
.\scripts\iniciar-local.ps1
```

## 📋 O que o servidor faz

1. **Serve arquivos estáticos** (CSS, JS, imagens) da pasta `assets/`
2. **Processa rotas do CodeIgniter** através do `router.php`
3. **Usa o index.php da raiz** (CodeIgniter, não Laravel)

## 🌐 Acessar o Sistema

Após iniciar o servidor, acesse:

- **URL:** `http://localhost:8000`
- **Login:** 
  - Usuário: `admin` ou `admin@advocacia.com`
  - Senha: `admin123`

## ⚠️ Importante

- **NÃO use Apache** para desenvolvimento local (a menos que configure corretamente)
- **Use sempre o router.php** para garantir que os assets sejam servidos corretamente
- **Porta 8000** é a padrão, mas você pode usar outra se necessário

## 🛑 Parar o Servidor

Pressione `Ctrl + C` no terminal onde o servidor está rodando.

## 🔍 Verificar se está rodando

Execute:
```powershell
Get-NetTCPConnection -LocalPort 8000
```

Se retornar algo, o servidor está rodando.

## 🆘 Problemas

### Porta 8000 já em uso

Use outra porta:
```powershell
php -S localhost:8080 router.php
```

### Assets não carregam

Certifique-se de usar `router.php`:
```powershell
php -S localhost:8000 router.php
```

NÃO use:
```powershell
php -S localhost:8000 -t .
```

### Erro 404 após login

1. Verifique se o servidor está usando `router.php`
2. Verifique se o banco de dados foi criado: `php scripts/criar-banco-dados.php`
3. Verifique os logs: `application/logs/`

