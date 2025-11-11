# ⚡ Configuração Rápida da API

## 🎯 Passo a Passo

### 1. Abra o arquivo de configuração

Edite o arquivo: `admin/consulta-processual/consultar.php`

### 2. Localize as linhas 39-45

```php
// URL da API (substitua pela URL real da sua API)
$api_url = 'https://api.exemplo.com/consulta-processual';

// Token/Chave de autenticação (se necessário)
$api_token = 'SEU_TOKEN_AQUI';
```

### 3. Configure a URL da sua API

Substitua `https://api.exemplo.com/consulta-processual` pela URL real da sua API.

**Exemplos:**
```php
// Exemplo 1: API do CNJ
$api_url = 'https://pje.jfsc.jus.br/api/consulta-processual';

// Exemplo 2: API de terceiros
$api_url = 'https://api.seudominio.com.br/v1/processos/consultar';

// Exemplo 3: API local
$api_url = 'http://localhost:8080/api/consulta';
```

### 4. Configure o Token (se necessário)

Substitua `SEU_TOKEN_AQUI` pelo token real da sua API.

```php
$api_token = 'abc123xyz789token';
```

**Ou configure no config.php:**
```php
// Em config.php, adicione:
$api_consulta_token = 'seu_token_aqui';

// Em consultar.php, use:
require_once("../../config.php");
$api_token = $api_consulta_token ?? 'SEU_TOKEN_AQUI';
```

### 5. Ajuste o método HTTP (se necessário)

Por padrão, está configurado para **GET**. Se sua API usar **POST**, descomente as linhas 64-77:

```php
// Descomente e ajuste se a API usar POST:
curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($params),
    // ... resto do código
]);
```

### 6. Ajuste os parâmetros (se necessário)

Na linha 53-55, ajuste o nome do parâmetro conforme sua API:

```php
// Se a API espera outro nome:
$params = [
    'numero' => $numero_processo_limpo,        // Em vez de 'numero_processo'
    'processo' => $numero_processo_limpo,      // Ou 'processo'
    'cnj' => $numero_processo_limpo,           // Ou 'cnj'
];
```

### 7. Ajuste os Headers (se necessário)

Na linha 56-60, ajuste os headers conforme sua API:

```php
CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',           // Ou 'application/x-www-form-urlencoded'
    'Authorization: Bearer ' . $api_token,     // Ou 'Basic ' . base64_encode($token)
    'X-API-Key: ' . $api_token,                // Ou outro formato
    'Accept: application/json',                 // Adicione se necessário
],
```

## 📋 Checklist

Antes de testar, verifique:

- [ ] URL da API configurada corretamente
- [ ] Token configurado (se necessário)
- [ ] Método HTTP correto (GET ou POST)
- [ ] Nome do parâmetro correto
- [ ] Headers configurados corretamente
- [ ] Formato do número do processo correto

## 🧪 Teste

1. Acesse o menu "Consulta Processual"
2. Digite um número de processo válido
3. Marque "Modo Debug" para ver detalhes
4. Clique em "Consultar Processo"

## ❓ Ainda com problemas?

Consulte o arquivo `TROUBLESHOOTING.md` para mais detalhes sobre erros comuns.

