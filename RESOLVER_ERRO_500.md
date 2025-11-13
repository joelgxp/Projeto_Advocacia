# 🔧 Resolver Erro 500 no Login

## ❌ Problema
Erro 500 ao tentar fazer login: `POST https://adv.joelsouza.com.br/login/processar net::ERR_HTTP_RESPONSE_CODE_FAILURE 500`

## ✅ Correções Aplicadas

### 1. **Model Usuario_model - Método verificarLogin**
- ✅ Adicionado `try/catch` para capturar erros
- ✅ Verificação se campo `ativo` existe antes de usar
- ✅ Verificação se campo `senha` existe e não está vazio
- ✅ Logs de erro para debug

### 2. **Controller Login - Método processar**
- ✅ Verificação de todas as propriedades do usuário antes de usar
- ✅ Validação de `usuario_id` antes de criar sessão
- ✅ Tratamento de exceções com logs

### 3. **Scripts de Diagnóstico**
- ✅ `scripts/testar-login-detalhado.php` - Teste completo
- ✅ `scripts/verificar-erro-500.php` - Diagnóstico rápido
- ✅ `scripts/capturar-erro-500.php` - Captura de erros em tempo real

## 📋 Passos para Resolver

### Passo 1: Fazer Deploy
Faça commit e push das alterações:
```bash
git add .
git commit -m "Correção: Tratamento de erros no login"
git push
```

### Passo 2: Executar Diagnóstico no Servidor
```bash
cd /home2/hotel631/adv.joelsouza.com.br
php scripts/testar-login-detalhado.php
```

Este script vai verificar:
- ✅ Estrutura da tabela `usuarios`
- ✅ Usuários existentes e seus campos
- ✅ Grupos de permissões
- ✅ Logs de erro recentes
- ✅ Permissões de diretórios

### Passo 3: Verificar Logs
```bash
tail -50 application/logs/log-$(date +%Y-%m-%d).php
```

### Passo 4: Criar Dados Iniciais (se necessário)
Se faltarem grupos de permissões:
```bash
mysql -u hotel631_joeladv -p hotel631_advocacia < scripts/criar-dados-iniciais.sql
```

### Passo 5: Verificar Estrutura do Banco
Execute no MySQL:
```sql
DESCRIBE usuarios;
DESCRIBE permissoes;
SELECT * FROM usuarios LIMIT 1;
SELECT * FROM permissoes LIMIT 1;
```

## 🔍 Possíveis Causas do Erro 500

### 1. Campo `senha` vazio ou NULL
**Solução**: Verificar se usuários têm senha hash:
```sql
SELECT id, email, senha IS NULL as sem_senha FROM usuarios;
```

### 2. Campo `ativo` não existe
**Solução**: Adicionar campo ou remover verificação:
```sql
ALTER TABLE usuarios ADD COLUMN ativo TINYINT(1) DEFAULT 1;
```

### 3. Campo `permissoes_id` não existe
**Solução**: Adicionar campo:
```sql
ALTER TABLE usuarios ADD COLUMN permissoes_id INT(11) DEFAULT NULL;
```

### 4. Tabela `permissoes` não existe
**Solução**: Executar script SQL:
```bash
mysql -u hotel631_joeladv -p hotel631_advocacia < scripts/criar-dados-iniciais.sql
```

### 5. Erro de propriedade dinâmica (PHP 8.2+)
**Solução**: Verificar se `index.php` está suprimindo warnings:
```php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
```

## 📝 Próximos Passos

1. ✅ Deploy das correções
2. ⏳ Executar diagnóstico no servidor
3. ⏳ Verificar logs de erro
4. ⏳ Corrigir estrutura do banco se necessário
5. ⏳ Testar login novamente

## 💡 Dica

Se o erro persistir, acesse via navegador:
```
https://adv.joelsouza.com.br/scripts/capturar-erro-500.php
```

Isso vai mostrar o erro completo em tempo real.

