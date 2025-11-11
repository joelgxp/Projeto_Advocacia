<!-- c1c8a7f9-c0ed-44f5-9ce6-954baf18e2de cada3097-3487-410d-97fc-ab689738c1f9 -->
# Plano de Modernização - Sistema de Advocacia

## Visão Geral

Migração completa do sistema de PHP puro para **Laravel 11** com **PHP 8.3**, refatoração do banco de dados para estrutura moderna, atualização do frontend (Bootstrap 5 + JavaScript moderno) e implementação de todas as funcionalidades solicitadas em fases incrementais.

## Fase 1: Setup e Estrutura Base Laravel

### 1.1 Instalação e Configuração

- Instalar Laravel 11 com PHP 8.3
- Configurar ambiente (.env)
- Configurar conexão com banco de dados MySQL
- Configurar autenticação Laravel (substituir MD5 por bcrypt)
- Configurar sistema de roles/permissions (Spatie Permission ou similar)

### 1.2 Estrutura de Pastas

- Criar estrutura de módulos/packages para organizar código
- Configurar namespaces PSR-4
- Criar estrutura de APIs (para futuras integrações)

### 1.3 Migração de Autenticação

- Migrar sistema de login atual para Laravel Auth
- Implementar sistema de roles: admin, advogado, recepcionista, tesoureiro, cliente
- Criar middleware para controle de acesso por role
- Migrar dados de usuários existentes (convertendo MD5 para bcrypt)

## Fase 2: Refatoração do Banco de Dados

### 2.1 Análise e Planejamento

- Analisar estrutura atual (18 tabelas)
- Identificar relacionamentos e normalização necessária
- Planejar estrutura moderna com relacionamentos adequados

### 2.2 Novas Migrations

- **users**: Refatorar tabela usuarios (adicionar timestamps, soft deletes)
- **roles**: Tabela de roles/permissões
- **clientes**: Refatorar (adicionar campos faltantes, relacionamentos)
- **advogados**: Integrar com users, adicionar relacionamentos
- **processos**: Refatorar com relacionamentos (cliente, advogado, vara, especialidade)
- **documentos**: Refatorar com versionamento, tags, tipos
- **audiencias**: Refatorar com relacionamentos adequados
- **prazos**: Nova tabela para gestão de prazos judiciais
- **notificacoes**: Nova tabela para sistema de notificações
- **templates_peticoes**: Nova tabela para templates de petições
- **movimentacoes_processuais**: Refatorar movimentacoes
- **financeiro**: Refatorar receber/pagar/pagamentos
- **tarefas**: Refatorar tarefas/agenda
- **comunicacoes**: Nova tabela para histórico de comunicações com clientes
- **anexos_processo**: Nova tabela para anexos de processos
- Adicionar índices, foreign keys, timestamps e soft deletes em todas as tabelas

### 2.3 Seeders

- Criar seeders para dados iniciais (varas, especialidades, cargos, roles)
- Criar seeder para migração de dados existentes
- Criar factory para testes

## Fase 3: Frontend - Bootstrap 5 e JS Moderno

### 3.1 Atualização de Assets

- Instalar Bootstrap 5 via NPM/Composer
- Configurar Vite para compilação de assets
- Atualizar CSS customizado para Bootstrap 5
- Migrar JavaScript para ES6+ módulos
- Configurar Alpine.js ou Vue.js para interatividade

### 3.2 Componentes Blade

- Criar layout base moderno
- Criar componentes reutilizáveis (cards, tables, modals, forms)
- Criar partials para navegação, sidebar, footer
- Implementar sistema de notificações toast

### 3.3 Responsividade

- Garantir responsividade em todos os componentes
- Otimizar para mobile

## Fase 4: Funcionalidades Core (MVP)

### 4.1 Dashboard de Processos

- Visualização de processos ativos por usuário/role
- Cards com estatísticas (processos em andamento, prazos próximos, etc.)
- Gráficos de processos por status
- Filtros e busca avançada
- Lista de processos com paginação

### 4.2 Gestão de Processos

- CRUD completo de processos
- Vinculação com clientes, advogados, varas, especialidades
- Upload de anexos/documentos
- Histórico de movimentações
- Controle de status (Aberto, Andamento, Concluído, Arquivado, Cancelado)

### 4.3 Gestão de Clientes

- CRUD completo de clientes (PF e PJ)
- Vinculação com processos
- Histórico de comunicações
- Documentos do cliente

### 4.4 Gestão de Documentos

- Upload de documentos com versionamento
- Organização por tipos/tags
- Busca avançada de documentos
- Preview de documentos
- Download de documentos
- Histórico de versões

## Fase 5: Gestão de Prazos e Calendário

### 5.1 Sistema de Prazos

