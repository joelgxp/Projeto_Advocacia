# 🔧 Resolver Problema de Login Online

## ❌ Problema
O login não está funcionando no servidor online.

## 🔍 Diagnóstico

Execute no servidor:
```bash
php scripts/diagnosticar-login-online.php
```

Este script verifica:
1. ✅ Estrutura da tabela `usuarios`
2. ✅ Campos existentes (`usuario`, `email`, `nivel`, `senha`, `ativo`)
3. ✅ Usuários cadastrados e seus dados
4. ✅ Tipo de hash de senha (bcrypt ou MD5)
5. ✅ Tabela de sessões `ci_sessions`
6. ✅ Logs de erro recentes
7. ✅ Permissões de diretórios

## ✅ Correções Aplicadas

### 1. **Logs de Debug Adicionados**
- Log de POST recebido
- Log de validação
- Log de tentativa de login
- Log de resultado da verificação
- Log de criação de sessão

### 2. **Nível de Log Ajustado**
- Produção: Warnings e Errors (nível 2)
- Desenvolvimento: Tudo (nível 4)

## 📋 Checklist de Verificação

### 1. Verificar Estrutura do Banco
```sql
DESCRIBE usuarios;
```

Deve ter:
- ✅ `usuario` (varchar) - campo principal
- ✅ `senha` (varchar) - hash da senha
- ✅ `nivel` (enum) - 'admin', 'Advogado', etc.
- ✅ `ativo` (tinyint) - 1 para ativo

### 2. Verificar Usuários
```sql
SELECT id, nome, usuario, nivel, ativo, 
       CASE WHEN senha IS NULL THEN 'SEM SENHA'
            WHEN LENGTH(senha) < 20 THEN 'MD5'
            ELSE 'bcrypt' END as tipo_senha
FROM usuarios;
```

### 3. Criar Usuário de Teste
```sql
-- Senha: admin123
INSERT INTO usuarios (nome, usuario, senha, nivel, ativo, data_cadastro) 
VALUES (
    'Admin Teste', 
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    1, 
    NOW()
);
```

### 4. Verificar Tabela de Sessões
```sql
SHOW TABLES LIKE 'ci_sessions';
```

Se não existir:
```sql
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 5. Verificar Logs
```bash
tail -50 application/logs/log-$(date +%Y-%m-%d).php
```

Procure por:
- Erros de conexão com banco
- Erros de validação
- Erros de sessão
- Mensagens de debug do login

### 6. Verificar Permissões
```bash
chmod 755 application/logs
chmod 755 application/cache
chmod 755 application/sessions
```

## 🔧 Possíveis Problemas e Soluções

### Problema 1: Campo `usuario` vazio ou NULL
**Solução:**
```sql
UPDATE usuarios SET usuario = email WHERE usuario IS NULL OR usuario = '';
```

### Problema 2: Senha em MD5
**Solução:** O sistema já suporta MD5 e atualiza automaticamente para bcrypt no primeiro login.

### Problema 3: Usuário inativo
**Solução:**
```sql
UPDATE usuarios SET ativo = 1 WHERE id = 1;
```

### Problema 4: Nível não configurado
**Solução:**
```sql
UPDATE usuarios SET nivel = 'admin' WHERE nivel IS NULL;
```

### Problema 5: Tabela de sessões não existe
**Solução:** Execute o SQL do passo 4 acima.

### Problema 6: Permissões de diretório
**Solução:**
```bash
chmod -R 755 application/logs application/cache application/sessions
```

## 🚀 Próximos Passos

1. ✅ Execute o script de diagnóstico
2. ⏳ Verifique os logs de erro
3. ⏳ Corrija os problemas identificados
4. ⏳ Teste o login novamente

## 💡 Dica

Se o problema persistir, ative logs mais detalhados temporariamente:

No `.env`:
```env
APP_ENVIRONMENT=development
```

Isso vai mostrar erros completos na tela e nos logs.

