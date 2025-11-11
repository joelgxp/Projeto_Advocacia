# Quick Start - Sistema de Advocacia Laravel

## Passos Rápidos para Testar

### 1. Completar Instalação do Composer

```bash
# Se a instalação foi interrompida, complete:
composer install --no-interaction --ignore-platform-reqs

# Ou reinstale completamente:
Remove-Item -Recurse -Force vendor -ErrorAction SilentlyContinue
Remove-Item composer.lock -ErrorAction SilentlyContinue
composer install --no-interaction --ignore-platform-reqs
```

### 2. Instalar Spatie Permission

```bash
composer require spatie/laravel-permission --no-interaction --ignore-platform-reqs
```

### 3. Configurar Ambiente

```bash
# Copiar .env se não existir
if (-not (Test-Path .env)) { Copy-Item env.example .env }

# Gerar chave da aplicação
php artisan key:generate
```

### 4. Configurar Banco de Dados

Edite o arquivo `.env` e configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=advocacia
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Executar Migrations

```bash
php artisan migrate
```

### 6. Popular Banco de Dados

```bash
php artisan db:seed
```

### 7. Testar

```bash
# Verificar versão
php artisan --version

# Iniciar servidor
php artisan serve
```

Acesse: http://localhost:8000

### 8. Login

- **Email:** admin@advocacia.com
- **Senha:** 123456

## Troubleshooting

### Erro: Class not found
```bash
composer dump-autoload -o
```

### Erro: Permission denied
```bash
# Windows - verificar permissões
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

### Erro: Database connection
- Verifique se o MySQL está rodando
- Verifique as credenciais no .env
- Crie o banco de dados: `CREATE DATABASE advocacia;`

### Erro: Missing extensions
Habilite no `php.ini`:
- extension=zip
- extension=gd
- extension=mbstring
- extension=openssl
- extension=pdo_mysql

