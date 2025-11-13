# 🔧 Correção: Estrutura do Banco de Dados

## ❌ Problema Identificado

O diagnóstico mostrou que a estrutura da tabela `usuarios` é diferente do esperado:

### Estrutura Real:
- ✅ `id` (int)
- ✅ `nome` (varchar)
- ✅ `usuario` (varchar) - **NÃO `email`**
- ✅ `senha` (varchar)
- ✅ `nivel` (enum: 'admin','Advogado','Cliente','Recepcionista','Tesoureiro') - **NÃO `permissoes_id`**
- ✅ `ativo` (tinyint)
- ✅ `data_cadastro` (timestamp)

### Estrutura Esperada (antiga):
- ❌ `email` (não existe)
- ❌ `permissoes_id` (não existe)

## ✅ Correções Aplicadas

### 1. **Model Usuario_model**
- ✅ Método `getByEmail()` agora busca primeiro por `usuario`, depois por `email` (compatibilidade)
- ✅ Método `verificarLogin()` aceita `usuario_input` ao invés de `email`
- ✅ Suporte para senhas MD5 antigas (compatibilidade) com atualização automática para bcrypt

### 2. **Controller Login**
- ✅ Aceita campo `usuario` no formulário (compatibilidade com `email`)
- ✅ Usa campo `nivel` da sessão para redirecionamento
- ✅ Fallback para `permissoes_id` se `nivel` não existir

### 3. **View Login**
- ✅ Campo alterado de `email` para `usuario`
- ✅ Label atualizado para "Usuário/E-mail"
- ✅ Placeholder atualizado

### 4. **Redirecionamento por Role**
- ✅ Usa campo `nivel` diretamente da tabela
- ✅ Mapeia valores: 'admin', 'Advogado', 'Cliente', 'Recepcionista', 'Tesoureiro'
- ✅ Fallback para sistema de permissões se necessário

## 📋 Próximos Passos

### 1. Criar Diretórios Necessários
Execute no servidor:
```bash
php scripts/criar-diretorios.php
```

Isso vai criar:
- `application/logs/` (com .htaccess para proteção)
- `application/cache/`
- `application/sessions/`

### 2. Fazer Deploy
```bash
git add .
git commit -m "Correção: Adaptação para estrutura real do banco (usuario/nivel)"
git push
```

### 3. Testar Login
Após o deploy, teste o login com:
- Campo: `usuario` (não `email`)
- Valor: O valor do campo `usuario` da tabela

## 🔍 Verificar Usuários

Execute no MySQL:
```sql
SELECT id, nome, usuario, nivel, ativo FROM usuarios;
```

## 💡 Nota

O sistema agora suporta:
- ✅ Estrutura antiga (com `email` e `permissoes_id`)
- ✅ Estrutura nova (com `usuario` e `nivel`)
- ✅ Senhas MD5 antigas (com atualização automática)
- ✅ Senhas bcrypt modernas

