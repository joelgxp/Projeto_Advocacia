# 🔧 Comandos Sem Artisan - Guia de Referência

Como o sistema não usa artisan no servidor, aqui estão as alternativas para os comandos comuns:

## 🔑 Gerar APP_KEY

**Com Artisan:**
```bash
php artisan key:generate
```

**Sem Artisan:**
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```
Cole o resultado no `.env` na linha `APP_KEY=`

---

## 🗄️ Executar Migrations

**Com Artisan:**
```bash
php artisan migrate
```

**Sem Artisan:**
```bash
# Importe o SQL diretamente via phpMyAdmin
# Ou via terminal:
mysql -u usuario -p nome_banco < database/sql/advocacia.sql
```

---

## 🧹 Limpar Cache

**Com Artisan:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

**Sem Artisan:**
```bash
# Limpar cache de configuração
rm -f bootstrap/cache/*.php

# Limpar cache de views
rm -rf storage/framework/views/*

# Limpar cache de aplicação (se usar database)
# Via phpMyAdmin: DELETE FROM cache;
```

---

## 💾 Gerar Cache

**Com Artisan:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Sem Artisan:**
```bash
# O Laravel gera cache automaticamente quando necessário
# Se precisar forçar, limpe primeiro e depois acesse o site
rm -f bootstrap/cache/*.php
# O cache será regenerado no próximo acesso
```

---

## 👤 Alterar Senha de Usuário

**Com Artisan (Tinker):**
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'admin@sistema.com')->first();
>>> $user->password = Hash::make('nova_senha');
>>> $user->save();
```

**Sem Artisan:**
```sql
-- Via phpMyAdmin ou SQL direto
UPDATE users 
SET password = '$2y$10$...' -- Hash bcrypt da nova senha
WHERE email = 'admin@sistema.com';
```

**Gerar hash bcrypt:**
- Use um gerador online: https://bcrypt-generator.com/
- Ou via PHP: `php -r "echo password_hash('nova_senha', PASSWORD_BCRYPT);"`

---

## 📊 Verificar Banco de Dados

**Com Artisan (Tinker):**
```bash
php artisan tinker
>>> DB::table('users')->count();
```

**Sem Artisan:**
```sql
-- Via phpMyAdmin ou SQL direto
SELECT COUNT(*) as total_users FROM users;
SHOW TABLES;
```

---

## 🔍 Verificar Rotas

**Com Artisan:**
```bash
php artisan route:list
```

**Sem Artisan:**
- Verifique os arquivos em `routes/`
- Ou acesse as rotas diretamente no navegador

---

## 📝 Verificar Configuração

**Com Artisan:**
```bash
php artisan config:show
```

**Sem Artisan:**
- Verifique o arquivo `.env`
- Ou os arquivos em `config/`

---

## 🗑️ Limpar Tudo

**Com Artisan:**
```bash
php artisan optimize:clear
```

**Sem Artisan:**
```bash
# Limpar todos os caches
rm -f bootstrap/cache/*.php
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*

# Limpar logs (opcional)
rm -f storage/logs/*.log
```

---

## ✅ Verificar Sistema

**Com Artisan:**
```bash
php artisan about
```

**Sem Artisan:**
```bash
# Execute o script de verificação
php verificar-servidor.php
```

---

## 📚 Resumo

| Comando Artisan | Alternativa Sem Artisan |
|----------------|------------------------|
| `php artisan key:generate` | `php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"` |
| `php artisan migrate` | Importar SQL via phpMyAdmin |
| `php artisan config:clear` | `rm -f bootstrap/cache/*.php` |
| `php artisan cache:clear` | `DELETE FROM cache;` (via SQL) |
| `php artisan tinker` | phpMyAdmin ou SQL direto |
| `php artisan route:list` | Ver arquivos em `routes/` |

---

## 💡 Dicas

1. **Cache**: O Laravel regenera cache automaticamente quando necessário
2. **Migrations**: Sempre importe o SQL completo via phpMyAdmin
3. **Senhas**: Use geradores online de hash bcrypt para alterar senhas
4. **Logs**: Acesse via File Manager: `storage/logs/laravel.log`
5. **Verificação**: Use `php verificar-servidor.php` para diagnóstico completo

