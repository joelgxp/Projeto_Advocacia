# 🔍 Diagnóstico de Erro 500 no Login

## ❌ Erro: HTTP 500 (Internal Server Error)

O erro 500 indica um problema interno no servidor. Possíveis causas:

## 🔍 Possíveis Causas

### 1. Tabela `permissoes` não existe
O controller tenta buscar permissões do usuário, mas a tabela pode não existir.

### 2. Usuário sem `permissoes_id`
O usuário pode não ter um grupo de permissões associado.

### 3. Erro ao carregar models
Os models podem não estar sendo carregados corretamente.

### 4. Erro de conexão com banco
A conexão pode estar falhando silenciosamente.

## ✅ Correções Aplicadas

### 1. Tratamento de Erros no Login
- Adicionado `try/catch` no método `processar()`
- Logs de erro para debug
- Redirecionamento seguro em caso de erro

### 2. Redirecionamento Mais Seguro
- Verifica se `permissoes_id` existe
- Verifica se permissões foram encontradas
- Redireciona para dashboard genérico se houver problema

### 3. MY_Controller Mais Robusto
- Verifica se tabela `configuracoes` existe antes de carregar
- Tratamento de erros ao carregar biblioteca Permission

## 📋 Verificar no Servidor

Execute no servidor:

```bash
cd /home2/hotel631/adv.joelsouza.com.br
php scripts/verificar-erro-500.php
```

Este script vai verificar:
- Logs de erro recentes
- Tabelas do banco de dados
- Usuários e seus `permissoes_id`
- Grupos de permissões existentes

## 🔧 Solução Rápida

Se o problema for falta de grupos de permissões, crie manualmente no banco:

```sql
-- Criar grupo Admin
INSERT INTO permissoes (nome, permissoes, situacao) 
VALUES ('Admin', 'a:1:{s:12:"admin.access";s:1:"1";}', 1);

-- Atualizar usuário para usar grupo Admin (ID 1)
UPDATE usuarios SET permissoes_id = 1 WHERE id = 1;
```

## 📝 Próximos Passos

1. Execute o script de diagnóstico
2. Verifique os logs em `application/logs/`
3. Confirme se as tabelas existem
4. Verifique se os usuários têm `permissoes_id` configurado

