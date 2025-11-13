# 🔧 Correção de Erro CSRF

## ❌ Erro: "The action you have requested is not allowed"

Este erro ocorre quando o CodeIgniter detecta que um formulário POST não tem o token CSRF válido.

## ✅ Correções Aplicadas

### 1. Token CSRF no Formulário de Login
Adicionado token CSRF no formulário de login (`application/views/auth/login.php`):

```php
<?php
// Adicionar token CSRF se estiver habilitado
if ($this->config->item('csrf_protection') === TRUE) {
    echo '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '" />';
}
?>
```

### 2. Verificação CSRF no Controller
Adicionada verificação CSRF no método `processar()` do controller Login.

## 📋 Próximos Passos

Após fazer deploy, o formulário de login deve funcionar corretamente.

## 🔍 Se o Erro Persistir

### Opção 1: Desabilitar CSRF temporariamente (não recomendado)

No servidor, edite `application/config/config.php`:

```php
$config['csrf_protection'] = FALSE;
```

### Opção 2: Excluir login do CSRF

No servidor, edite `application/config/config.php`:

```php
$config['csrf_exclude_uris'] = array('login/processar');
```

### Opção 3: Verificar se o token está sendo enviado

Abra o console do navegador (F12) e verifique se o campo hidden com o token CSRF está presente no formulário.

## ✅ Solução Recomendada

Manter CSRF habilitado e usar o token no formulário (já implementado). Isso é mais seguro.

