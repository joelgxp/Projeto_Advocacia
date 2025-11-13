<!-- bd558542-98a8-4743-964d-87184fa2a6f1 4dc0b47f-d934-4494-9413-bf851cc57b87 -->
# Migração Completa: Laravel 10 → CodeIgniter 3 (MapOS)

## Objetivo

Migrar completamente o sistema de advocacia de Laravel 10 para CodeIgniter 3, seguindo a arquitetura, estrutura e padrões do MapOS que funciona no servidor online.

## Estrutura do Projeto CodeIgniter 3

### Estrutura de Diretórios

```
/
├── application/
│   ├── config/          # Configurações (database, routes, etc.)
│   ├── controllers/     # Controllers (Admin, Advogado, Recepcao, Cliente)
│   ├── models/          # Models (Processo_model, Cliente_model, etc.)
│   ├── views/           # Views organizadas por módulo
│   ├── core/            # MY_Controller.php (controller base)
│   ├── libraries/       # Bibliotecas customizadas (Permission, etc.)
│   ├── helpers/         # Helpers customizados
│   ├── hooks/           # Hooks do CodeIgniter
│   └── language/        # Traduções (pt-br)
├── assets/              # CSS, JS, imagens, anexos
├── system/              # Core do CodeIgniter 3
├── index.php            # Front controller
├── .htaccess            # Configuração Apache
└── composer.json        # Dependências PHP
```

## Fases da Migração

### Fase 1: Estrutura Base do CodeIgniter 3

1. Instalar CodeIgniter 3.1.13
2. Criar estrutura de diretórios (`application/`, `assets/`)
3. Configurar `application/config/config.php` (base_url, encryption_key, etc.)
4. Configurar `application/config/database.php` (conexão MySQL)
5. Configurar `application/config/routes.php` (rotas principais)
6. Criar `application/core/MY_Controller.php` (controller base com autenticação)
7. Configurar `.htaccess` na raiz e em `application/`

### Fase 2: Sistema de Autenticação e Permissões

1. Criar tabela `usuarios` (adaptar de `users` do Laravel)
2. Criar tabela `permissoes` (grupos de permissões)
3. Criar `application/libraries/Permission.php` (biblioteca de permissões)
4. Implementar autenticação em `MY_Controller`
5. Criar controller `Login` para autenticação
6. Adaptar sistema de roles (admin, advogado, recepcao, cliente)

### Fase 3: Migração de Models

1. Criar `application/models/Processo_model.php`
2. Criar `application/models/Cliente_model.php`
3. Criar `application/models/Advogado_model.php`
4. Criar `application/models/Usuario_model.php`
5. Criar `application/models/Vara_model.php`
6. Criar `application/models/Especialidade_model.php`
7. Criar `application/models/Prazo_model.php`
8. Criar `application/models/Audiencia_model.php`
9. Criar `application/models/Documento_model.php`
10. Adaptar métodos CRUD para Query Builder do CodeIgniter

### Fase 4: Migração de Controllers

1. **Admin:**

   - `Admin.php` (dashboard)
   - `Clientes.php`
   - `Advogados.php`
   - `Processos.php`
   - `Varas.php`
   - `Especialidades.php`
   - `Cargos.php`
   - `Funcionarios.php`
   - `Consulta_processual.php`

2. **Advogado:**

   - `Advogado.php` (dashboard)
   - `Processos.php`
   - `Clientes.php`
   - `Audiencias.php`
   - `Agenda.php`
   - `Documentos.php`

3. **Recepcao:**

   - `Recepcao.php` (dashboard)
   - `Processos.php`
   - `Clientes.php`
   - `Audiencias.php`
   - `Movimentacoes.php`
   - `Pagamentos.php`

4. **Cliente:**

   - `Cliente.php` (dashboard)
   - `Processos.php`
   - `Documentos.php`
   - `Comunicacoes.php`

5. **Auth:**

   - `Login.php`

### Fase 5: Migração de Views

1. Criar estrutura de views em `application/views/`
2. Criar templates base (`tema/topo.php`, `tema/menu.php`, `tema/conteudo.php`, `tema/rodape.php`)
3. Migrar views de admin (`admin/processos/`, `admin/clientes/`, etc.)
4. Migrar views de advogado (`advogado/processos/`, etc.)
5. Migrar views de recepcao (`recepcao/processos/`, etc.)
6. Migrar views de cliente (`cliente/processos/`, etc.)
7. Migrar views de autenticação (`auth/login.php`)
8. Adaptar Blade para PHP puro (sem @extends, usar include/require)

