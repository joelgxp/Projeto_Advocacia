#!/bin/bash

# Script de Diagnóstico Específico para Problemas Online do Laravel
# Verifica problemas comuns que impedem o Laravel de funcionar online

echo "=========================================="
echo "  DIAGNÓSTICO LARAVEL ONLINE"
echo "  Verificando problemas comuns"
echo "=========================================="
echo "Data: $(date)"
echo ""

REPORT_FILE="diagnostico-laravel-online-$(date +%Y%m%d-%H%M%S).txt"
touch "$REPORT_FILE"

add_report() {
    echo "$1" | tee -a "$REPORT_FILE"
}

add_report "=========================================="
add_report "  DIAGNÓSTICO LARAVEL ONLINE"
add_report "=========================================="
add_report "Data: $(date)"
add_report ""

# 1. Verificar APP_KEY
add_report "=========================================="
add_report "  1. VERIFICAR APP_KEY"
add_report "=========================================="

if [ -f ".env" ]; then
    APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "null" ] || [ "$APP_KEY" = "" ]; then
        add_report "❌ CRÍTICO: APP_KEY não está configurado!"
        add_report "   Execute: php artisan key:generate"
        add_report ""
    else
        add_report "✅ APP_KEY está configurado"
        add_report "   Valor: ${APP_KEY:0:20}..."
        add_report ""
    fi
else
    add_report "❌ CRÍTICO: Arquivo .env não existe!"
    add_report ""
fi

# 2. Verificar DocumentRoot e estrutura
add_report "=========================================="
add_report "  2. VERIFICAR ESTRUTURA E DOCUMENTROOT"
add_report "=========================================="

CURRENT_DIR=$(pwd)
add_report "Diretório atual: $CURRENT_DIR"
add_report ""

# Verificar se public/index.php existe
if [ -f "public/index.php" ]; then
    add_report "✅ public/index.php existe"
else
    add_report "❌ CRÍTICO: public/index.php NÃO existe!"
    add_report ""
fi

# Verificar se public/.htaccess existe
if [ -f "public/.htaccess" ]; then
    add_report "✅ public/.htaccess existe"
    add_report "Conteúdo do .htaccess:"
    head -5 public/.htaccess | tee -a "$REPORT_FILE"
    add_report ""
else
    add_report "❌ CRÍTICO: public/.htaccess NÃO existe!"
    add_report ""
fi

# Verificar se o DocumentRoot está apontando para public/
add_report "Verificando se o DocumentRoot está correto:"
add_report "O DocumentRoot do servidor web DEVE apontar para: $CURRENT_DIR/public"
add_report ""

# 3. Verificar permissões
add_report "=========================================="
add_report "  3. VERIFICAR PERMISSÕES"
add_report "=========================================="

# Verificar permissões do storage
if [ -d "storage" ]; then
    STORAGE_PERM=$(stat -c "%a" storage 2>/dev/null || stat -f "%OLp" storage 2>/dev/null)
    add_report "Permissões do storage: $STORAGE_PERM"
    if [ "$STORAGE_PERM" != "775" ] && [ "$STORAGE_PERM" != "777" ]; then
        add_report "⚠️ AVISO: Permissões do storage podem estar incorretas (recomendado: 775)"
        add_report "   Execute: chmod -R 775 storage"
    else
        add_report "✅ Permissões do storage estão OK"
    fi
    add_report ""
fi

# Verificar permissões do bootstrap/cache
if [ -d "bootstrap/cache" ]; then
    CACHE_PERM=$(stat -c "%a" bootstrap/cache 2>/dev/null || stat -f "%OLp" bootstrap/cache 2>/dev/null)
    add_report "Permissões do bootstrap/cache: $CACHE_PERM"
    if [ "$CACHE_PERM" != "775" ] && [ "$CACHE_PERM" != "777" ]; then
        add_report "⚠️ AVISO: Permissões do bootstrap/cache podem estar incorretas (recomendado: 775)"
        add_report "   Execute: chmod -R 775 bootstrap/cache"
    else
        add_report "✅ Permissões do bootstrap/cache estão OK"
    fi
    add_report ""
fi

# 4. Verificar se o Laravel pode ser executado
add_report "=========================================="
add_report "  4. TESTAR EXECUÇÃO DO LARAVEL"
add_report "=========================================="

