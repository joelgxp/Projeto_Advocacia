# 📋 Análise de Arquitetura - Sistema de Advocacia

## 🔴 Problemas Identificados

### 1. **Código Legado Misturado com Laravel**
- ❌ Pastas `admin/`, `advogado/`, `recepcao/` na raiz (código PHP antigo)
- ❌ Laravel está em `app/`, `routes/`, `resources/` (estrutura moderna)
- ⚠️ **Conflito**: Dois sistemas rodando simultaneamente

### 2. **Arquivos de Configuração Duplicados**
- ❌ `config.php` (antigo)
- ❌ `config-avancado.php` (antigo)
- ❌ `conexao.php` (antigo)
- ✅ `config/` (Laravel - correto)
- ⚠️ **Problema**: Configurações conflitantes

### 3. **Assets Duplicados**
- ❌ `css/`, `js/`, `img/` na raiz
- ✅ `public/` e `resources/` (Laravel)
- ⚠️ **Problema**: Assets não servidos corretamente pelo Laravel

### 4. **Controllers Duplicados**
- ❌ `app/Http/Controllers/DashboardController.php` (raiz)
- ✅ `app/Http/Controllers/Admin/DashboardController.php` (correto)
- ✅ `app/Http/Controllers/Advogado/DashboardController.php` (correto)
- ⚠️ **Problema**: Controller na raiz pode causar conflitos

### 5. **Rotas Duplicadas**
- ❌ `routes/api_routes.php` (não usado)
- ✅ `routes/api.php` (Laravel padrão)
- ⚠️ **Problema**: Arquivo não utilizado

### 6. **Arquivos de Teste/Verificação na Raiz**
- ❌ `teste-*.php` (múltiplos arquivos)
- ❌ `verificar-instalacao.php`
- ❌ `exemplo-*.php`
- ⚠️ **Problema**: Poluição da raiz do projeto

### 7. **Documentação Espalhada**
- ❌ Múltiplos arquivos `.md` na raiz
- ⚠️ **Sugestão**: Mover para `docs/`

### 8. **Arquivos SQL na Raiz**
- ❌ `advocacia.sql`
- ❌ `criar-banco.sql`
- ⚠️ **Sugestão**: Mover para `database/sql/`

### 9. **Estrutura de Views Incompleta**
- ✅ `resources/views/admin/dashboard.blade.php` (criado)
- ❌ Faltam views para outros módulos
- ⚠️ **Problema**: Views antigas em `admin/`, `advogado/`, `recepcao/`

### 10. **Namespace `src/` Não Utilizado**
- ⚠️ `src/Config/`, `src/Database/` existem mas não são usados pelo Laravel
- ⚠️ **Sugestão**: Migrar para `app/` ou remover

---

## ✅ Plano de Reorganização

### Fase 1: Limpeza e Organização Imediata

#### 1.1 Mover Arquivos de Documentação
```
docs/
├── INSTALACAO.md
├── INSTALACAO_COMPLETA.md
├── README_LARAVEL.md
├── QUICK_START.md
├── CONFIGURAR_MYSQL.md
├── TESTE_SISTEMA.md
├── STATUS_INSTALACAO.md
├── RESUMO_INICIALIZACAO.md
└── IMPLEMENTACAO.md
```

#### 1.2 Mover Arquivos SQL
```
database/
├── migrations/
├── seeders/
└── sql/
    ├── advocacia.sql
    └── criar-banco.sql
```

#### 1.3 Mover Arquivos de Teste
```
tests/
├── Database/
└── legacy/  (arquivos de teste antigos)
    ├── teste-banco.php
    ├── teste-porta.php
    ├── teste-servidor.php
    └── verificar-instalacao.php
```

#### 1.4 Remover/Mover Código Legado
```
legacy/  (manter temporariamente para referência)
├── admin/
├── advogado/
├── recepcao/
├── conexao.php
├── config.php
├── config-avancado.php
├── autenticar.php
├── middleware.php
├── index.php
└── index-novo.php
```

#### 1.5 Organizar Assets
```
public/
├── css/  (mover de raiz)
├── js/   (mover de raiz)
└── img/  (mover de raiz)

resources/
├── css/
├── js/
└── views/
```

### Fase 2: Correções de Estrutura Laravel

#### 2.1 Remover Controller Duplicado
- ❌ Remover `app/Http/Controllers/DashboardController.php`
- ✅ Manter apenas os controllers em namespaces específicos

#### 2.2 Limpar Rotas
- ❌ Remover `routes/api_routes.php`
- ✅ Usar apenas `routes/api.php`

#### 2.3 Organizar Views
```
resources/views/
├── admin/
│   ├── dashboard.blade.php ✅
│   ├── clientes/
│   ├── processos/
│   └── ...
├── advogado/
│   ├── dashboard.blade.php
│   └── ...
├── recepcao/
│   ├── dashboard.blade.php
│   └── ...
└── layouts/
```

### Fase 3: Migração de Assets

#### 3.1 Mover Assets Estáticos
- Mover `css/`, `js/`, `img/` para `public/`
- Atualizar referências nas views

#### 3.2 Configurar Vite
- Garantir que `vite.config.js` está correto
- Compilar assets com `npm run dev`

### Fase 4: Limpeza Final

#### 4.1 Remover Arquivos Não Utilizados
- `exemplo-migracao.php`
- `exemplo-uso-composer.php`
- `composer.local.json` (se não usado)
- `test-laravel.php`

#### 4.2 Atualizar .gitignore
- Adicionar `legacy/` se necessário
- Garantir que arquivos temporários não sejam commitados

---

## 📁 Estrutura Final Proposta

```
Projeto_Advocacia/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Advogado/
│   │   │   ├── Recepcao/
│   │   │   ├── Cliente/
│   │   │   └── Api/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── sql/
├── docs/
├── public/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── index.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── advogado/
│       ├── recepcao/
│       └── layouts/
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── admin.php
│   ├── advogado.php
│   ├── recepcao.php
│   └── cliente.php
├── storage/
├── tests/
│   ├── Database/
│   └── legacy/
├── vendor/
├── legacy/  (temporário - código antigo)
├── .env
├── .env.example
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## 🎯 Prioridades

### 🔴 Crítico (Fazer Agora)
1. Remover controller duplicado `DashboardController.php` da raiz
2. Mover assets para `public/`
3. Criar estrutura de views completa

### 🟡 Importante (Fazer em Seguida)
1. Mover código legado para `legacy/`
2. Organizar documentação em `docs/`
3. Limpar arquivos de teste

### 🟢 Desejável (Fazer Depois)
1. Migrar código legado para Laravel
2. Remover pasta `legacy/` após migração completa
3. Otimizar estrutura final

---

## ⚠️ Avisos Importantes

1. **NÃO DELETAR** código legado imediatamente - mover para `legacy/`
2. **TESTAR** cada mudança antes de prosseguir
3. **BACKUP** antes de reorganizar
4. **ATUALIZAR** referências em arquivos após mover

---

## 📝 Checklist de Execução

- [ ] Criar pasta `docs/` e mover documentação
- [ ] Criar pasta `database/sql/` e mover SQLs
- [ ] Criar pasta `tests/legacy/` e mover testes
- [ ] Criar pasta `legacy/` e mover código antigo
- [ ] Mover assets para `public/`
- [ ] Remover controller duplicado
- [ ] Remover `routes/api_routes.php`
- [ ] Atualizar referências nos arquivos
- [ ] Testar aplicação após mudanças
- [ ] Atualizar `.gitignore`

