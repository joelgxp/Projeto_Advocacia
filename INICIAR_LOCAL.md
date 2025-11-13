# 🚀 Como Rodar o Sistema Localmente (CodeIgniter 3)

## 📋 Pré-requisitos

- PHP 7.4+ ou 8.0+ (recomendado 8.2)
- MySQL/MariaDB
- Composer (opcional, apenas para dependências extras)

## 🔧 Passo 1: Configurar Variáveis de Ambiente

1. Copie o arquivo `env.example` para `.env`:
```bash
# Windows PowerShell
Copy-Item env.example .env

# Linux/Mac
cp env.example .env
```

2. Edite o arquivo `.env` e configure:

```env
# URL base do sistema (ajuste para sua porta)
APP_BASEURL=http://localhost:8000/

# Banco de Dados
DB_HOSTNAME=localhost
DB_USERNAME=root
DB_PASSWORD=sua_senha
DB_DATABASE=advocacia

# Chave de criptografia (gere uma nova)
APP_ENCRYPTION_KEY=sua_chave_aqui
```

**Gerar chave de criptografia:**
```bash
php -r "echo base64_encode(random_bytes(32));"
```
Cole o resultado no `.env` na linha `APP_ENCRYPTION_KEY=`

## 🗄️ Passo 2: Criar Banco de Dados

1. Acesse o MySQL:
```bash
mysql -u root -p
```

2. Crie o banco de dados:
```sql
CREATE DATABASE advocacia CHARACTER SET utf8 COLLATE utf8_general_ci;
EXIT;
```

## 📦 Passo 3: Executar Migrations (Opcional)

Se você já tem o banco de dados do Laravel, pode pular este passo.

Para criar as tabelas do CodeIgniter 3:

1. Configure o CodeIgniter para executar migrations:
   - Edite `application/config/migration.php` (crie se não existir)
   - Defina `$config['migration_enabled'] = TRUE;`

2. Execute as migrations via CLI ou interface web (se implementada)

**OU** importe o banco de dados existente do Laravel (as tabelas são compatíveis).

## 🚀 Passo 4: Iniciar Servidor PHP

### Opção 1: Servidor PHP Built-in (Recomendado)

```bash
# Windows PowerShell
php -S localhost:8000

# Linux/Mac
php -S localhost:8000
```

### Opção 2: Com DocumentRoot na raiz

```bash
php -S localhost:8000 -t .
```

### Opção 3: Com XAMPP/WAMP

1. Configure o VirtualHost apontando para a pasta do projeto
2. Acesse via `http://localhost/advocacia`

## 🌐 Passo 5: Acessar o Sistema

Abra o navegador em:
```
http://localhost:8000
```

Você será redirecionado para a página de login.

## 👤 Passo 6: Criar Usuário Administrador

Se ainda não tiver usuário, você pode criar manualmente no banco:

```sql
USE advocacia;

-- Criar grupo de permissões Admin
INSERT INTO permissoes (nome, permissoes, situacao) 
VALUES ('Admin', 'a:1:{s:12:"admin.access";s:1:"1";}', 1);

-- Criar usuário admin
-- Senha padrão: admin123 (hash)
INSERT INTO usuarios (nome, email, senha, permissoes_id, ativo, dataCadastro) 
VALUES (
    'Administrador', 
    'admin@advocacia.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    1, 
    1, 
    NOW()
);
```

**Credenciais padrão:**
- Email: `admin@advocacia.com`
- Senha: `admin123`

⚠️ **IMPORTANTE**: Altere a senha após o primeiro login!

## 🔍 Verificar se Está Funcionando

1. Acesse `http://localhost:8000`
2. Você deve ver a tela de login
3. Faça login com as credenciais acima
4. Deve redirecionar para o dashboard

## ⚠️ Problemas Comuns

### Erro: "Unable to connect to your database server"
- Verifique se o MySQL está rodando
- Confirme as credenciais no `.env`
- Teste a conexão: `mysql -u root -p`

### Erro: "404 Not Found"
- Verifique se o `base_url` no `config.php` está correto
- Confirme que o `.htaccess` está na raiz
- Tente acessar diretamente: `http://localhost:8000/index.php/login`

### Erro: "Session: Failed to initialize storage module"
- Crie a tabela `ci_sessions` no banco
- Execute a migration `003_create_ci_sessions_table.php`
- Ou crie manualmente:

```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### Assets não carregam (CSS/JS não aparecem)
- Verifique se os arquivos estão em `assets/css/`, `assets/js/`
- Confirme que o `base_url` está correto
- Abra o console do navegador (F12) para ver erros 404

## 📝 Notas

- O sistema usa sessões em banco de dados (mais seguro)
- As configurações são carregadas do arquivo `.env`
- O sistema está configurado para desenvolvimento por padrão
- Para produção, altere `APP_ENVIRONMENT=production` no `.env`

## 🎯 Próximos Passos

1. Criar mais usuários conforme necessário
2. Configurar grupos de permissões
3. Popular dados de teste (varas, especialidades, etc.)
4. Testar funcionalidades principais

---

**Sistema migrado de Laravel 10 para CodeIgniter 3**
**Baseado na arquitetura do MapOS**

