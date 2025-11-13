# ✅ Verificação Pós-Deploy

## 📋 Checklist de Verificação

### ✅ Configuração Básica
- [x] APP_KEY configurado
- [x] Arquivos essenciais presentes
- [x] MySQL conectado
- [x] Sintaxe PHP OK
- [x] PHP executando
- [x] Permissões corretas (storage, bootstrap/cache)
- [x] Sem logs de erro

### 🔍 Verificações Adicionais Recomendadas

#### 1. Verificar se o site carrega
- Acesse: `https://seudominio.com.br`
- Deve mostrar a página de login

#### 2. Verificar Assets (CSS/JS)
Abra o DevTools (F12) → Network e verifique se carregam:
- ✅ `/css/vendor/bootstrap.min.css`
- ✅ `/css/vendor/fontawesome.min.css`
- ✅ `/css/vendor/inter-font.css`
- ✅ `/js/vendor/jquery.min.js`
- ✅ `/js/vendor/bootstrap.bundle.min.js`

**Se houver 404:**
- Verifique se `public/css/vendor/` e `public/js/vendor/` existem
- Verifique se DocumentRoot aponta para `public/`

#### 3. Testar Login
- Acesse a página de login
- Tente fazer login com credenciais padrão
- Verifique se redireciona corretamente após login

#### 4. Verificar Rotas
Teste as principais rotas:
- `/login` - Página de login
- `/dashboard` - Dashboard (após login)
- `/admin/dashboard` - Dashboard admin (se tiver permissão)

#### 5. Verificar Banco de Dados
**Via phpMyAdmin:**
- Verifique se as tabelas foram criadas
- Verifique se há dados iniciais (usuários, etc.)
- Execute: `SELECT COUNT(*) FROM users;`

**Ou via SQL direto:**
```sql
SHOW TABLES;
SELECT COUNT(*) as total_users FROM users;
```

#### 6. Limpar Cache (SEM ARTISAN)
```bash
# Limpar cache manualmente
rm -f bootstrap/cache/*.php
rm -rf storage/framework/views/*
```

#### 7. Verificar Logs
```bash
# Ver últimos logs
tail -f storage/logs/laravel.log

# Ou via painel, abra: storage/logs/laravel.log
# Ou via File Manager do cPanel
```

---

## 🎯 Próximos Passos

### 1. Alterar Senhas Padrão
⚠️ **IMPORTANTE**: Altere todas as senhas padrão em produção!

**Via phpMyAdmin:**
1. Acesse phpMyAdmin
2. Selecione o banco de dados
3. Vá na tabela `users`
4. Edite o registro do usuário
5. No campo `password`, use: `SELECT SHA2('nova_senha_forte', 256)` ou use um gerador de hash bcrypt online
6. Salve

**Ou via SQL direto:**
```sql
UPDATE users 
SET password = '$2y$10$...' -- Hash bcrypt da nova senha
WHERE email = 'admin@sistema.com';
```

### 2. Configurar HTTPS
- Certifique-se de que o site está usando HTTPS
- Configure `APP_URL=https://seudominio.com.br` no `.env`

### 3. Configurar Backup
- Configure backup automático do banco de dados
- Configure backup dos arquivos

### 4. Monitorar Performance
- Monitore os logs regularmente
- Verifique uso de recursos do servidor

---

## 🐛 Se Algo Não Estiver Funcionando

### Erro 500
```bash
# Verificar logs
tail -f storage/logs/laravel.log
# Ou via File Manager: storage/logs/laravel.log

# Verificar permissões
chmod -R 775 storage bootstrap/cache

# Limpar cache (SEM ARTISAN)
rm -f bootstrap/cache/*.php
rm -rf storage/framework/views/*
```

### Assets não carregam
- Verifique se `public/css/vendor/` existe
- Verifique se DocumentRoot aponta para `public/`
- Verifique permissões: `chmod -R 755 public/`

### Erro de banco
- Verifique credenciais no `.env`
- Teste conexão via phpMyAdmin ou cliente MySQL
- Verifique se o banco existe e as tabelas foram criadas

### Views não encontradas
- Verifique se todas as views existem em `resources/views/`
- Limpe cache: `rm -rf storage/framework/views/*`

---

## ✅ Status Atual

Baseado no diagnóstico:
- ✅ **Configuração**: OK
- ✅ **Arquivos**: OK
- ✅ **Banco de Dados**: OK
- ✅ **PHP**: OK
- ✅ **Permissões**: OK

**Sistema pronto para uso!** 🚀

---

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs: `storage/logs/laravel.log` (via File Manager ou SSH)
2. Execute: `php verificar-servidor.php` (se tiver acesso SSH)
3. Verifique permissões: `chmod -R 775 storage bootstrap/cache`
4. Limpe cache: `rm -f bootstrap/cache/*.php`
5. Verifique `.env` está configurado corretamente