### Fase 6: Rotas e Navegação

1. Configurar `application/config/routes.php` com rotas principais
2. Implementar rotas por módulo (admin, advogado, recepcao, cliente)
3. Criar menus dinâmicos baseados em permissões
4. Adaptar sistema de rotas nomeadas do Laravel para CodeIgniter

### Fase 7: Assets e Frontend

1. Mover `public/css/` para `assets/css/`
2. Mover `public/js/` para `assets/js/`
3. Mover `public/img/` para `assets/img/`
4. Criar `assets/anexos/` para documentos
5. Adaptar links de assets nas views (usar `base_url('assets/...')`)
6. Manter Bootstrap 5, Font Awesome, jQuery (já localizados)

### Fase 8: Banco de Dados

1. Adaptar migrations do Laravel para estrutura CodeIgniter
2. Criar tabela `sessions` para sessões em banco
3. Verificar e adaptar estrutura de tabelas existentes
4. Criar seeders se necessário
5. Configurar sessões em banco de dados

### Fase 9: Funcionalidades Específicas

1. **Consulta Processual:** Adaptar `ConsultaProcessualService` para helper ou library
2. **API:** Criar controllers de API em `application/controllers/api/v1/`
3. **Notificações:** Adaptar sistema de notificações
4. **Relatórios:** Implementar geração de PDFs (se necessário)

### Fase 10: Configurações e Deploy

1. Configurar `.env` (adaptar variáveis do MapOS)
2. Configurar `application/config/config.php` com todas as opções
3. Configurar segurança (CSRF, XSS, sessões)
4. Testar localmente
5. Preparar para deploy no servidor

## Arquivos Principais a Criar/Modificar

### Configuração

- `application/config/config.php` - Configurações gerais
- `application/config/database.php` - Conexão banco
- `application/config/routes.php` - Rotas
- `application/config/autoload.php` - Autoload de libraries/helpers
- `.env` - Variáveis de ambiente (adaptar do MapOS)

### Core

- `application/core/MY_Controller.php` - Controller base com autenticação
- `index.php` - Front controller

### Libraries

- `application/libraries/Permission.php` - Sistema de permissões
- `application/libraries/Consulta_processual.php` - Helper consulta processual

### Models (exemplos)

- `application/models/Processo_model.php`
- `application/models/Cliente_model.php`
- `application/models/Usuario_model.php`
- ... (todos os models)

### Controllers (exemplos)

- `application/controllers/Login.php`
- `application/controllers/Admin.php`
- `application/controllers/Advogado.php`
- ... (todos os controllers)

### Views

- `application/views/tema/topo.php`
- `application/views/tema/menu.php`
- `application/views/tema/conteudo.php`
- `application/views/tema/rodape.php`
- `application/views/auth/login.php`
- ... (todas as views)

## Adaptações Necessárias

### Laravel → CodeIgniter 3

- `Route::get()` → `routes.php` ou métodos do controller
- `Auth::user()` → `$this->session->userdata('usuario')`
- `Model::find()` → `$this->Model_model->getById()`
- `Model::create()` → `$this->Model_model->add()`
- `Model::update()` → `$this->Model_model->edit()`
- `Model::delete()` → `$this->Model_model->delete()`
- `@extends/@yield` → `include/require` com variáveis
- `asset()` → `base_url('assets/...')`
- `route('name')` → `base_url('controller/method')`
- Eloquent ORM → Query Builder do CodeIgniter
- Middleware → Hooks ou verificação em MY_Controller

## Dependências Composer

- CodeIgniter 3.1.13
- Manter dependências úteis (Guzzle para API CNJ, etc.)
- Remover dependências específicas do Laravel

## Observações Importantes

- CodeIgniter 3 é legado, mas funciona bem em servidores compartilhados
- Estrutura mais simples que Laravel
- Query Builder protege contra SQL Injection
- Sistema de sessões em banco funciona bem
- Padrão MVC mais explícito

## Ordem de Execução Recomendada

1. Fase 1 (Estrutura Base)
2. Fase 2 (Autenticação)
3. Fase 3 (Models)
4. Fase 4 (Controllers)
5. Fase 5 (Views)
6. Fase 6 (Rotas)
7. Fase 7 (Assets)
8. Fase 8 (Banco de Dados)
9. Fase 9 (Funcionalidades)
10. Fase 10 (Configurações)