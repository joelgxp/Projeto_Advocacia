#!/bin/bash

# Script de Verificação do Ambiente do Servidor
# Sistema de Advocacia - Diagnóstico Completo
# Execute no servidor: bash scripts/check-server.sh

echo "=========================================="
echo "  DIAGNÓSTICO DO SERVIDOR"
echo "  Sistema de Advocacia"
echo "=========================================="
echo "Data: $(date)"
echo ""

# Criar arquivo de relatório
REPORT_FILE="diagnostico-servidor-$(date +%Y%m%d-%H%M%S).txt"
touch "$REPORT_FILE"

# Função para adicionar ao relatório
add_report() {
    echo "$1" | tee -a "$REPORT_FILE"
}

# Função para verificar e reportar
check_item() {
    local name="$1"
    local command="$2"
    local expected="$3"
    
    add_report ""
    add_report "=== $name ==="
    
    if eval "$command" > /dev/null 2>&1; then
        add_report "✅ $name: OK"
        if [ -n "$expected" ]; then
            add_report "Detalhes:"
            eval "$command" | head -10 | tee -a "$REPORT_FILE"
        fi
    else
        add_report "❌ $name: FALHOU"
        add_report "Erro ao verificar $name"
    fi
}

add_report "=========================================="
add_report "  INFORMAÇÕES DO SISTEMA"
add_report "=========================================="
add_report "Sistema Operacional: $(uname -a)"
add_report "Usuário atual: $(whoami)"
add_report "Diretório atual: $(pwd)"
add_report "Data/Hora: $(date)"
add_report ""

# 1. VERIFICAR ESTRUTURA DE DIRETÓRIOS
add_report "=========================================="
add_report "  1. ESTRUTURA DE DIRETÓRIOS"
add_report "=========================================="

check_item "Diretório raiz do projeto" "ls -la | head -20"
check_item "Diretório public/" "ls -la public/"
check_item "Diretório app/" "ls -la app/ | head -5"
check_item "Diretório bootstrap/" "ls -la bootstrap/"
check_item "Diretório storage/" "ls -la storage/"
check_item "Diretório vendor/" "ls -d vendor/"

# 2. VERIFICAR ARQUIVOS ESSENCIAIS
add_report ""
add_report "=========================================="
add_report "  2. ARQUIVOS ESSENCIAIS"
add_report "=========================================="

check_item "public/index.php" "ls -la public/index.php"
check_item "public/.htaccess" "ls -la public/.htaccess"
check_item "composer.json" "ls -la composer.json"
check_item "package.json" "ls -la package.json"
check_item "artisan" "ls -la artisan"

# 3. VERIFICAR ARQUIVO .env
add_report ""
add_report "=========================================="
add_report "  3. ARQUIVO .env"
add_report "=========================================="

if [ -f ".env" ]; then
    add_report "✅ Arquivo .env existe"
    add_report "Permissões: $(ls -l .env | awk '{print $1, $3, $4}')"
    add_report ""
    add_report "Conteúdo (sem senhas):"
    grep -v "PASSWORD\|SECRET\|KEY" .env | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Variáveis importantes:"
    grep -E "APP_NAME|APP_ENV|APP_DEBUG|APP_URL|DB_|API_" .env | tee -a "$REPORT_FILE"
else
    add_report "❌ Arquivo .env NÃO existe"
    add_report "⚠️ CRÍTICO: O Laravel precisa do arquivo .env para funcionar"
fi

# 4. VERIFICAR DIRETÓRIOS DO LARAVEL
add_report ""
add_report "=========================================="
add_report "  4. DIRETÓRIOS DO LARAVEL"
add_report "=========================================="

# bootstrap/cache
if [ -d "bootstrap/cache" ]; then
    add_report "✅ bootstrap/cache existe"
    add_report "Permissões: $(ls -ld bootstrap/cache | awk '{print $1, $3, $4}')"
    add_report "Conteúdo: $(ls -la bootstrap/cache/ | wc -l) arquivos"
else
    add_report "❌ bootstrap/cache NÃO existe"
    add_report "⚠️ CRÍTICO: Este diretório é necessário"
fi

# storage/framework
if [ -d "storage/framework" ]; then
    add_report "✅ storage/framework existe"
    add_report "Estrutura:"
    ls -la storage/framework/ | tee -a "$REPORT_FILE"
else
    add_report "❌ storage/framework NÃO existe"
    add_report "⚠️ CRÍTICO: Este diretório é necessário"
