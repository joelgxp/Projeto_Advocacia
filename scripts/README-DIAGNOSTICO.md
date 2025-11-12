# Script de Diagnóstico do Servidor

## Descrição

Script para verificar se o ambiente do servidor está configurado corretamente para o Sistema de Advocacia.

## Como Usar

### 1. No Servidor

Execute o script no servidor onde o projeto está instalado:

```bash
# Navegar até o diretório do projeto
cd /caminho/do/projeto

# Executar o script
bash scripts/check-server.sh
```

### 2. Visualizar Relatório

O script gera um arquivo de relatório com timestamp:

```bash
# Ver o relatório
cat diagnostico-servidor-YYYYMMDD-HHMMSS.txt

# Ou usar less para navegar
less diagnostico-servidor-YYYYMMDD-HHMMSS.txt
```

### 3. Enviar Relatório

Copie o conteúdo do arquivo de relatório e envie para análise.

## O que o Script Verifica

1. ✅ Estrutura de diretórios
2. ✅ Arquivos essenciais (.env, index.php, etc)
3. ✅ Configuração do .env
4. ✅ Diretórios do Laravel (bootstrap/cache, storage/)
5. ✅ Permissões de arquivos e diretórios
6. ✅ PHP e extensões
7. ✅ Composer e dependências
8. ✅ Node.js e NPM (opcional)
9. ✅ Servidor web (Nginx/Apache)
10. ✅ Banco de dados
11. ✅ Laravel (versão, migrações, rotas)
12. ✅ Logs
13. ✅ Cache
14. ✅ Git
15. ✅ Recursos do servidor (disco, memória)
16. ✅ Conectividade

## Problemas Comuns Detectados

### ❌ Arquivo .env não existe
**Solução:**
```bash
cp env.example .env
php artisan key:generate
```

### ❌ Diretório bootstrap/cache não existe
**Solução:**
```bash
mkdir -p bootstrap/cache
chmod 775 bootstrap/cache
```

### ❌ Diretório storage/framework não existe
**Solução:**
```bash
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 775 storage
```

### ❌ Diretório vendor/ não existe
**Solução:**
```bash
composer install --no-dev --optimize-autoloader
```

### ❌ Permissões incorretas
**Solução:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Exemplo de Saída

O script gera um relatório completo como:

```
==========================================
  DIAGNÓSTICO DO SERVIDOR
  Sistema de Advocacia
==========================================
Data: 2024-01-15 10:30:00

=== Estrutura de Diretórios ===
✅ Diretório raiz do projeto: OK
...

=== Arquivos Essenciais ===
✅ public/index.php: OK
...

=== Resumo ===
✅ Nenhum item crítico encontrado
```

## Notas

- O script não modifica nada, apenas verifica e reporta
- Todas as informações são salvas em um arquivo de relatório
- O script é seguro para executar em produção
- Não coleta senhas ou informações sensíveis (remove do .env)

## Troubleshooting

Se o script não executar:

```bash
# Dar permissão de execução
chmod +x scripts/check-server.sh

# Executar com bash explicitamente
bash scripts/check-server.sh
```

Se houver erros de permissão:

```bash
# Executar com sudo (se necessário)
sudo bash scripts/check-server.sh
```

