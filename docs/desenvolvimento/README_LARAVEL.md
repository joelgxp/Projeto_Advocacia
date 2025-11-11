# Sistema de Advocacia - Laravel 11

## Instalação

### Pré-requisitos
- PHP 8.3 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js e NPM (para assets)

### Passos de Instalação

1. **Instalar dependências do Composer**
```bash
composer install
```

2. **Configurar ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar banco de dados no .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=advocacia
DB_USERNAME=root
DB_PASSWORD=
```

4. **Executar migrations**
```bash
php artisan migrate
```

5. **Popular banco de dados com dados iniciais**
```bash
php artisan db:seed
```

6. **Instalar dependências do NPM**
```bash
npm install
```

7. **Compilar assets**
```bash
npm run dev
```

8. **Iniciar servidor de desenvolvimento**
```bash
php artisan serve
```

## Estrutura do Projeto

### Models
- `User` - Usuários do sistema
- `Cliente` - Clientes (PF/PJ)
- `Processo` - Processos judiciais
- `Advogado` - Advogados
- `Vara` - Varas judiciais
- `Especialidade` - Especialidades jurídicas
- `Prazo` - Prazos judiciais
- `Audiencia` - Audiências
- `Documento` - Documentos
- `Notificacao` - Notificações
- `Tarefa` - Tarefas/Agenda
- `ContaReceber` - Contas a receber
- `ContaPagar` - Contas a pagar
- `Comunicacao` - Comunicações com clientes

### Roles e Permissions
O sistema usa Spatie Permission para gerenciar roles e permissões:

- **admin** - Acesso total ao sistema
- **advogado** - Gestão de processos, clientes, documentos
- **recepcionista** - Atendimento e recepção
- **tesoureiro** - Gestão financeira
- **cliente** - Acesso limitado ao próprio processo

### Rotas

#### Admin
- `/admin` - Dashboard administrativo
- `/admin/processos` - Gestão de processos
- `/admin/clientes` - Gestão de clientes
- `/admin/advogados` - Gestão de advogados
- `/admin/funcionarios` - Gestão de funcionários

#### Advogado
- `/advogado` - Dashboard do advogado
- `/advogado/processos` - Processos do advogado
- `/advogado/clientes` - Clientes do advogado
- `/advogado/audiencias` - Audiências
- `/advogado/agenda` - Agenda/Tarefas

#### Recepção
- `/recepcao` - Dashboard da recepção
- `/recepcao/processos` - Processos
- `/recepcao/pagamentos` - Pagamentos
- `/recepcao/receber` - Contas a receber
- `/recepcao/pagar` - Contas a pagar

#### Cliente
- `/cliente` - Dashboard do cliente
- `/cliente/processos` - Processos do cliente
- `/cliente/documentos` - Documentos
- `/cliente/comunicacoes` - Comunicações

## Migração de Dados

Para migrar dados do sistema antigo:

1. Fazer backup do banco de dados atual
2. Executar migrations do Laravel
3. Executar o seeder de migração:
```bash
php artisan db:seed --class=DadosMigracaoSeeder
```

**Nota:** O seeder de migração assume que as tabelas antigas ainda existem. Ajuste conforme necessário.

## Desenvolvimento

### Comandos Artisan Úteis

```bash
# Criar migration
php artisan make:migration create_nome_tabela_table

# Criar model
php artisan make:model NomeModel

# Criar controller
php artisan make:controller NomeController

# Criar seeder
php artisan make:seeder NomeSeeder

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Otimizar
php artisan optimize
```

### Estrutura de Pastas

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Advogado/
│   │   ├── Cliente/
│   │   └── Recepcao/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Services/
├── Jobs/
├── Events/
└── Listeners/

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── admin/
│   ├── advogado/
│   ├── cliente/
│   └── recepcao/
├── js/
└── css/

routes/
├── web.php
├── api.php
├── admin.php
├── advogado.php
├── recepcao.php
└── cliente.php
```

## Próximos Passos

1. ✅ Fase 1: Setup e Estrutura Base Laravel
2. ✅ Fase 2: Refatoração do Banco de Dados
3. ⏳ Fase 3: Frontend - Bootstrap 5 e JS Moderno
4. ⏳ Fase 4: Funcionalidades Core (MVP)
5. ⏳ Fase 5: Gestão de Prazos e Calendário
6. ⏳ Fase 6: Sistema de Notificações
7. ⏳ Fase 7: Automação de Petições e Documentos
8. ⏳ Fase 8: Gestão Financeira
9. ⏳ Fase 9: Integração com APIs de Tribunais
10. ⏳ Fase 10: Área do Cliente
11. ⏳ Fase 11: Gestão de Equipe e Produtividade
12. ⏳ Fase 12: Busca e Jurisprudência
13. ⏳ Fase 13: Testes e Qualidade
14. ⏳ Fase 14: Deploy e Produção

## Credenciais Padrão

- **Admin:** admin@advocacia.com / 123456
- **Advogado:** advogado@advocacia.com / 123456
- **Recepcionista:** recepcao@advocacia.com / 123456
- **Tesoureiro:** tesoureiro@advocacia.com / 123456

**Importante:** Altere as senhas após o primeiro acesso!

## Suporte

Para dúvidas ou problemas, consulte a documentação do Laravel ou entre em contato com a equipe de desenvolvimento.

