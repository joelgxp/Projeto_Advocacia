# 🔧 Guia de Solução de Problemas - Consulta Processual

## ❌ Erro HTTP 400 - Bad Request

O erro **HTTP 400** indica que a requisição enviada à API está incorreta. Veja as causas mais comuns:

### 1. **Formato do Número do Processo Incorreto**

**Problema:** A API pode esperar o número em um formato específico (com ou sem formatação).

**Solução:**
- Verifique na documentação da API qual formato é esperado
- Ajuste no arquivo `consultar.php` como o número é enviado:

```php
// Se a API espera COM formatação:
$params = [
    'numero_processo' => formatarNumeroProcesso($numero_processo_limpo),
];

// Se a API espera SEM formatação:
$params = [
    'numero_processo' => $numero_processo_limpo,
];

// Se a API espera outro formato (ex: apenas dígitos verificadores):
$params = [
    'numero' => substr($numero_processo_limpo, 0, 15), // Ajuste conforme necessário
];
```

### 2. **Nome do Parâmetro Incorreto**

**Problema:** A API pode esperar um nome de parâmetro diferente.

**Solução:**
Verifique na documentação da API o nome exato do parâmetro e ajuste:

```php
// Exemplos comuns:
$params = [
    'numero' => $numero_processo_limpo,           // Em vez de 'numero_processo'
    'processo' => $numero_processo_limpo,         // Outro nome comum
    'cnj' => $numero_processo_limpo,              // Para APIs do CNJ
    'numeroProcesso' => $numero_processo_limpo,   // CamelCase
];
```

### 3. **Método HTTP Incorreto**

**Problema:** A API pode exigir POST em vez de GET (ou vice-versa).

**Solução:**
No arquivo `consultar.php`, descomente a seção POST se necessário:

```php
// Para POST (descomente e ajuste):
curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($params),  // ou http_build_query($params)
    // ... resto das configurações
]);
```

### 4. **Headers HTTP Faltando ou Incorretos**

**Problema:** A API pode exigir headers específicos.

**Solução:**
Ajuste os headers conforme a documentação da API:

```php
CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',           // ou 'application/x-www-form-urlencoded'
    'Authorization: Bearer ' . $api_token,     // ou 'Basic ' . base64_encode($token)
    'Accept: application/json',                 // Adicione se necessário
    'X-API-Key: ' . $api_token,                // Algumas APIs usam este formato
    // Adicione outros headers conforme necessário
],
```

### 5. **Token de Autenticação Inválido ou Faltando**

**Problema:** O token pode estar incorreto, expirado ou faltando.

**Solução:**
- Verifique se o token está correto
- Verifique se o token não expirou
- Verifique se o formato do header de autenticação está correto

### 6. **Estrutura do Body Incorreta (POST)**

**Problema:** Se usar POST, o formato do body pode estar incorreto.

**Solução:**
```php
// Para JSON:
CURLOPT_POSTFIELDS => json_encode($params),

// Para form-urlencoded:
CURLOPT_POSTFIELDS => http_build_query($params),
```

### 7. **Validação do Número de Processo**

**Problema:** O número pode não estar no formato esperado pela API.

**Solução:**
Ajuste a validação e formatação:

```php
// Exemplo: API pode exigir exatamente 20 dígitos
if(strlen($numero_processo_limpo) !== 20){
    throw new Exception('Número de processo deve ter exatamente 20 dígitos');
}

// Ou pode exigir um formato específico
$numero_formatado = substr($numero_processo_limpo, 0, 7) . '-' . 
                    substr($numero_processo_limpo, 7, 2) . '.' . 
                    substr($numero_processo_limpo, 9, 4) . '.' . 
                    substr($numero_processo_limpo, 13, 1) . '.' . 
                    substr($numero_processo_limpo, 14, 2) . '.' . 
                    substr($numero_processo_limpo, 16);
```

## 🔍 Como Diagnosticar

### 1. Ative o Modo Debug

Na interface, marque a opção "Modo Debug" antes de fazer a consulta. Isso mostrará:
- A URL completa sendo chamada
- A resposta completa da API
- Detalhes técnicos do erro

### 2. Verifique os Logs

Os erros são registrados no log do PHP. Verifique:
- `error_log` do PHP
- Logs do servidor web (Apache/Nginx)

### 3. Teste a API Manualmente

Use ferramentas como Postman ou cURL para testar a API diretamente:

```bash
# Exemplo com cURL
curl -X GET "https://api.exemplo.com/consulta-processual?numero_processo=12345678901234567890" \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json"
```

### 4. Verifique a Documentação da API

Consulte a documentação oficial da API para:
- Formato exato dos parâmetros
- Método HTTP correto
- Headers necessários
- Formato de autenticação

## 📝 Exemplos de Configuração por Tipo de API

### API do CNJ (PJe)

```php
$api_url = 'https://pje.jfsc.jus.br/api/consulta-processual';
$api_token = 'SEU_TOKEN_PJE';

$params = [
    'numeroProcesso' => $numero_processo_limpo,
];

curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($params),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_token,
    ],
]);
```

### API de Terceiros (Exemplo)

```php
$api_url = 'https://api.terceiros.com.br/v1/processos/consultar';
$api_token = 'SEU_TOKEN';

$params = [
    'numero' => formatarNumeroProcesso($numero_processo_limpo),
    'formato' => 'cnj',
];

curl_setopt_array($ch, [
    CURLOPT_URL => $api_url . '?' . http_build_query($params),
    CURLOPT_HTTPHEADER => [
        'X-API-Key: ' . $api_token,
        'Accept: application/json',
    ],
]);
```

## ✅ Checklist de Verificação

Antes de reportar um problema, verifique:

- [ ] URL da API está correta
- [ ] Token de autenticação está correto e não expirou
- [ ] Método HTTP está correto (GET/POST)
- [ ] Nome dos parâmetros está correto
- [ ] Formato do número do processo está correto
- [ ] Headers HTTP estão corretos
- [ ] Formato do body (se POST) está correto
- [ ] A API está online e acessível
- [ ] Não há firewall bloqueando a conexão

## 🆘 Ainda com Problemas?

Se após seguir este guia o problema persistir:

1. Ative o modo debug e copie a mensagem de erro completa
2. Verifique os logs do servidor
3. Teste a API manualmente com Postman/cURL
4. Entre em contato com o suporte da API para verificar se há problemas no lado deles

