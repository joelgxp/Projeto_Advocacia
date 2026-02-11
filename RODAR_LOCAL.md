# 🚀 Como Rodar o Sistema Localmente

## ⚡ Início Rápido

### Método Manual (Recomendado)
```powershell
# 1. Configurar .env
Copy-Item env.example .env
notepad .env

# 2. Criar banco de dados
php scripts/criar-banco-dados.php

# 3. Iniciar servidor
php -S localhost:8000 router.php
```

### Windows (PowerShell) - Script Automatizado
```powershell
.\scripts\iniciar-local.ps1
```

### Linux/Mac
```bash
chmod +x scripts/iniciar-local.sh
./scripts/iniciar-local.sh
```

## 📋 Passo a Passo Manual

### 1. Configurar .env

Copie o arquivo de exemplo:
```powershell
# Windows
Copy-Item env.example .env

# Linux/Mac
cp env.example .env
```

Edite o `.env` e configure:

```env
APP_ENVIRONMENT=development
APP_BASEURL=http://localhost:8000/
DB_HOSTNAME=localhost
DB_USERNAME=root
DB_PASSWORD=sua_senha
DB_DATABASE=advocacia
```

**Gerar chave de criptografia:**
```bash
php -r "echo base64_encode(random_bytes(32));"
```
Cole o resultado em `APP_ENCRYPTION_KEY=`

### 2. Criar Banco de Dados

```sql
CREATE DATABASE advocacia CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### 3. Criar Tabela de Sessões

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

### 4. Criar Usuário Administrador

```sql
USE advocacia;

-- Criar usuário admin
-- Senha: admin123
INSERT INTO usuarios (nome, usuario, senha, nivel, ativo, data_cadastro) 
VALUES (
    'Administrador', 
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    1, 
    NOW()
);
```

**Credenciais:**
- Usuário: `admin`
- Senha: `admin123`

### 5. Iniciar Servidor

```bash
php -S localhost:8000 router.php
```

### 6. Acessar

Abra no navegador:
```
http://localhost:8000
```

## 🔧 Verificações

O script `iniciar-local.ps1` automaticamente:
- ✅ Verifica se `.env` existe
- ✅ Verifica se PHP está instalado
- ✅ Cria diretórios necessários (logs, cache, sessions)
- ✅ Copia fontes do Font Awesome
- ✅ Inicia o servidor

## ⚠️ Problemas Comuns

### Erro: "Unable to connect to database"
- Verifique se o MySQL está rodando
- Confirme as credenciais no `.env`
- Teste: `mysql -u root -p`

### Erro: "404 Not Found"
- Acesse: `http://localhost:8000/index.php/login`
- Verifique se o `base_url` no `.env` está correto

### Fontes não carregam
- Execute: `php scripts/criar-webfonts.php`
- Verifique se `public/css/webfonts/` existe

### Erro de sessão
- Crie a tabela `ci_sessions` (veja passo 3)
- Verifique permissões do diretório `application/sessions/`

## 📝 Notas

- O sistema usa CodeIgniter 3
- Sessões são armazenadas no banco de dados
- Ambiente padrão: `development` (mostra erros)
- Para produção, altere `APP_ENVIRONMENT=production`