if [ -f "artisan" ]; then
    add_report "Testando se o artisan funciona..."
    
    # Tentar executar um comando simples
    PHP_VERSION_CHECK=$(php artisan --version 2>&1)
    if echo "$PHP_VERSION_CHECK" | grep -qi "Laravel\|artisan"; then
        add_report "✅ Artisan está funcionando"
        add_report "   Saída: $PHP_VERSION_CHECK"
    else
        add_report "❌ ERRO: Artisan não está funcionando corretamente"
        add_report "   Saída: $PHP_VERSION_CHECK"
        add_report ""
        add_report "Possíveis causas:"
        add_report "  - Problemas com vendor/autoload.php"
        add_report "  - Problemas com bootstrap/app.php"
        add_report "  - Erros de sintaxe no código"
    fi
    add_report ""
else
    add_report "❌ CRÍTICO: Arquivo artisan não existe!"
    add_report ""
fi

# 5. Verificar erros de PHP
add_report "=========================================="
add_report "  5. VERIFICAR ERROS DE PHP"
add_report "=========================================="

# Verificar se há erros de sintaxe
add_report "Verificando erros de sintaxe PHP..."
PHP_SYNTAX_ERRORS=$(php -l public/index.php 2>&1)
if echo "$PHP_SYNTAX_ERRORS" | grep -qi "No syntax errors"; then
    add_report "✅ public/index.php: Sem erros de sintaxe"
else
    add_report "❌ ERRO: Problemas de sintaxe em public/index.php"
    add_report "   $PHP_SYNTAX_ERRORS"
fi
add_report ""

# 6. Verificar cache
add_report "=========================================="
add_report "  6. VERIFICAR CACHE"
add_report "=========================================="

if [ -f "bootstrap/cache/config.php" ]; then
    add_report "✅ Cache de configuração existe"
    CACHE_TIME=$(stat -c "%Y" bootstrap/cache/config.php 2>/dev/null || stat -f "%Sm" bootstrap/cache/config.php 2>/dev/null)
    add_report "   Última atualização: $CACHE_TIME"
else
    add_report "ℹ️ Cache de configuração não existe (será criado automaticamente)"
fi
add_report ""

# 7. Verificar logs de erro
add_report "=========================================="
add_report "  7. VERIFICAR LOGS DE ERRO"
add_report "=========================================="

