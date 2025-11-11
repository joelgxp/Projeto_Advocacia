# 🔑 Como Atualizar a API Key do CNJ

## ⚠️ Importante

A API Key do CNJ pode ser alterada a qualquer momento pelo CNJ por razões de segurança. Quando isso acontecer, você precisará atualizar a chave no sistema.

## 📍 Onde Atualizar

### Opção 1: No arquivo `config.php` (Recomendado)

1. Abra o arquivo `config.php` na raiz do projeto
2. Localize a linha com `$api_cnj_key`
3. Substitua o valor pela nova chave:

```php
$api_cnj_key = 'NOVA_CHAVE_AQUI';
```

### Opção 2: No arquivo `consultar.php`

1. Abra o arquivo `admin/consulta-processual/consultar.php`
2. Localize a linha com a API Key (aproximadamente linha 45)
3. Substitua o valor pela nova chave:

```php
$api_key = 'NOVA_CHAVE_AQUI';
```

## 🔍 Onde Encontrar a Chave Atualizada

A chave atualizada estará sempre disponível na documentação oficial do CNJ:
- Wiki do DataJud
- Documentação da API Pública do CNJ

## 📝 Formato da Autenticação

A API do CNJ usa o seguinte formato no header:

```
Authorization: APIKey [Chave Pública]
```

Exemplo:
```
Authorization: APIKey cDZHYzlZa0JadVREZDJCendQbXY6SkJlTzNjLV9TRENyQk1RdnFKZGRQdw==
```

## ✅ Verificação

Após atualizar a chave:

1. Acesse o menu "Consulta Processual"
2. Selecione um tribunal
3. Digite um número de processo
4. Faça uma consulta de teste

Se receber erro 401 (Unauthorized), a chave pode estar incorreta ou desatualizada.

## 🆘 Problemas Comuns

### Erro 401 - Unauthorized
- **Causa**: API Key incorreta ou desatualizada
- **Solução**: Verifique e atualize a chave conforme instruções acima

### Erro 403 - Forbidden
- **Causa**: Chave pode estar bloqueada ou inválida
- **Solução**: Verifique se a chave está correta e se não expirou

### Erro 429 - Too Many Requests
- **Causa**: Muitas requisições em pouco tempo
- **Solução**: Aguarde alguns minutos antes de tentar novamente