fi

# storage/logs
if [ -d "storage/logs" ]; then
    add_report "✅ storage/logs existe"
    add_report "Últimos logs:"
    ls -lt storage/logs/ | head -5 | tee -a "$REPORT_FILE"
else
    add_report "❌ storage/logs NÃO existe"
fi

# 5. VERIFICAR PERMISSÕES
add_report ""
add_report "=========================================="
add_report "  5. PERMISSÕES"
add_report "=========================================="

add_report "Permissões de diretórios:"
ls -ld . public/ storage/ bootstrap/cache/ 2>/dev/null | awk '{print $1, $3, $4, $9}' | tee -a "$REPORT_FILE"

add_report ""
add_report "Permissões de arquivos importantes:"
ls -l public/index.php public/.htaccess artisan composer.json 2>/dev/null | awk '{print $1, $9}' | tee -a "$REPORT_FILE"

# 6. VERIFICAR PHP
add_report ""
add_report "=========================================="
add_report "  6. PHP"
add_report "=========================================="

if command -v php &> /dev/null; then
    add_report "✅ PHP instalado"
    add_report "Versão: $(php -v | head -1)"
    add_report ""
    add_report "Extensões instaladas:"
    php -m | grep -E "pdo|mysql|mbstring|openssl|tokenizer|xml|ctype|json|fileinfo|curl" | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Configuração PHP:"
    php -i | grep -E "memory_limit|upload_max_filesize|post_max_size|max_execution_time" | tee -a "$REPORT_FILE"
else
    add_report "❌ PHP não instalado"
fi

# 7. VERIFICAR COMPOSER
add_report ""
add_report "=========================================="
add_report "  7. COMPOSER"
add_report "=========================================="

if command -v composer &> /dev/null; then
    add_report "✅ Composer instalado"
    add_report "Versão: $(composer --version)"
    add_report ""
    if [ -d "vendor" ]; then
        add_report "✅ Diretório vendor/ existe"
        add_report "Tamanho: $(du -sh vendor/ 2>/dev/null | awk '{print $1}')"
    else
        add_report "❌ Diretório vendor/ NÃO existe"
        add_report "⚠️ Execute: composer install"
    fi
else
    add_report "❌ Composer não instalado"
fi

# 8. VERIFICAR NODE.JS E NPM (opcional)
add_report ""
add_report "=========================================="
add_report "  8. NODE.JS E NPM (opcional)"
add_report "=========================================="

if command -v node &> /dev/null; then
    add_report "✅ Node.js instalado"
    add_report "Versão: $(node --version)"
    if command -v npm &> /dev/null; then
        add_report "✅ NPM instalado"
        add_report "Versão: $(npm --version)"
        if [ -d "node_modules" ]; then
            add_report "✅ node_modules/ existe"
        else
            add_report "ℹ️ node_modules/ não existe (normal se não usar npm no servidor)"
        fi
        if [ -d "public/build" ]; then
            add_report "✅ public/build/ existe (assets compilados)"
            add_report "Conteúdo:"
            ls -la public/build/ | head -10 | tee -a "$REPORT_FILE"
        else
            add_report "⚠️ public/build/ não existe (assets não compilados)"
        fi
    fi
else
    add_report "ℹ️ Node.js não instalado (normal se assets são compilados no CI/CD)"
fi

# 9. VERIFICAR SERVIDOR WEB
add_report ""
add_report "=========================================="
add_report "  9. SERVIDOR WEB"
add_report "=========================================="

if command -v nginx &> /dev/null; then
    add_report "✅ Nginx instalado"
    add_report "Versão: $(nginx -v 2>&1)"
    add_report "Status:"
    systemctl status nginx --no-pager -l 2>/dev/null | head -10 | tee -a "$REPORT_FILE" || service nginx status 2>/dev/null | head -10 | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Configuração do site:"
    if [ -f "/etc/nginx/sites-available/adv.joelsouza.com.br" ]; then
        add_report "✅ Arquivo de configuração encontrado"
        grep -E "server_name|root|listen" /etc/nginx/sites-available/adv.joelsouza.com.br | tee -a "$REPORT_FILE"
    elif [ -f "/etc/nginx/sites-enabled/adv.joelsouza.com.br" ]; then
        add_report "✅ Arquivo de configuração encontrado (enabled)"
        grep -E "server_name|root|listen" /etc/nginx/sites-enabled/adv.joelsouza.com.br | tee -a "$REPORT_FILE"
    else
        add_report "⚠️ Arquivo de configuração não encontrado"
        add_report "Arquivos disponíveis:"
        ls -la /etc/nginx/sites-available/ 2>/dev/null | head -10 | tee -a "$REPORT_FILE"
    fi