if [ -d "storage/logs" ]; then
    LATEST_LOG=$(ls -t storage/logs/*.log 2>/dev/null | head -1)
    if [ -n "$LATEST_LOG" ]; then
        add_report "✅ Logs encontrados"
        add_report "   Último log: $LATEST_LOG"
        add_report ""
        add_report "Últimas 20 linhas do log:"
        tail -20 "$LATEST_LOG" 2>/dev/null | tee -a "$REPORT_FILE" || add_report "   (Não foi possível ler o log)"
    else
        add_report "ℹ️ Nenhum arquivo de log encontrado (pode ser normal se não houve erros)"
    fi
else
    add_report "⚠️ Diretório storage/logs não existe"
fi
add_report ""

# 8. Verificar servidor web
add_report "=========================================="
add_report "  8. VERIFICAR SERVIDOR WEB"
add_report "=========================================="

# Tentar detectar Apache
if command -v apache2ctl >/dev/null 2>&1 || command -v httpd >/dev/null 2>&1; then
    add_report "✅ Apache detectado"
    if command -v apache2ctl >/dev/null 2>&1; then
        apache2ctl -v 2>/dev/null | head -1 | tee -a "$REPORT_FILE" || true
    fi
else
    add_report "⚠️ Apache não detectado no PATH"
fi

# Tentar detectar Nginx
if command -v nginx >/dev/null 2>&1; then
    add_report "✅ Nginx detectado"
    nginx -v 2>&1 | tee -a "$REPORT_FILE" || true
else
    add_report "⚠️ Nginx não detectado no PATH"
fi

add_report ""
add_report "NOTA: Em servidores compartilhados (como HostGator),"
add_report "o servidor web geralmente é gerenciado pelo painel de controle."
add_report ""

# 9. Testar acesso ao index.php
add_report "=========================================="
add_report "  9. TESTAR ACESSO AO INDEX.PHP"
add_report "=========================================="

if [ -f "public/index.php" ]; then
    # Tentar executar o index.php via CLI para ver se há erros
    add_report "Testando execução do index.php..."
    PHP_OUTPUT=$(php public/index.php 2>&1 | head -20)
    
    if echo "$PHP_OUTPUT" | grep -qi "error\|fatal\|warning"; then
        add_report "❌ ERRO detectado ao executar index.php:"
        echo "$PHP_OUTPUT" | tee -a "$REPORT_FILE"
    else
        add_report "✅ index.php pode ser executado (sem erros fatais)"
        add_report "   (Nota: Saída esperada em ambiente web, não CLI)"
    fi
    add_report ""
fi

# 10. Verificar variáveis de ambiente críticas
add_report "=========================================="
add_report "  10. VARIÁVEIS DE AMBIENTE CRÍTICAS"
add_report "=========================================="

if [ -f ".env" ]; then
    add_report "Verificando variáveis críticas no .env:"
    
    # APP_URL
    APP_URL=$(grep "^APP_URL=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    if [ -n "$APP_URL" ]; then
        add_report "✅ APP_URL: $APP_URL"
    else
        add_report "⚠️ APP_URL não configurado"
    fi
    
    # DB_CONNECTION
    DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)
    if [ -n "$DB_CONNECTION" ]; then
        add_report "✅ DB_CONNECTION: $DB_CONNECTION"
    else
        add_report "⚠️ DB_CONNECTION não configurado"
    fi
    
    # DB_DATABASE
    DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
    if [ -n "$DB_DATABASE" ]; then
        add_report "✅ DB_DATABASE: $DB_DATABASE"
    else
        add_report "⚠️ DB_DATABASE não configurado"
    fi
    
    add_report ""
fi

# 11. Resumo e recomendações
add_report "=========================================="
add_report "  RESUMO E RECOMENDAÇÕES"
add_report "=========================================="
add_report ""

# Contar problemas críticos
CRITICAL_COUNT=0

if [ -f ".env" ]; then
    APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
    if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "null" ] || [ "$APP_KEY" = "" ]; then
        CRITICAL_COUNT=$((CRITICAL_COUNT + 1))
    fi
fi

if [ ! -f "public/index.php" ]; then
    CRITICAL_COUNT=$((CRITICAL_COUNT + 1))
fi

if [ ! -f "public/.htaccess" ]; then
    CRITICAL_COUNT=$((CRITICAL_COUNT + 1))
fi

if [ $CRITICAL_COUNT -gt 0 ]; then
    add_report "❌ $CRITICAL_COUNT problema(s) crítico(s) encontrado(s)!"
    add_report ""
    add_report "COMANDOS PARA CORRIGIR:"
    add_report ""
    
    if [ -f ".env" ]; then
        APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
        if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "null" ] || [ "$APP_KEY" = "" ]; then
            add_report "1. Gerar APP_KEY:"
            add_report "   php artisan key:generate"
            add_report ""
        fi
    fi
    
    add_report "2. Limpar e regenerar cache:"
    add_report "   php artisan config:clear"
    add_report "   php artisan cache:clear"
    add_report "   php artisan config:cache"
    add_report ""
    
    add_report "3. Verificar permissões:"
    add_report "   chmod -R 775 storage bootstrap/cache"
    add_report "   chown -R www-data:www-data storage bootstrap/cache  # Se necessário"
    add_report ""
    
    add_report "4. Verificar DocumentRoot do servidor web:"
    add_report "   O DocumentRoot DEVE apontar para: $CURRENT_DIR/public"
    add_report ""
else
    add_report "✅ Nenhum problema crítico encontrado!"
    add_report ""
    add_report "Se o site ainda não funciona, verifique:"
    add_report "  1. DocumentRoot do servidor web aponta para: $CURRENT_DIR/public"
    add_report "  2. Mod_rewrite está habilitado no Apache"
    add_report "  3. Permissões corretas (775 para storage e bootstrap/cache)"
    add_report "  4. Logs de erro do servidor web"
    add_report ""
fi

add_report "=========================================="
add_report "  FIM DO RELATÓRIO"
add_report "=========================================="
add_report "Arquivo salvo em: $REPORT_FILE"
add_report ""

echo ""
echo "✅ Diagnóstico concluído!"
echo "📄 Relatório salvo em: $REPORT_FILE"
echo ""

