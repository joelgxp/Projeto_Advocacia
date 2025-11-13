# Sistema de Gerenciamento para Escritório de Advocacia

Sistema moderno desenvolvido com Laravel 10 para gerenciamento completo de escritórios de advocacia.

## 🚀 Tecnologias

- **Laravel 10**
- **PHP 8.2+**
- **MySQL**
- **Bootstrap 5**
- **Alpine.js**
- **Vite**

## 📋 Funcionalidades

- ✅ Gestão de Clientes (PF e PJ)
- ✅ Gestão de Processos Judiciais
- ✅ Controle de Prazos e Audiências
- ✅ Gestão de Advogados e Especialidades
- ✅ Sistema de Notificações
- ✅ Gestão Financeira
- ✅ Área do Cliente
- ✅ Integração com APIs de Tribunais

## 📁 Estrutura do Projeto

```
Projeto_Advocacia/
├── app/                    # Código da aplicação Laravel
├── config/                 # Configurações
├── database/               # Migrations, Seeders e SQLs
├── docs/                   # Documentação
├── legacy/                 # Código legado (referência)
├── public/                 # Assets públicos (css, js, img, build)
├── resources/              # Views e assets fonte
├── routes/                 # Rotas da aplicação
├── storage/                # Arquivos e logs
└── tests/                  # Testes automatizados
```

## 🛠️ Instalação

### Requisitos

- PHP 8.2+
- Composer
- Node.js 18+ (apenas para desenvolvimento)
- MySQL 5.7+

### Passos

1. **Clone o repositório**
   ```bash
   git clone <repo-url>
   cd Projeto_Advocacia
   ```

2. **Instale as dependências**
   ```bash
   composer install
   npm install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados no `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=advocacia
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Execute as migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile os assets (desenvolvimento)**
   ```bash
   npm run dev
   ```

   Ou para produção:
   ```bash
   npm run build
   ```

7. **Inicie o servidor**
   ```bash
   php artisan serve
   ```

## 🚀 Deploy

### Deploy Automatizado

Execute o script de deploy:

**Windows:**
```powershell
.\deploy.ps1
```

**Linux/Mac:**
```bash
chmod +x deploy.sh
./deploy.sh
```

O script automaticamente:
- ✅ Compila os assets com Vite
- ✅ Prepara dependências para produção
- ✅ Gera cache do Laravel

### Deploy Manual

1. **Compile os assets:**
   ```bash
   npm run build
   ```

2. **Instale dependências de produção:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Envie para o servidor** (incluindo `public/build/`)

4. **No servidor:**
   ```bash
   php artisan migrate
   php artisan config:cache
   php artisan route:cache
   ```

📖 **Documentação completa de deploy:** `docs/DEPLOY.md`

## 🔐 Credenciais Padrão

- **Admin**: `admin@advocacia.com` / `123456`
- **Advogado**: `advogado@advocacia.com` / `123456`
- **Recepcionista**: `recepcao@advocacia.com` / `123456`
- **Tesoureiro**: `tesoureiro@advocacia.com` / `123456`

⚠️ **IMPORTANTE**: Altere essas senhas em produção!

## 📚 Documentação

Consulte a pasta `docs/` para documentação detalhada:

- `docs/DEPLOY.md` - Guia completo de deploy
- `docs/ARQUITETURA_MODERNA.md` - Arquitetura do sistema
- `docs/INSTALACAO.md` - Guia de instalação completo

## 🗂️ Código Legado

O código legado foi movido para a pasta `legacy/` para referência durante a migração. Não é mais utilizado pela aplicação Laravel.

## 📝 Licença

MIT

## 👥 Contribuição

Contribuições são bem-vindas! Por favor, abra uma issue ou pull request.
