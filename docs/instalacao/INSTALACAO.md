# Guia de Instalação - Sistema de Advocacia Laravel

## Pré-requisitos

- PHP 8.3 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js 18+ e NPM
- Git

## Passos de Instalação

### 1. Clonar o Repositório

```bash
git clone <url-do-repositorio>
cd Projeto_Advocacia
```

### 2. Instalar Dependências do Composer

```bash
composer install
```

### 3. Configurar Ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:

```env
APP_NAME="Sistema de Advocacia"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=advocacia
DB_USERNAME=root
DB_PASSWORD=

API_CNJ_KEY=cDZHYzlZa0JadVREZDJCendQbXY6SkJlTzNjLV9TRENyQk1RdnFKZGRQdw==
```

### 4. Gerar Chave da Aplicação

```bash
php artisan key:generate
```

### 5. Criar Banco de Dados

Crie o banco de dados MySQL:

```sql
CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Executar Migrations

```bash
php artisan migrate
```

### 7. Popular Banco de Dados

```bash
php artisan db:seed
```

Isso criará:
- Roles e permissions
- Cargos
- Varas
- Especialidades
- Usuários padrão

### 8. Instalar Dependências do NPM

```bash
npm install
```

### 9. Compilar Assets

Para desenvolvimento:

```bash
npm run dev
```

Para produção:

```bash
npm run build
```

### 10. Criar Link Simbólico para Storage

```bash
php artisan storage:link
```

### 11. Configurar Permissões (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 12. Iniciar Servidor de Desenvolvimento

```bash
php artisan serve
```

Acesse: http://localhost:8000

## Credenciais Padrão

Após executar os seeders, você pode fazer login com:

- **Admin:** admin@advocacia.com / 123456
- **Advogado:** advogado@advocacia.com / 123456
- **Recepcionista:** recepcao@advocacia.com / 123456
- **Tesoureiro:** tesoureiro@advocacia.com / 123456

**⚠️ IMPORTANTE:** Altere as senhas após o primeiro acesso!

## Migração de Dados Antigos

Se você tem dados do sistema antigo e quer migrá-los:

1. Faça backup do banco de dados antigo
2. Certifique-se de que as tabelas antigas ainda existem
3. Execute o seeder de migração:

```bash
php artisan db:seed --class=DadosMigracaoSeeder
```

**Nota:** O seeder de migração precisa ser ajustado conforme a estrutura do seu banco de dados antigo.

## Estrutura de Diretórios

```
app/                    # Aplicação Laravel
├── Http/
│   ├── Controllers/    # Controllers
│   └── Middleware/     # Middlewares
├── Models/             # Models Eloquent
└── Services/           # Services

database/
├── migrations/         # Migrations
└── seeders/           # Seeders

resources/
├── views/             # Views Blade
├── js/                # JavaScript
└── css/               # CSS

routes/                # Rotas
public/                # Arquivos públicos
storage/               # Arquivos de armazenamento
```

## Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Otimizar aplicação
php artisan optimize

# Criar migration
php artisan make:migration create_nome_tabela_table

# Criar model
php artisan make:model NomeModel

# Criar controller
php artisan make:controller NomeController

# Executar migrations
php artisan migrate

# Reverter última migration
php artisan migrate:rollback

# Executar seeders
php artisan db:seed
```

## Problemas Comuns

### Erro de Permissão

Se encontrar erros de permissão no Linux/Mac:

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Erro de Conexão com Banco de Dados

Verifique se:
- O MySQL está rodando
- As credenciais no `.env` estão corretas
- O banco de dados foi criado

### Erro ao Compilar Assets

Certifique-se de que o Node.js está instalado:

```bash
node --version
npm --version
```

Se necessário, reinstale as dependências:

```bash
rm -rf node_modules package-lock.json
npm install
```

## Próximos Passos

1. Configure o servidor web (Apache/Nginx) para produção
2. Configure SSL/HTTPS
3. Configure backup automático do banco de dados
4. Configure monitoramento e logs
5. Configure fila de jobs (se necessário)

## Suporte

Para dúvidas ou problemas, consulte a documentação do Laravel ou entre em contato com a equipe de desenvolvimento.

