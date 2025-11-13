# 🔧 Como Corrigir Problemas Identificados pelo Diagnóstico

Quando o script `diagnosticar-erros.php` identifica problemas, você pode corrigi-los automaticamente usando o script de correção.

## 🚀 Executando a Correção

### Passo 1: Execute o Diagnóstico

Primeiro, identifique os problemas:

```bash
php scripts/diagnosticar-erros.php
```

### Passo 2: Execute a Correção

Depois, execute o script de correção:

```bash
php scripts/corrigir-problemas.php
```

## ✅ O que o Script Corrige

O script `corrigir-problemas.php` corrige automaticamente:

1. **Pasta `storage/framework/sessions`**
   - Cria a pasta se não existir
   - Define permissões corretas (775)

2. **Tabela `users`**
   - Cria a tabela com a estrutura completa
   - Inclui todos os campos: id, name, email, password, cpf, telefone, ativo, etc.
   - Cria índices e constraints necessários

3. **Tabela `advogados`**
   - Cria a tabela com a estrutura completa
   - Inclui foreign key para `users`
   - Cria índices e constraints necessários

## 📋 Exemplo de Execução

```bash
hotel631@hotelalphavilleguaxupe.com.br [~/adv.joelsouza.com.br]# php scripts/corrigir-problemas.php

========================================
  CORREÇÃO DE PROBLEMAS - Sistema Advocacia
========================================

1. Criando pasta storage/framework/sessions...
   ✅ Pasta criada com sucesso

2. Verificando tabela 'users'...
   ⚠️  Tabela 'users' não existe. Criando...
   ✅ Tabela 'users' criada com sucesso

3. Verificando tabela 'advogados'...
   ⚠️  Tabela 'advogados' não existe. Criando...
   ✅ Tabela 'advogados' criada com sucesso

========================================
  RESUMO DAS CORREÇÕES
========================================

✅ Correções aplicadas: 3
   • Pasta storage/framework/sessions criada
   • Tabela 'users' criada
   • Tabela 'advogados' criada

✅ Todas as correções foram aplicadas com sucesso!

📋 Próximos passos:
   1. Execute o diagnóstico novamente: php scripts/diagnosticar-erros.php
   2. Verifique se todos os problemas foram resolvidos
```

## 🔍 Verificando Após a Correção

Após executar o script de correção, execute o diagnóstico novamente para confirmar:

```bash
php scripts/diagnosticar-erros.php
```

Todos os erros devem estar resolvidos!

## ⚠️ Notas Importantes

- O script **não modifica** tabelas ou pastas que já existem
- O script **apenas cria** o que está faltando
- É seguro executar múltiplas vezes
- Não requer Artisan ou acesso root

## 🆘 Se Ainda Houver Problemas

Se após executar o script de correção ainda houver erros:

1. Verifique as permissões do banco de dados
2. Verifique se o usuário do banco tem permissão para criar tabelas
3. Verifique os logs do script para mensagens de erro específicas
4. Execute o diagnóstico novamente para ver detalhes

## 📚 Scripts Relacionados

- `scripts/diagnosticar-erros.php` - Identifica problemas
- `scripts/corrigir-problemas.php` - Corrige problemas automaticamente
- `scripts/verificar-servidor.php` - Verificação rápida

