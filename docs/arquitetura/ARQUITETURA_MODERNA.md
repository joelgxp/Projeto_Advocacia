# 🏗️ Arquitetura Moderna - Sistema de Advocacia

## 📋 Visão Geral

A arquitetura foi reorganizada seguindo as **melhores práticas do Laravel** e padrões modernos de desenvolvimento, incluindo:

- ✅ **Repository Pattern** (abstração de dados)
- ✅ **Service Layer** (lógica de negócio)
- ✅ **Form Requests** (validação)
- ✅ **API Resources** (transformação de dados)
- ✅ **Enums** (tipos enumerados)
- ✅ **Policies** (autorização)
- ✅ **Events/Listeners** (eventos)
- ✅ **Jobs** (tarefas assíncronas)

## 📁 Estrutura de Pastas

```
app/
├── Console/              # Comandos Artisan
├── Events/               # Eventos do sistema
├── Exceptions/           # Tratamento de exceções
├── Http/
│   ├── Controllers/      # Controladores (thin controllers)
│   │   ├── Admin/
│   │   ├── Advogado/
│   │   ├── Recepcao/
│   │   ├── Cliente/
│   │   └── Api/
│   ├── Middleware/       # Middleware customizado
│   ├── Requests/         # ✨ Form Requests (validação)
│   │   └── Processo/
│   └── Resources/        # ✨ API Resources (transformação)
├── Jobs/                 # ✨ Jobs (tarefas assíncronas)
├── Listeners/            # ✨ Listeners (ouvintes de eventos)
├── Mail/                 # ✨ Classes de email
├── Models/               # Modelos Eloquent
├── Notifications/        # ✨ Notificações
├── Policies/             # ✨ Policies (autorização)
├── Providers/            # Service Providers
│   └── RepositoryServiceProvider.php  # ✨ Bind de repositories
├── Repositories/         # ✨ Repository Pattern
│   ├── Contracts/        # Interfaces dos repositories
│   └── ProcessoRepository.php
├── Rules/                # ✨ Regras de validação customizadas
├── Services/             # ✨ Service Layer (lógica de negócio)
│   └── ProcessoService.php
└── Enums/                # ✨ Enums (tipos enumerados)
    ├── ProcessoStatus.php
    ├── TipoPessoa.php
    └── PrazoStatus.php
```

## 🎯 Padrões Implementados

### 1. Repository Pattern

**Objetivo**: Abstrair a camada de acesso a dados

```php
// Interface
app/Repositories/Contracts/ProcessoRepositoryInterface.php

// Implementação
app/Repositories/ProcessoRepository.php
```

**Uso**:
```php
// No Service
public function __construct(
    private ProcessoRepositoryInterface $processoRepository
) {}
```

### 2. Service Layer

**Objetivo**: Centralizar a lógica de negócio

```php
app/Services/ProcessoService.php
```

**Responsabilidades**:
- Lógica de negócio
- Transações de banco
- Logs
- Validações complexas

### 3. Form Requests

**Objetivo**: Validação e autorização de requisições

```php
app/Http/Requests/Processo/StoreProcessoRequest.php
```

**Benefícios**:
- Validação centralizada
- Autorização automática
- Mensagens customizadas

### 4. API Resources

**Objetivo**: Transformar modelos em arrays JSON consistentes

```php
app/Http/Resources/ProcessoResource.php
```

**Uso**:
```php
return new ProcessoResource($processo);
// ou
return ProcessoResource::collection($processos);
```

### 5. Enums

**Objetivo**: Tipos enumerados type-safe

```php
app/Enums/ProcessoStatus.php
```

**Uso**:
```php
use App\Enums\ProcessoStatus;

$status = ProcessoStatus::ANDAMENTO;
echo $status->label(); // "Em Andamento"
echo $status->color(); // "primary"
```

## 🔄 Fluxo de Dados

```
Request → Form Request (validação) → Controller → Service → Repository → Model → Database
                                                                              ↓
Response ← API Resource (transformação) ← Controller ← Service ← Repository ←
```

## 📝 Exemplo Completo

### Controller (Thin)
```php
class ProcessoController extends Controller
{
    public function __construct(
        private ProcessoService $processoService
    ) {}

    public function store(StoreProcessoRequest $request)
    {
        $processo = $this->processoService->criarProcesso($request->validated());
        
        return new ProcessoResource($processo);
    }
}
```

### Service (Lógica de Negócio)
```php
class ProcessoService
{
    public function __construct(
        private ProcessoRepositoryInterface $processoRepository
    ) {}

    public function criarProcesso(array $data): Processo
    {
        DB::beginTransaction();
        try {
            $processo = $this->processoRepository->create($data);
            Log::info('Processo criado', ['id' => $processo->id]);
            DB::commit();
            return $processo;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

### Repository (Acesso a Dados)
```php
class ProcessoRepository implements ProcessoRepositoryInterface
{
    public function create(array $data): Processo
    {
        return Processo::create($data);
    }
}
```

## ✅ Benefícios

1. **Separação de Responsabilidades**
   - Controllers: apenas roteamento
   - Services: lógica de negócio
   - Repositories: acesso a dados

2. **Testabilidade**
   - Fácil mockar repositories
   - Testes unitários isolados
   - Testes de integração claros

3. **Manutenibilidade**
   - Código organizado
   - Fácil localizar funcionalidades
   - Mudanças isoladas

4. **Reutilização**
   - Services podem ser usados em múltiplos controllers
   - Repositories podem ser usados em services diferentes

5. **Type Safety**
   - Enums garantem valores válidos
   - Interfaces garantem contratos

## 🚀 Próximos Passos

1. ✅ Criar repositories para outros modelos
2. ✅ Criar services para outros módulos
3. ✅ Criar form requests para todas as rotas
4. ✅ Criar policies para autorização
5. ✅ Criar events/listeners para ações importantes
6. ✅ Criar jobs para tarefas assíncronas

## 📚 Referências

- [Laravel Best Practices](https://laravel.com/docs/10.x)
- [Repository Pattern](https://designpatternsphp.readthedocs.io/en/latest/More/Repository/README.html)
- [Service Layer Pattern](https://martinfowler.com/eaaCatalog/serviceLayer.html)

