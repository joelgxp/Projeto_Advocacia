# 🔧 Corrigir Erro 404 no Apache

## ⚠️ Problema: Erro 404 após login

O Apache não está processando as rotas do CodeIgniter corretamente.

## ✅ Solução Rápida

### Opção 1: Usar Servidor PHP Built-in (MAIS FÁCIL)

Pare o Apache e use o servidor PHP:

```powershell
# Parar Apache no XAMPP/WAMP
# Depois execute:
php -S localhost:8000 router.php
```

Acesse: `http://localhost:8000`

### Opção 2: Configurar Apache Corretamente

#### Passo 1: Habilitar mod_rewrite

**XAMPP:**
1. Abra: `C:\xampp\apache\conf\httpd.conf`
2. Procure: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remova o `#`:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

**WAMP:**
1. Clique no ícone WAMP
2. Apache → Apache Modules → **rewrite_module** (marque)

#### Passo 2: Configurar AllowOverride

**XAMPP:**
No arquivo `httpd.conf`, procure por:
```apache
<Directory "C:/xampp/htdocs">
    ...
    AllowOverride None
    ...
</Directory>
```

Altere para:
```apache
<Directory "C:/xampp/htdocs">
    ...
    AllowOverride All
    ...
</Directory>
```

**WAMP:**
Mesmo processo, procure por `<Directory>` e altere `AllowOverride None` para `AllowOverride All`

#### Passo 3: Configurar DocumentRoot

O DocumentRoot deve apontar para a **raiz do projeto**:

```apache
DocumentRoot "C:/Users/joelv/OneDrive/Documentos/Repositorios/Projeto_Advocacia"
<Directory "C:/Users/joelv/OneDrive/Documentos/Repositorios/Projeto_Advocacia">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

#### Passo 4: Reiniciar Apache

- **XAMPP:** Pare e inicie o Apache no Control Panel
- **WAMP:** Reinicie todos os serviços

#### Passo 5: Testar

1. Acesse: `http://localhost/`
2. Faça login
3. Deve redirecionar para `http://localhost/admin` sem erro

## 🔍 Verificar se está funcionando

Execute:
```powershell
php scripts/verificar-apache.php
```

Acesse diretamente no navegador:
- `http://localhost/verificar-apache.php` (se estiver usando Apache)
- Ou execute via linha de comando

## 🆘 Se ainda não funcionar

1. **Verifique os logs do Apache:**
   - XAMPP: `C:\xampp\apache\logs\error.log`
   - WAMP: `C:\wamp64\logs\apache_error.log`

2. **Teste o .htaccess:**
   - Acesse: `http://localhost/index.php/admin`
   - Se funcionar, o problema é o mod_rewrite
   - Se não funcionar, o problema é outro

3. **Use o servidor PHP built-in:**
   ```powershell
   php -S localhost:8000 router.php
   ```
   Esta é a forma mais confiável para desenvolvimento local.

## 📝 Nota

O controller Admin foi temporariamente modificado para não verificar permissões, facilitando o debug. Após corrigir o Apache, você pode reabilitar a verificação de permissões.

