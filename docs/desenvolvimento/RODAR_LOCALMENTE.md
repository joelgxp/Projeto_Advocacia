# Como Rodar o Projeto Localmente

## Requisitos

- **PHP 8.3+** ([Download](https://windows.php.net/download))
- **Composer** ([Download](https://getcomposer.org/download/))
- **MySQL 8.0+** (via XAMPP, Laragon ou MySQL standalone)
- **Git** (já instalado)

## Passo a Passo

### 1. Clonar o Repositório

```bash
git clone https://github.com/joelgxp/Projeto_Advocacia.git
cd Projeto_Advocacia
```

Se já tem o projeto clonado, apenas faça pull:
```bash
git pull origin main
```

### 2. Instalar Dependências PHP

```bash
composer install
```

**Nota:** Como removemos o npm/Vite, não é necessário `npm install`!

### 3. Configurar o Arquivo .env

Copie o arquivo de exemplo:
```bash
copy env.example .env
```

Edite o `.env` e configure:

```env
APP_NAME="Sistema de Advocacia"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Gerar essa chave no próximo passo
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=advocacia
DB_USERNAME=root
DB_PASSWORD=

# API CNJ (opcional para testes)
API_CNJ_KEY=sua_chave_aqui
```

### 4. Gerar APP_KEY Manualmente

```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Copie o resultado e cole no `.env` na linha `APP_KEY=`

Exemplo de resultado:
```
base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 5. Criar Banco de Dados

Abra o phpMyAdmin (se usar XAMPP/Laragon) ou MySQL Workbench e execute:

```sql
CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ou via linha de comando:
```bash
mysql -uroot -p -e "CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Executar Migrações (via SQL)

Como não usamos artisan no servidor compartilhado, importe o SQL diretamente:

**Via phpMyAdmin:**
1. Abra phpMyAdmin
2. Selecione o banco `advocacia`
3. Vá em "Importar"
4. Selecione `database/sql/advocacia.sql`
5. Clique em "Executar"

**Via linha de comando:**
```bash
mysql -uroot -p advocacia < database/sql/advocacia.sql
```

### 7. Popular Dados Iniciais

Os dados iniciais já estão no arquivo SQL acima, incluindo:
- Cargos padrão
- Especialidades
- Varas
- Usuários de teste

### 8. Iniciar o Servidor

Use o PHP built-in server:
```bash
php -S localhost:8000 -t public
```

Ou especificar outro endereço:
```bash
php -S 127.0.0.1:8080 -t public
```

### 9. Acessar o Sistema

Abra o navegador: **http://localhost:8000**

**Credenciais padrão (criadas pelo seeder):**
- **Admin:** admin@sistema.com / senha: `password`
- **Advogado:** advogado@sistema.com / senha: `password`
- **Recepção:** recepcao@sistema.com / senha: `password`

## Solução de Problemas

### Erro: "vendor/autoload.php not found"
```bash
composer install
```

### Erro: "APP_KEY not set"
Gere manualmente:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```
Cole o resultado no `.env` na linha `APP_KEY=`

### Erro: "Access denied for user"
Verifique as credenciais do MySQL no `.env`

### Erro: "Class not found"
```bash
composer dump-autoload
```

### Erro: "Permission denied" (storage/logs)
**Windows (PowerShell como Admin):**
```powershell
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

**Linux/Mac:**
```bash
chmod -R 775 storage bootstrap/cache
```

### Limpar Cache Manualmente (SEM ARTISAN)

**Windows:**
```powershell
Remove-Item bootstrap\cache\*.php -Force
```

**Linux/Mac:**
```bash
rm -rf bootstrap/cache/*.php
```

### Erro: "No application encryption key has been specified"

Gere a chave manualmente:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Copie o resultado e cole no `.env` na linha `APP_KEY=`

### Erro: "SQLSTATE[HY000] [1045] Access denied"

Verifique as credenciais do MySQL no `.env`:
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=advocacia`
- `DB_USERNAME=root`
- `DB_PASSWORD=` (sua senha do MySQL)

### Erro: "SQLSTATE[HY000] [1049] Unknown database 'advocacia'"

Crie o banco de dados:
```sql
CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ou via linha de comando:
```bash
mysql -uroot -p -e "CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Depois importe o SQL:
```bash
mysql -uroot -p advocacia < database/sql/advocacia.sql
```

### Erro: "Class 'X' not found"

Regenere o autoload do Composer:
```bash
composer dump-autoload
```

### Script de Verificação Automática

Execute o script de verificação:
```powershell
.\verificar-local.ps1
```

Este script verifica:
- ✅ PHP instalado
- ✅ Composer instalado
- ✅ Dependências instaladas
- ✅ Arquivo .env configurado
- ✅ APP_KEY configurado
- ✅ Permissões de storage
- ✅ Limpa cache automaticamente

## Estrutura do Projeto

```
Projeto_Advocacia/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/               # Models Eloquent
│   ├── Services/             # Lógica de negócio
│   ├── Helpers/              # Helpers customizados
│   └── Providers/            # Service Providers
├── database/
│   ├── migrations/           # Migrações do banco
│   └── seeders/              # Dados iniciais
├── public/                   # Pasta pública (DocumentRoot)
│   ├── index.php             # Entry point
│   ├── css/                  # CSS customizado
│   └── js/                   # JS customizado
├── resources/
│   └── views/                # Templates Blade
└── routes/                   # Definição de rotas
    ├── web.php               # Rotas públicas
    ├── admin.php             # Rotas admin
    ├── advogado.php          # Rotas advogado
    └── recepcao.php          # Rotas recepção
```

## Recursos do Sistema

- **Gestão de Processos** com numeração CNJ
- **Consulta Processual** via API DataJud
- **Gestão de Clientes e Advogados**
- **Agenda e Tarefas**
- **Audiências**
- **Documentos**
- **Controle Financeiro** (Contas a pagar/receber)
- **Dashboard** por perfil (Admin, Advogado, Recepção, Cliente)

## Tecnologias

- Laravel 11
- PHP 8.3
- MySQL 8.0
- Bootstrap 5.3.2 (via CDN)
- jQuery 3.7.1 (via CDN)
- Font Awesome 6.5.1 (via CDN)

## Deploy

O projeto usa GitHub Actions para deploy automático via SSH.
Veja [DEPLOY.md](../deploy/DEPLOY.md) para mais informações.