- Cadastro de prazos judiciais/administrativos
- Cálculo automático de prazos (dias úteis)
- Alertas de prazos próximos
- Dashboard de prazos

### 5.2 Calendário de Audiências

- Calendário integrado (FullCalendar ou similar)
- CRUD de audiências
- Notificações de audiências
- Filtros por advogado, cliente, processo
- Sincronização com prazos

### 5.3 Agenda/Tarefas

- Sistema de tarefas/agenda
- Vinculação com processos e clientes
- Notificações de tarefas

## Fase 6: Sistema de Notificações

### 6.1 Notificações Internas

- Sistema de notificações em tempo real (Laravel Broadcasting/Pusher)
- Notificações de prazos, audiências, movimentações
- Centro de notificações no dashboard

### 6.2 Notificações por Email

- Configurar Laravel Mail
- Templates de email
- Notificações automáticas (prazos, audiências, movimentações)
- Notificações para clientes

### 6.3 Integração WhatsApp (Opcional - Fase Futura)

- Integração com API WhatsApp (Twilio, Evolution API)
- Notificações via WhatsApp
- Configuração de templates

### 6.4 Integração SMS (Opcional - Fase Futura)

- Integração com API SMS
- Notificações via SMS

## Fase 7: Automação de Petições e Documentos

### 7.1 Sistema de Templates

- CRUD de templates de petições
- Editor de templates (WYSIWYG ou Markdown)
- Variáveis dinâmicas nos templates
- Categorização de templates

### 7.2 Geração Automática

- Sistema de preenchimento automático de dados
- Geração de petições a partir de templates
- Exportação para PDF (DomPDF ou similar)
- Histórico de petições geradas

### 7.3 Contratos

- Templates de contratos
- Geração automática de contratos
- Assinatura digital (integração futura)

## Fase 8: Gestão Financeira

### 8.1 Controle de Honorários

- Cadastro de honorários por processo
- Controle de parcelas
- Status de pagamento
- Relatórios de honorários

### 8.2 Contas a Receber

- CRUD de contas a receber
- Vinculação com processos e clientes
- Controle de pagamentos
- Relatórios financeiros

### 8.3 Contas a Pagar

- CRUD de contas a pagar
- Controle de vencimentos
- Relatórios de despesas

### 8.4 Relatórios Financeiros

- Dashboard financeiro
- Relatórios de receitas/despesas
- Relatórios por período
- Exportação para Excel/PDF

## Fase 9: Integração com APIs de Tribunais

### 9.1 Consulta Processual

- Melhorar integração existente com API CNJ (DataJud)
- Consulta de processos por número
- Importação automática de dados do processo
- Sincronização de movimentações

### 9.2 Atualização Automática

- Jobs agendados para consulta automática
- Notificações de novas movimentações
- Histórico de consultas

### 9.3 Múltiplos Tribunais

- Suporte para múltiplos tribunais
- Configuração por tribunal
- APIs específicas por tribunal (se necessário)

## Fase 10: Área do Cliente

### 10.1 Portal do Cliente

- Área restrita para clientes
- Autenticação de clientes
- Dashboard do cliente

### 10.2 Acompanhamento de Processos

- Visualização de processos do cliente
- Histórico de movimentações
- Documentos do processo
- Status do processo

### 10.3 Comunicação

- Canal de comunicação com advogado
- Envio de mensagens
- Histórico de comunicações
- Notificações para o cliente

### 10.4 Documentos

- Download de documentos
- Upload de documentos (se necessário)
- Visualização de documentos

## Fase 11: Gestão de Equipe e Produtividade

### 11.1 Gestão de Tarefas

- Sistema de tarefas por usuário
- Delegação de tarefas
- Status de tarefas
- Priorização

### 11.2 Acompanhamento de Produtividade

- Relatórios de produtividade por advogado
- Métricas de processos
- Dashboard de equipe

### 11.3 Colaboração

- Compartilhamento de documentos
- Comentários em processos
- Atribuição de responsáveis

## Fase 12: Busca e Jurisprudência (Fase Futura)

### 12.1 Busca Avançada

- Busca full-text em processos, documentos, clientes
- Filtros avançados
- Busca por tags

### 12.2 Jurisprudência

- Integração com APIs de jurisprudência
- Busca de decisões relevantes
- Armazenamento de jurisprudências favoritas

## Fase 13: Testes e Qualidade

### 13.1 Testes Unitários

- Testes para models
- Testes para controllers
- Testes para services

### 13.2 Testes de Integração

- Testes de APIs
- Testes de fluxos completos
- Testes de autenticação

### 13.3 Testes E2E (Opcional)

- Testes end-to-end com Cypress ou similar

## Fase 14: Deploy e Produção

### 14.1 Otimização

- Cache de configurações
- Cache de queries
- Otimização de assets
- Compressão de imagens

### 14.2 Segurança

