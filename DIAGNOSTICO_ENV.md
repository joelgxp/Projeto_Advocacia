# 🔍 Diagnóstico do .env no Servidor

## ✅ O .env já está correto

Se o `.env` já está configurado corretamente no servidor, mas ainda há erros, pode ser que o CodeIgniter não esteja lendo o arquivo corretamente.

## 🔧 Teste Rápido

Execute no servidor para verificar se o `.env` está sendo lido:

```bash
cd /home2/hotel631/adv.joelsouza.com.br
php scripts/testar-env.php
```

Este script vai:
- ✅ Verificar se o arquivo `.env` existe
- ✅ Ler todas as variáveis
- ✅ Testar a função `getEnvVar()` (usada no database.php)
- ✅ Testar conexão MySQL

## 📋 Verificações Manuais

### 1. Verificar se o .env está na raiz

```bash
cd /home2/hotel631/adv.joelsouza.com.br
ls -la .env
```

### 2. Verificar conteúdo do .env

```bash
cat .env | grep -E "DB_|APP_"
```

### 3. Verificar permissões

```bash
ls -la .env
# Deve ser: -rw------- (600)
```

Se não estiver, corrija:
```bash
chmod 600 .env
```

### 4. Testar leitura PHP

```bash
php -r "
\$env = file_get_contents('.env');
echo \$env;
"
```

## ⚠️ Problemas Comuns

### Problema 1: Arquivo .env não está sendo lido

**Solução:** Verifique o caminho no `database.php`. O arquivo deve estar em:
```
/home2/hotel631/adv.joelsouza.com.br/.env
```

### Problema 2: Variáveis com espaços ou aspas

O `.env` deve ter formato:
```env
DB_USERNAME=hotel631_joeladv
DB_PASSWORD=mXrnP61Gc&K$
```

**NÃO:**
```env
DB_USERNAME = hotel631_joeladv  # Espaços
DB_PASSWORD="mXrnP61Gc&K$"     # Aspas (serão removidas automaticamente)
```

### Problema 3: Caracteres especiais na senha

Se a senha tem caracteres especiais como `&`, `$`, `!`, etc., pode precisar de escape ou aspas (mas a função remove aspas automaticamente).

## 🔄 Se ainda não funcionar

1. **Verifique os logs do CodeIgniter:**
   ```bash
   tail -50 application/logs/log-*.php
   ```

2. **Teste conexão MySQL diretamente:**
   ```bash
   mysql -h localhost -u hotel631_joeladv -p'mXrnP61Gc&K$' hotel631_advocacia -e "SELECT 1;"
   ```

3. **Verifique se o PHP está lendo getenv():**
   ```bash
   php -r "echo getenv('DB_USERNAME') ?: 'não encontrado';"
   ```

## 📝 Próximos Passos

Após executar `testar-env.php`, envie o resultado para identificar o problema específico.

