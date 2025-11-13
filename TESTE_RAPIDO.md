# ⚡ Teste Rápido do Servidor

Guia rápido para testar se o servidor está funcionando.

---

## 🚀 Teste em 3 Passos (2 minutos)

### 1️⃣ Executar Script de Teste

```bash
php testar-servidor.php
```

**Resultado esperado:**
```
✅ Sistema testado com sucesso!
```

### 2️⃣ Acessar no Navegador

Acesse: `https://seudominio.com.br`

**Verificar:**
- ✅ Página carrega (não mostra erro 500)
- ✅ Mostra página de login
- ✅ Abra F12 → Console: sem erros vermelhos

### 3️⃣ Testar Login

1. Faça login com: `admin@sistema.com` / `password`
2. Verifique se redireciona para o dashboard
3. Verifique se o menu lateral aparece

**Se tudo funcionar: ✅ Sistema OK!**

---

## 🔍 Teste Detalhado (5 minutos)

### 1. Verificar Assets (CSS/JS)

Abra F12 → Network → Recarregue a página

**Verifique se carregam (Status 200):**
- `/css/vendor/bootstrap.min.css`
- `/css/vendor/fontawesome.min.css`
- `/js/vendor/jquery.min.js`
- `/js/vendor/bootstrap.bundle.min.js`

**Se houver 404:**
- Verifique se `public/css/vendor/` existe
- Verifique DocumentRoot aponta para `public/`

### 2. Testar Funcionalidades

- ✅ Dashboard carrega
- ✅ Menu lateral funciona
- ✅ Navegação entre páginas funciona
- ✅ Criar/editar registros funciona

### 3. Verificar Logs

```bash
tail -20 storage/logs/laravel.log
```

**Verificar:**
- Sem erros críticos
- Sem "View not found"
- Sem erros de banco

---

## 🐛 Problemas Comuns

### Erro 500
```bash
# Limpar cache
rm -f bootstrap/cache/*.php

# Verificar permissões
chmod -R 775 storage bootstrap/cache
```

### Assets 404
- Verifique `public/css/vendor/` existe
- Verifique DocumentRoot = `public/`

### Erro de Login
- Verifique banco de dados tem usuários
- Verifique credenciais no banco

---

## ✅ Checklist Rápido

- [ ] `php testar-servidor.php` → OK
- [ ] Site carrega no navegador
- [ ] Assets carregam (F12 → Network)
- [ ] Login funciona
- [ ] Dashboard carrega
- [ ] Sem erros no console (F12)

**Tudo OK? Sistema funcionando! 🎉**

