# 📋 Passo a Passo Manual - Rodar Sistema Localmente

## ✅ Passo 1: Verificar Pré-requisitos

### 1.1. Verificar PHP
Abra o PowerShell e execute:
```powershell
php -v
```
Deve mostrar a versão do PHP (ex: PHP 8.2.12)

### 1.2. Verificar MySQL
Certifique-se de que o MySQL está rodando:
- **XAMPP:** Abra o XAMPP Control Panel e inicie o MySQL
- **WAMP:** Clique no ícone WAMP e inicie o MySQL

## ✅ Passo 2: Configurar .env

### 2.1. Copiar arquivo de exemplo
```powershell
Copy-Item env.example .env
```

### 2.2. Editar .env
Abra o arquivo `.env` no Notepad ou editor de texto e configure:

```env
APP_ENVIRONMENT=development
APP_BASEURL=http://localhost:8000/
DB_HOSTNAME=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=advocacia
```

**Nota:** Se sua senha do MySQL não for vazia, coloque em `DB_PASSWORD=`

### 2.3. Gerar chave de criptografia (opcional)
```powershell
php -r "echo base64_encode(random_bytes(32));"
```
Cole o resultado no `.env` na linha `APP_ENCRYPTION_KEY=`

## ✅ Passo 3: Criar Banco de Dados

### 3.1. Criar banco de dados
Abra o phpMyAdmin (http://localhost/phpmyadmin) ou use o MySQL:

```sql
CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2. Criar tabelas
Execute o script SQL:
```powershell
php scripts/criar-banco-dados.php
```

Ou importe manualmente o arquivo:
- `database/sql/criar-banco.sql`

### 3.3. Criar tabela de sessões
No phpMyAdmin ou MySQL, execute:

```sql
USE advocacia;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## ✅ Passo 4: Verificar Diretórios

Certifique-se de que estes diretórios existem e são graváveis:

```powershell
# Verificar se existem
Test-Path "application/logs"
Test-Path "application/cache"
Test-Path "application/sessions"

# Se não existirem, criar:
New-Item -ItemType Directory -Path "application/logs" -Force
New-Item -ItemType Directory -Path "application/cache" -Force
New-Item -ItemType Directory -Path "application/sessions" -Force
```

## ✅ Passo 5: Iniciar Servidor

### 5.1. Abrir PowerShell
Abra o PowerShell na pasta do projeto:
```powershell
cd "C:\Users\joelv\OneDrive\Documentos\Repositorios\Projeto_Advocacia"
```

### 5.2. Iniciar servidor PHP
Execute:
```powershell
php -S localhost:8000 router.php
```

Você verá algo como:
```
PHP 8.2.12 Development Server (http://localhost:8000) started
```

**IMPORTANTE:** Deixe este terminal aberto enquanto usar o sistema.

## ✅ Passo 6: Acessar o Sistema

### 6.1. Abrir navegador
Acesse: `http://localhost:8000`

### 6.2. Fazer login
- **Usuário:** `admin` ou `admin@advocacia.com`
- **Senha:** `admin123`

### 6.3. Verificar funcionamento
Após o login, você deve ser redirecionado para o dashboard administrativo.

## 🛑 Parar o Servidor

No terminal onde o servidor está rodando, pressione:
```
Ctrl + C
```

## 🔍 Verificar se Está Funcionando

### Teste 1: Servidor responde
Acesse: `http://localhost:8000`
Deve mostrar a página de login.

### Teste 2: Assets carregam
Abra o DevTools (F12) e verifique se não há erros 404 para CSS/JS.

### Teste 3: Login funciona
Faça login e verifique se redireciona sem erro 404.

## 🆘 Problemas Comuns

### Erro: "Unable to connect to database"
- Verifique se MySQL está rodando
- Verifique credenciais no `.env`
- Teste conexão: `mysql -u root -p`

### Erro: "404 Not Found" após login
- Certifique-se de usar `router.php`: `php -S localhost:8000 router.php`
- NÃO use: `php -S localhost:8000 -t .`

### Assets (CSS/JS) não carregam
- Verifique se está usando `router.php`
- Limpe cache do navegador: `Ctrl + Shift + R`

### Porta 8000 já em uso
Use outra porta:
```powershell
php -S localhost:8080 router.php
```
E atualize `APP_BASEURL` no `.env` para `http://localhost:8080/`

## 📝 Checklist Final

- [ ] PHP instalado e funcionando
- [ ] MySQL rodando
- [ ] Arquivo `.env` configurado
- [ ] Banco de dados `advocacia` criado
- [ ] Tabelas criadas (via script ou SQL)
- [ ] Tabela `ci_sessions` criada
- [ ] Diretórios `application/logs`, `application/cache`, `application/sessions` existem
- [ ] Servidor iniciado com `php -S localhost:8000 router.php`
- [ ] Acessou `http://localhost:8000` no navegador
- [ ] Login funcionou

## 🎯 Resumo dos Comandos Essenciais

```powershell
# 1. Configurar .env
Copy-Item env.example .env
notepad .env

# 2. Criar banco de dados
php scripts/criar-banco-dados.php

# 3. Iniciar servidor
php -S localhost:8000 router.php

# 4. Acessar no navegador
# http://localhost:8000
```

Pronto! Agora você tem um guia completo para fazer tudo manualmente. 🚀

