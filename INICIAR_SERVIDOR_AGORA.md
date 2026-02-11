# 🚀 Como Iniciar o Servidor AGORA

## ⚠️ Erro: ERR_CONNECTION_REFUSED

Este erro significa que o servidor não está rodando. Siga estes passos:

## ✅ Passo 1: Abrir PowerShell

1. Pressione `Windows + X`
2. Selecione "Windows PowerShell" ou "Terminal"
3. Navegue até a pasta do projeto:

```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
```

## ✅ Passo 2: Verificar se está na pasta correta

Execute:
```powershell
Get-Location
```

Deve mostrar: `C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia`

Se não estiver, execute o comando do Passo 1 novamente.

## ✅ Passo 3: Verificar se os arquivos existem

```powershell
Test-Path "router.php"
Test-Path "index.php"
```

Ambos devem retornar `True`.

## ✅ Passo 4: Iniciar o Servidor

Execute este comando:

```powershell
php -S localhost:8000 router.php
```

**IMPORTANTE:** Você verá uma mensagem como:
```
PHP 8.2.12 Development Server (http://localhost:8000) started
```

**DEIXE ESTE TERMINAL ABERTO!** Se fechar o terminal, o servidor para.

## ✅ Passo 5: Verificar se está funcionando

### Opção 1: No navegador
Abra: `http://localhost:8000`

### Opção 2: No PowerShell (outro terminal)
```powershell
Invoke-WebRequest -Uri "http://localhost:8000" -UseBasicParsing
```

Se retornar Status 200, está funcionando!

## 🆘 Problemas Comuns

### Erro: "php não é reconhecido"
- O PHP não está no PATH do sistema
- Use o caminho completo: `C:\xampp\php\php.exe -S localhost:8000 router.php`

### Erro: "Porta 8000 já em uso"
Use outra porta:
```powershell
php -S localhost:8080 router.php
```
E acesse: `http://localhost:8080`

### Servidor inicia mas não carrega a página
- Verifique se o `router.php` existe na pasta
- Verifique se o `index.php` existe na raiz
- Verifique os logs: `application/logs/`

## 📝 Checklist

- [ ] PowerShell aberto na pasta do projeto
- [ ] Comando `php -S localhost:8000 router.php` executado
- [ ] Mensagem "Server started" apareceu
- [ ] Terminal está aberto (não fechou)
- [ ] Acessou `http://localhost:8000` no navegador

## 🎯 Comando Completo (Copie e Cole)

```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
php -S localhost:8000 router.php
```

Depois acesse: `http://localhost:8000`

