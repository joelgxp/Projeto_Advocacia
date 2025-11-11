# Consulta Processual - Documentação

## 📋 Visão Geral

Este módulo permite consultar informações de processos jurídicos através de uma API externa.

## 🔧 Configuração da API

### Passo 1: Editar o arquivo `consultar.php`

Abra o arquivo `admin/consulta-processual/consultar.php` e ajuste as seguintes configurações:

```php
// URL da API (substitua pela URL real da sua API)
$api_url = 'https://api.exemplo.com/consulta-processual';

// Token/Chave de autenticação (se necessário)
$api_token = 'SEU_TOKEN_AQUI';
```

### Passo 2: Ajustar método HTTP

A API pode usar GET ou POST. Por padrão, está configurada para GET. Se sua API usar POST, descomente a seção POST no código:

```php
// Se a API usar POST, descomente e ajuste:
curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($params),
    // ... outras configurações
]);
```

### Passo 3: Ajustar Headers

Ajuste os headers HTTP conforme necessário para sua API:

```php
CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_token,
    // Adicione outros headers necessários
],
```

### Passo 4: Processar Resposta da API

Ajuste a seção "PROCESSAR RESPOSTA DA API" conforme a estrutura de resposta da sua API:

```php
$dados_processados = [
    'numero_processo' => formatarNumeroProcesso($numero_processo_limpo),
    'classe' => $dados_api['classe'] ?? 'Não informado',
    'assunto' => $dados_api['assunto'] ?? 'Não informado',
    // ... ajuste conforme sua API
];
```

## 📊 Estrutura da Tabela de Histórico

A tabela `consultas_processuais` é criada automaticamente na primeira consulta, ou você pode criá-la manualmente executando:

```sql
-- Ver arquivo criar-tabela.sql
```

## 🔐 Segurança

### Recomendações:

1. **Token de API**: Armazene o token da API no arquivo `config.php` em vez de hardcoded:
   ```php
   // Em config.php
   $api_consulta_token = 'SEU_TOKEN_AQUI';
   
   // Em consultar.php
   require_once("../../config.php");
   $api_token = $api_consulta_token;
   ```

2. **Validação**: O sistema já valida o formato do número do processo antes de enviar à API.

3. **Timeout**: O timeout está configurado para 30 segundos. Ajuste se necessário.

## 📝 Exemplos de APIs Comuns

### API do CNJ (PJe)

```php
$api_url = 'https://pje.jfsc.jus.br/api/consulta-processual';
$api_token = 'SEU_TOKEN_PJE';
```

### API de Terceiros

```php
$api_url = 'https://api.terceiros.com.br/v1/processos';
$api_token = 'SEU_TOKEN_TERCEIROS';
```

## 🐛 Troubleshooting

### Erro: "Erro na conexão"
- Verifique se a URL da API está correta
- Verifique se o servidor tem acesso à internet
- Verifique se há firewall bloqueando a conexão

### Erro: "Código HTTP: 401"
- Verifique se o token de autenticação está correto
- Verifique se o token não expirou

### Erro: "Código HTTP: 404"
- Verifique se a URL da API está correta
- Verifique se o endpoint existe

### Erro: "Erro ao decodificar resposta"
- Verifique se a API retorna JSON válido
- Verifique se há erros na resposta da API

## 📚 Estrutura de Arquivos

```
admin/consulta-processual/
├── consultar.php          # Processa a consulta na API
├── historico.php          # Exibe histórico de consultas
├── detalhes.php           # Exibe detalhes de uma consulta
├── criar-tabela.sql       # SQL para criar tabela manualmente
└── README.md              # Esta documentação
```

## 🚀 Uso

1. Acesse o menu "Consulta Processual" no painel administrativo
2. Digite o número do processo (com ou sem formatação)
3. Clique em "Consultar Processo"
4. Os resultados serão exibidos na tela
5. O histórico de consultas é salvo automaticamente

## 💡 Dicas

- O número do processo pode ser digitado com ou sem formatação
- O sistema formata automaticamente o número no padrão brasileiro
- As consultas são salvas no histórico para referência futura
- Você pode ver os detalhes completos de qualquer consulta anterior