elif command -v apache2 &> /dev/null; then
    add_report "✅ Apache instalado"
    add_report "Versão: $(apache2 -v | head -1)"
    add_report "Status:"
    systemctl status apache2 --no-pager -l 2>/dev/null | head -10 | tee -a "$REPORT_FILE" || service apache2 status 2>/dev/null | head -10 | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Configuração do site:"
    if [ -f "/etc/apache2/sites-available/adv.joelsouza.com.br.conf" ]; then
        add_report "✅ Arquivo de configuração encontrado"
        grep -E "ServerName|DocumentRoot|Directory" /etc/apache2/sites-available/adv.joelsouza.com.br.conf | tee -a "$REPORT_FILE"
    else
        add_report "⚠️ Arquivo de configuração não encontrado"
    fi
else
    add_report "⚠️ Servidor web não detectado (Nginx ou Apache)"
fi

# 10. VERIFICAR BANCO DE DADOS
add_report ""
add_report "=========================================="
add_report "  10. BANCO DE DADOS"
add_report "=========================================="

if [ -f ".env" ]; then
    DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    
    add_report "Configurações do .env:"
    add_report "DB_HOST: $DB_HOST"
    add_report "DB_DATABASE: $DB_DATABASE"
    add_report "DB_USERNAME: $DB_USERNAME"
    add_report ""
    
    # Testar conexão (se artisan existe)
    if [ -f "artisan" ]; then
        add_report "Testando conexão com banco..."
        php artisan tinker --execute="DB::connection()->getPdo(); echo 'Conexão OK';" 2>&1 | tee -a "$REPORT_FILE"
    fi
else
    add_report "⚠️ Não é possível verificar banco (arquivo .env não existe)"
fi

# 11. VERIFICAR LARAVEL
add_report ""
add_report "=========================================="
add_report "  11. LARAVEL"
add_report "=========================================="

if [ -f "artisan" ]; then
    add_report "✅ Arquivo artisan existe"
    add_report ""
    add_report "Versão do Laravel:"
    php artisan --version 2>&1 | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Status das migrações:"
    php artisan migrate:status 2>&1 | head -20 | tee -a "$REPORT_FILE"
    add_report ""
    add_report "Rotas disponíveis (primeiras 10):"
    php artisan route:list 2>&1 | head -15 | tee -a "$REPORT_FILE"
else
    add_report "❌ Arquivo artisan não encontrado"
fi

# 12. VERIFICAR LOGS
add_report ""
add_report "=========================================="
add_report "  12. LOGS"
add_report "=========================================="

if [ -f "storage/logs/laravel.log" ]; then
    add_report "✅ Log do Laravel existe"
    add_report "Tamanho: $(du -h storage/logs/laravel.log | awk '{print $1}')"
    add_report ""
    add_report "Últimas 20 linhas do log:"
    tail -20 storage/logs/laravel.log | tee -a "$REPORT_FILE"
else
    add_report "⚠️ Log do Laravel não existe (pode ser normal se não houve erros)"
fi

# 13. VERIFICAR CACHE
add_report ""
add_report "=========================================="
add_report "  13. CACHE"
add_report "=========================================="

