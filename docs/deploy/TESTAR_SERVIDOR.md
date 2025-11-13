# 🧪 Como Testar o Servidor

Guia completo para testar se o sistema está funcionando corretamente no servidor.

---

## 🔧 Método 1: Script Automatizado

### Executar o Script de Teste

```bash
php testar-servidor.php
```

O script verifica:
- ✅ Carregamento do Laravel
- ✅ Configuração do .env
- ✅ Conexão com banco de dados
- ✅ Existência de tabelas e dados
- ✅ Arquivos essenciais (CSS, JS, views)
- ✅ Permissões de pastas
- ✅ Logs de erro
- ✅ Rotas principais

### Exemplo de Saída

```
========================================
  TESTE DO SERVIDOR - Sistema Advocacia
========================================

1. Testando carregamento do Laravel...
   ✅ Laravel carregado com sucesso

2. Verificando configuração .env...
   ✅ APP_KEY configurado
   ✅ APP_ENV=production
   ✅ APP_DEBUG=false
   ✅ DB_CONNECTION configurado: mysql

3. Testando conexão com banco de dados...
   ✅ Conexão com banco OK
   ✅ Tabelas encontradas: 25
   ✅ Usuários no banco: 4

4. Verificando arquivos essenciais...
   ✅ index.php
   ✅ Bootstrap CSS
   ✅ Bootstrap JS
   ✅ Font Awesome CSS
   ✅ jQuery
   ✅ View de login
   ✅ Layout principal

5. Verificando permissões...
   ✅ storage é gravável
   ✅ bootstrap/cache é gravável

6. Testando public/index.php...
   ✅ index.php existe e é acessível

7. Verificando logs...
   ✅ Nenhum erro encontrado nos logs

8. Verificando rotas...
   ✅ Rotas web
   ✅ Rotas admin
   ✅ Rotas advogado

========================================
  RESUMO DO TESTE
========================================

✅ Sucessos: 15

✅ Sistema testado com sucesso!
```

---

## 🌐 Método 2: Teste no Navegador

### 1. Testar Página Inicial

Acesse: `https://seudominio.com.br`

**O que verificar:**
- ✅ Página carrega sem erro 500
- ✅ Mostra a página de login
- ✅ Não há erros no console (F12)

### 2. Verificar Assets (CSS/JS)

Abra o DevTools (F12) → Network e recarregue a página.

**Verifique se carregam:**
- ✅ `/css/vendor/bootstrap.min.css` (Status 200)
- ✅ `/css/vendor/fontawesome.min.css` (Status 200)
- ✅ `/css/vendor/inter-font.css` (Status 200)
- ✅ `/js/vendor/jquery.min.js` (Status 200)
- ✅ `/js/vendor/bootstrap.bundle.min.js` (Status 200)

**Se houver 404:**
- Verifique se `public/css/vendor/` existe
- Verifique se DocumentRoot aponta para `public/`
- Verifique permissões: `chmod -R 755 public/`

### 3. Testar Login

1. Acesse a página de login
2. Tente fazer login com credenciais padrão
3. Verifique se redireciona corretamente

**Credenciais padrão:**
- Admin: `admin@sistema.com` / `password`
- Advogado: `advogado@sistema.com` / `password`

### 4. Testar Dashboard

Após login, verifique:
- ✅ Dashboard carrega
- ✅ Menu lateral aparece
- ✅ Estatísticas são exibidas
- ✅ Não há erros no console

### 5. Testar Navegação

Teste os principais links:
- ✅ Dashboard
- ✅ Processos
- ✅ Clientes
- ✅ Outros menus

---

## 🔍 Método 3: Teste Manual via SSH

### 1. Testar PHP

```bash
php -v
# Deve mostrar: PHP 8.2.x ou superior
```

### 2. Testar Conexão com Banco

```bash
# Via MySQL
mysql -u usuario -p nome_banco -e "SELECT COUNT(*) FROM users;"

# Ou via PHP
php -r "
require 'vendor/autoload.php';
\$config = parse_ini_file('.env');
\$pdo = new PDO('mysql:host='.\$config['DB_HOST'].';dbname='.\$config['DB_DATABASE'], \$config['DB_USERNAME'], \$config['DB_PASSWORD']);
echo 'Conexão OK! Tabelas: ' . count(\$pdo->query('SHOW TABLES')->fetchAll()) . PHP_EOL;
"
```

### 3. Verificar Arquivos

```bash
# Verificar se arquivos vendor existem
ls -la public/css/vendor/
ls -la public/js/vendor/
ls -la public/fonts/

# Verificar views
ls -la resources/views/auth/
ls -la resources/views/layouts/
```

### 4. Verificar Permissões

```bash
# Verificar permissões
ls -ld storage
ls -ld bootstrap/cache

# Deve mostrar: drwxrwxr-x (775)
```