- Validação de inputs
- Proteção CSRF
- Sanitização de dados
- Logs de auditoria

### 14.3 Backup

- Sistema de backup automático
- Backup de banco de dados
- Backup de arquivos

### 14.4 Monitoramento

- Logs de erros
- Monitoramento de performance
- Alertas de erros

## Estrutura de Arquivos Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Advogado/
│   │   ├── Cliente/
│   │   ├── Recepcao/
│   │   └── Api/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Models/
│   ├── User.php
│   ├── Cliente.php
│   ├── Processo.php
│   ├── Documento.php
│   ├── Audiencia.php
│   ├── Prazo.php
│   └── ...
├── Services/
│   ├── ProcessoService.php
│   ├── DocumentoService.php
│   ├── NotificacaoService.php
│   ├── ApiTribunalService.php
│   └── ...
├── Jobs/
│   ├── ConsultarProcessoTribunal.php
│   ├── EnviarNotificacao.php
│   └── ...
├── Events/
│   ├── ProcessoAtualizado.php
│   ├── PrazoProximo.php
│   └── ...
└── Listeners/
    ├── EnviarNotificacaoProcesso.php
    └── ...

database/
├── migrations/
│   ├── 2024_01_01_000001_create_users_table.php
│   ├── 2024_01_01_000002_create_clientes_table.php
│   ├── 2024_01_01_000003_create_processos_table.php
│   └── ...
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── RolesSeeder.php
│   ├── DadosMigracaoSeeder.php
│   └── ...
└── factories/
    ├── UserFactory.php
    ├── ClienteFactory.php
    └── ...

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── admin/
│   ├── advogado/
│   ├── cliente/
│   └── ...
├── js/
│   ├── app.js
│   ├── components/
│   └── ...
└── css/
    ├── app.css
    └── ...

routes/
├── web.php
├── api.php
└── channels.php
```

## Tecnologias e Pacotes

- **Laravel**: 11.x
- **PHP**: 8.3
- **MySQL**: 8.0+
- **Bootstrap**: 5.3
- **JavaScript**: ES6+ (Vanilla ou Alpine.js)
- **Vite**: Para compilação de assets
- **Spatie Permission**: Para roles e permissões
- **Laravel Horizon**: Para filas (opcional)
- **Laravel Broadcasting**: Para notificações em tempo real
- **DomPDF**: Para geração de PDFs
- **Laravel Excel**: Para exportação de planilhas
- **FullCalendar**: Para calendário
- **Intervention Image**: Para manipulação de imagens

## Ordem de Implementação Recomendada

1. **Fase 1 e 2**: Setup e banco de dados (Base)
2. **Fase 3**: Frontend moderno (UI/UX)
3. **Fase 4**: Funcionalidades core (MVP)
4. **Fase 5**: Prazos e calendário (Prioridade alta)
5. **Fase 6**: Notificações (Essencial)
6. **Fase 7**: Automação (Produtividade)
7. **Fase 8**: Financeiro (Importante)
8. **Fase 9**: APIs tribunais (Melhoria)
9. **Fase 10**: Área do cliente (Diferencial)
10. **Fase 11**: Gestão de equipe (Opcional)
11. **Fases 12-14**: Melhorias e produção

## Observações Importantes

- Migração gradual: manter sistema antigo funcionando durante migração
- Backup completo antes de iniciar migração
- Testes em ambiente de desenvolvimento primeiro
- Migração de dados com scripts de conversão
- Documentação de APIs e funcionalidades
- Treinamento de usuários nas novas funcionalidades

### To-dos

- [ ] Fase 1: Instalar Laravel 11, configurar ambiente PHP 8.3, autenticação e roles
- [ ] Fase 2: Refatorar banco de dados com migrations modernas, relacionamentos e seeders
- [ ] Fase 3: Atualizar frontend para Bootstrap 5, JS moderno e componentes Blade
- [ ] Fase 4: Implementar funcionalidades core - Dashboard, Processos, Clientes, Documentos
- [ ] Fase 5: Sistema de prazos, calendário de audiências e agenda/tarefas
- [ ] Fase 6: Sistema de notificações (internas, email, WhatsApp/SMS opcional)
- [ ] Fase 7: Automação de petições e documentos com templates
- [ ] Fase 8: Gestão financeira completa (honorários, receber, pagar, relatórios)
- [ ] Fase 9: Integração com APIs de tribunais (CNJ/DataJud) com atualização automática
- [ ] Fase 10: Área do cliente com portal, acompanhamento e comunicação
- [ ] Fase 11: Gestão de equipe, tarefas e produtividade
- [ ] Fase 12: Busca avançada e jurisprudência (fase futura)
- [ ] Fase 13: Implementar testes unitários e de integração
- [ ] Fase 14: Otimização, segurança, backup e deploy para produção