if [ -d "bootstrap/cache" ]; then
    add_report "Cache de configuração:"
    ls -la bootstrap/cache/*.php 2>/dev/null | tee -a "$REPORT_FILE" || add_report "Nenhum arquivo de cache encontrado"
fi

if [ -d "storage/framework/cache" ]; then
    add_report "Cache de aplicação:"
    ls -la storage/framework/cache/ 2>/dev/null | head -10 | tee -a "$REPORT_FILE"
fi

# 14. VERIFICAR GIT
add_report ""
add_report "=========================================="
add_report "  14. GIT"
add_report "=========================================="

if command -v git &> /dev/null; then
    add_report "✅ Git instalado"
    add_report "Versão: $(git --version)"
    if [ -d ".git" ]; then
        add_report "✅ Repositório Git encontrado"
        add_report "Branch atual: $(git branch --show-current 2>/dev/null || echo 'N/A')"
        add_report "Último commit: $(git log -1 --oneline 2>/dev/null || echo 'N/A')"
        add_report "Status:"
        git status --short 2>/dev/null | head -10 | tee -a "$REPORT_FILE" || add_report "Nenhuma alteração pendente"
    else
        add_report "⚠️ Diretório .git não encontrado"
    fi
else
    add_report "⚠️ Git não instalado"
fi

# 15. VERIFICAR ESPAÇO EM DISCO
add_report ""
add_report "=========================================="
add_report "  15. RECURSOS DO SERVIDOR"
add_report "=========================================="

add_report "Espaço em disco:"
df -h | tee -a "$REPORT_FILE"
add_report ""
add_report "Uso de memória:"
free -h | tee -a "$REPORT_FILE"
add_report ""
add_report "Tamanho do projeto:"
du -sh . 2>/dev/null | tee -a "$REPORT_FILE"

# 16. VERIFICAR CONECTIVIDADE
add_report ""
add_report "=========================================="
add_report "  16. CONECTIVIDADE"
add_report "=========================================="

add_report "Testando conectividade com GitHub:"
ping -c 2 github.com 2>&1 | tee -a "$REPORT_FILE" || add_report "Não foi possível testar conectividade"

# 17. RESUMO E RECOMENDAÇÕES
add_report ""
add_report "=========================================="
add_report "  17. RESUMO E RECOMENDAÇÕES"
add_report "=========================================="

add_report ""
add_report "ITENS CRÍTICOS:"
add_report ""

CRITICAL_ITEMS=0

if [ ! -f ".env" ]; then
    add_report "❌ CRÍTICO: Arquivo .env não existe"
    CRITICAL_ITEMS=$((CRITICAL_ITEMS + 1))
fi

if [ ! -d "bootstrap/cache" ]; then
    add_report "❌ CRÍTICO: Diretório bootstrap/cache não existe"
    CRITICAL_ITEMS=$((CRITICAL_ITEMS + 1))
fi

if [ ! -d "storage/framework" ]; then
    add_report "❌ CRÍTICO: Diretório storage/framework não existe"
    CRITICAL_ITEMS=$((CRITICAL_ITEMS + 1))
fi

if [ ! -f "public/index.php" ]; then
    add_report "❌ CRÍTICO: Arquivo public/index.php não existe"
    CRITICAL_ITEMS=$((CRITICAL_ITEMS + 1))
fi

if [ ! -d "vendor" ]; then
    add_report "❌ CRÍTICO: Diretório vendor/ não existe (execute: composer install)"
    CRITICAL_ITEMS=$((CRITICAL_ITEMS + 1))
fi

if [ $CRITICAL_ITEMS -eq 0 ]; then
    add_report "✅ Nenhum item crítico encontrado"
else
    add_report "⚠️ Total de itens críticos: $CRITICAL_ITEMS"
fi

add_report ""
add_report "ITENS DE AVISO:"
add_report ""

if [ ! -d "public/build" ]; then
    add_report "⚠️ AVISO: Diretório public/build não existe (assets não compilados)"
fi

if [ ! -f "storage/logs/laravel.log" ]; then
    add_report "ℹ️ INFO: Log do Laravel não existe (pode ser normal)"
fi

add_report ""
add_report "=========================================="
add_report "  COMANDOS PARA CORRIGIR PROBLEMAS"
add_report "=========================================="
add_report ""
add_report "1. Criar diretórios do Laravel:"
add_report "   mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs"
add_report "   chmod -R 775 storage bootstrap/cache"
add_report ""
add_report "2. Criar arquivo .env:"
add_report "   cp env.example .env"
add_report "   php artisan key:generate"
add_report ""
add_report "3. Instalar dependências:"
add_report "   composer install --no-dev --optimize-autoloader"
add_report ""
add_report "4. Executar migrações:"
add_report "   php artisan migrate"
add_report ""
add_report "5. Limpar e regenerar cache:"
add_report "   php artisan config:clear"
add_report "   php artisan config:cache"
add_report ""
add_report "=========================================="
add_report "  FIM DO RELATÓRIO"
add_report "=========================================="
add_report "Arquivo de relatório salvo em: $REPORT_FILE"
add_report "Data: $(date)"

echo ""
echo "=========================================="
echo "  DIAGNÓSTICO CONCLUÍDO"
echo "=========================================="
echo "Relatório salvo em: $REPORT_FILE"
echo ""
echo "Para visualizar o relatório:"
echo "  cat $REPORT_FILE"
echo ""
echo "Para enviar o relatório:"
echo "  cat $REPORT_FILE"
echo ""

