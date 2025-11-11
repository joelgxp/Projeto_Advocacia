# 📁 Explicação da Pasta `src/`

## 🔍 O que é a pasta `src/`?

A pasta `src/` foi criada durante uma **tentativa anterior de modernização** do código, antes da migração completa para Laravel. Ela contém classes com namespace `Advocacia\` que foram desenvolvidas para substituir o código legado.

## 📋 Conteúdo Atual

```
src/
├── Config/
│   └── Database.php      # Classe de configuração de banco
├── Database/
│   └── Connection.php    # Classe de conexão PDO (Singleton)
└── README.md
```

## ⚠️ Problema: Não é mais necessária!

### Por que não usar mais?

1. **Laravel já tem seu próprio sistema:**
   - ✅ Laravel usa **Eloquent ORM** e **Query Builder**
   - ✅ Configurações em `config/database.php`
   - ✅ Namespace padrão é `App\`, não `Advocacia\`

2. **Código duplicado:**
   - ❌ `src/Database/Connection.php` → Laravel já tem `DB::connection()`
   - ❌ `src/Config/Database.php` → Laravel já tem `config/database.php`

3. **Não está sendo usado:**
   - ❌ Nenhum arquivo Laravel usa essas classes
   - ❌ Apenas código legado em `legacy/` pode usar

## 🎯 O que fazer?

### Opção 1: Mover para `legacy/` (Recomendado)
Manter como referência, mas não usar no Laravel.

### Opção 2: Remover completamente
Se não for mais necessário para referência.

## ✅ Solução Recomendada

**Mover `src/` para `legacy/src/`** porque:

1. ✅ Mantém histórico do projeto
2. ✅ Pode ser útil para referência durante migração
3. ✅ Não interfere no Laravel
4. ✅ Limpa a estrutura do projeto

## 📝 Classes Equivalentes no Laravel

| `src/` (Antigo) | Laravel (Atual) |
|----------------|-----------------|
| `Advocacia\Database\Connection` | `DB::connection()` ou `DB::table()` |
| `Advocacia\Config\Database` | `config('database')` |
| PDO direto | Eloquent ORM |

## 🔄 Exemplo de Migração

### Antes (src/):
```php
use Advocacia\Database\Connection;

$db = Connection::getInstance();
$result = $db->query("SELECT * FROM clientes");
```

### Depois (Laravel):
```php
use Illuminate\Support\Facades\DB;

$clientes = DB::table('clientes')->get();
// ou
$clientes = Cliente::all(); // usando Eloquent
```

## ✅ Conclusão

A pasta `src/` é **código legado** que não deve ser usado no Laravel. Deve ser movida para `legacy/` para manter a estrutura limpa e organizada.