### 5. Verificar Logs

```bash
# Ver últimos logs
tail -20 storage/logs/laravel.log

# Verificar se há erros
grep -i error storage/logs/laravel.log | tail -10
```

---

## 🧪 Método 4: Teste de Funcionalidades

### 1. Testar CRUD de Clientes

1. Acesse: `/admin/clientes`
2. Tente criar um novo cliente
3. Verifique se salva
4. Tente editar
5. Tente excluir

### 2. Testar Processos

1. Acesse: `/admin/processos`
2. Verifique se lista processos
3. Tente criar um novo processo
4. Verifique se salva

### 3. Testar Consulta Processual

1. Acesse: `/admin/consulta-processual`
2. Tente fazer uma consulta
3. Verifique se retorna resultados

---

## 🐛 Troubleshooting

### Erro 500 ao Acessar

**Verificar:**
```bash
# 1. Ver logs
tail -f storage/logs/laravel.log

# 2. Verificar APP_KEY
grep APP_KEY .env

# 3. Limpar cache
rm -f bootstrap/cache/*.php

# 4. Verificar permissões
chmod -R 775 storage bootstrap/cache
```

### Assets não Carregam (404)

**Verificar:**
```bash
# 1. Verificar se arquivos existem
ls -la public/css/vendor/
ls -la public/js/vendor/

# 2. Verificar permissões
chmod -R 755 public/

# 3. Verificar DocumentRoot
# Deve apontar para public/
```

### Erro de Banco

**Verificar:**
```bash
# 1. Testar conexão
mysql -u usuario -p nome_banco -e "SELECT 1;"

# 2. Verificar .env
grep DB_ .env

# 3. Verificar se banco existe
mysql -u usuario -p -e "SHOW DATABASES;"
```

### Views não Encontradas

**Verificar:**
```bash
# 1. Verificar se views existem
ls -la resources/views/

# 2. Limpar cache de views
rm -rf storage/framework/views/*

# 3. Verificar logs para ver qual view está faltando
grep "View.*not found" storage/logs/laravel.log
```

---

## ✅ Checklist de Teste Completo

### Configuração
- [ ] APP_KEY configurado
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] DB_* configurado corretamente

### Arquivos
- [ ] `public/index.php` existe
- [ ] `public/css/vendor/` existe
- [ ] `public/js/vendor/` existe
- [ ] `public/fonts/` existe
- [ ] `vendor/autoload.php` existe
- [ ] Views existem em `resources/views/`

### Banco de Dados
- [ ] Conexão funciona
- [ ] Tabelas foram criadas
- [ ] Dados iniciais foram importados
- [ ] Usuários existem

### Permissões
- [ ] `storage/` é gravável (775)
- [ ] `bootstrap/cache/` é gravável (775)
- [ ] `public/` tem permissões corretas (755)

### Funcionalidades
- [ ] Site carrega no navegador
- [ ] Assets (CSS/JS) carregam
- [ ] Login funciona
- [ ] Dashboard carrega
- [ ] Navegação funciona
- [ ] CRUD básico funciona

### Logs
- [ ] Sem erros críticos nos logs
- [ ] Logs são gravados corretamente

---

## 📊 Teste de Performance

### 1. Tempo de Carregamento

Use o DevTools (F12) → Network:
- Primeira carga: < 3 segundos
- Recarregamento: < 1 segundo

### 2. Tamanho dos Assets

Verifique no Network:
- CSS total: < 500KB
- JS total: < 300KB
- Imagens: < 1MB

### 3. Requisições

- Total de requisições: < 20
- Requisições bloqueantes: < 5

---

## 🎯 Teste Rápido (1 minuto)

```bash
# 1. Executar script de teste
php testar-servidor.php

# 2. Acessar no navegador
# https://seudominio.com.br

# 3. Verificar console (F12)
# Sem erros 404 ou 500

# 4. Testar login
# admin@sistema.com / password
```

---

## 📞 Se Algo Não Funcionar

1. **Execute o script de teste:**
   ```bash
   php testar-servidor.php
   ```

2. **Verifique os logs:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

3. **Verifique permissões:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

4. **Limpe o cache:**
   ```bash
   rm -f bootstrap/cache/*.php
   ```

5. **Verifique o .env:**
   ```bash
   grep APP_KEY .env
   grep DB_ .env
   ```

---

## ✅ Resultado Esperado

Após todos os testes, você deve ter:
- ✅ Script de teste: Todos os itens OK
- ✅ Navegador: Site carrega sem erros
- ✅ Login: Funciona e redireciona
- ✅ Dashboard: Carrega e mostra dados
- ✅ Assets: Todos carregam (sem 404)
- ✅ Logs: Sem erros críticos

**Se tudo estiver OK, o sistema está funcionando! 🎉**

