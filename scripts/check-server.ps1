# Script de Verificação do Ambiente do Servidor (PowerShell)
# Sistema de Advocacia - Diagnóstico Completo
# Execute no servidor: pwsh scripts/check-server.ps1

$ReportFile = "diagnostico-servidor-$(Get-Date -Format 'yyyyMMdd-HHmmss').txt"

function Add-Report {
    param([string]$Message)
    Write-Host $Message
    Add-Content -Path $ReportFile -Value $Message
}

Add-Report "=========================================="
Add-Report "  DIAGNÓSTICO DO SERVIDOR"
Add-Report "  Sistema de Advocacia"
Add-Report "=========================================="
Add-Report "Data: $(Get-Date)"
Add-Report ""

# 1. Informações do Sistema
Add-Report "=========================================="
Add-Report "  1. INFORMAÇÕES DO SISTEMA"
Add-Report "=========================================="
Add-Report "Sistema Operacional: $($env:OS)"
Add-Report "Usuário atual: $($env:USERNAME)"
Add-Report "Diretório atual: $(Get-Location)"
Add-Report ""

# 2. Estrutura de Diretórios
Add-Report "=========================================="
Add-Report "  2. ESTRUTURA DE DIRETÓRIOS"
Add-Report "=========================================="

$directories = @("public", "app", "bootstrap", "storage", "vendor")
foreach ($dir in $directories) {
    if (Test-Path $dir) {
        Add-Report "✅ Diretório $dir existe"
        Get-ChildItem $dir | Select-Object -First 5 | ForEach-Object {
            Add-Report "  $($_.Name)"
        }
    } else {
        Add-Report "❌ Diretório $dir NÃO existe"
    }
}

# 3. Arquivos Essenciais
Add-Report ""
Add-Report "=========================================="
Add-Report "  3. ARQUIVOS ESSENCIAIS"
Add-Report "=========================================="

$files = @("public/index.php", "public/.htaccess", "composer.json", "package.json", "artisan", ".env")
foreach ($file in $files) {
    if (Test-Path $file) {
        Add-Report "✅ $file existe"
    } else {
        Add-Report "❌ $file NÃO existe"
    }
}

# 4. Arquivo .env
Add-Report ""
Add-Report "=========================================="
Add-Report "  4. ARQUIVO .env"
Add-Report "=========================================="

if (Test-Path ".env") {
    Add-Report "✅ Arquivo .env existe"
    Add-Report ""
    Add-Report "Variáveis importantes:"
    Get-Content .env | Where-Object { $_ -match "APP_NAME|APP_ENV|APP_DEBUG|APP_URL|DB_|API_" } | ForEach-Object {
        if ($_ -notmatch "PASSWORD|SECRET|KEY") {
            Add-Report "  $_"
        }
    }
} else {
    Add-Report "❌ Arquivo .env NÃO existe"
    Add-Report "⚠️ CRÍTICO: O Laravel precisa do arquivo .env para funcionar"
}

# 5. Diretórios do Laravel
Add-Report ""
Add-Report "=========================================="
Add-Report "  5. DIRETÓRIOS DO LARAVEL"
Add-Report "=========================================="

if (Test-Path "bootstrap/cache") {
    Add-Report "✅ bootstrap/cache existe"
} else {
    Add-Report "❌ bootstrap/cache NÃO existe"
    Add-Report "⚠️ CRÍTICO: Este diretório é necessário"
}

if (Test-Path "storage/framework") {
    Add-Report "✅ storage/framework existe"
    Get-ChildItem "storage/framework" | ForEach-Object {
        Add-Report "  $($_.Name)"
    }
} else {
    Add-Report "❌ storage/framework NÃO existe"
    Add-Report "⚠️ CRÍTICO: Este diretório é necessário"
}

# 6. PHP
Add-Report ""
Add-Report "=========================================="
Add-Report "  6. PHP"
Add-Report "=========================================="

try {
    $phpVersion = php -v 2>&1 | Select-Object -First 1
    Add-Report "✅ PHP instalado"
    Add-Report "Versão: $phpVersion"
} catch {
    Add-Report "❌ PHP não instalado ou não encontrado no PATH"
}

# 7. Composer
Add-Report ""
Add-Report "=========================================="
Add-Report "  7. COMPOSER"
Add-Report "=========================================="

try {
    $composerVersion = composer --version 2>&1
    Add-Report "✅ Composer instalado"
    Add-Report "Versão: $composerVersion"
    
    if (Test-Path "vendor") {
        Add-Report "✅ Diretório vendor/ existe"
    } else {
        Add-Report "❌ Diretório vendor/ NÃO existe"
        Add-Report "⚠️ Execute: composer install"
    }
} catch {
    Add-Report "❌ Composer não instalado ou não encontrado no PATH"
}

# 8. Node.js e NPM
Add-Report ""
Add-Report "=========================================="
Add-Report "  8. NODE.JS E NPM"
Add-Report "=========================================="

try {
    $nodeVersion = node --version 2>&1
    Add-Report "✅ Node.js instalado"
    Add-Report "Versão: $nodeVersion"
    
    try {
        $npmVersion = npm --version 2>&1
        Add-Report "✅ NPM instalado"
        Add-Report "Versão: $npmVersion"
        
        if (Test-Path "public/build") {
            Add-Report "✅ public/build/ existe (assets compilados)"
        } else {
            Add-Report "⚠️ public/build/ não existe (assets não compilados)"
        }
    } catch {
        Add-Report "⚠️ NPM não encontrado"
    }
} catch {
    Add-Report "ℹ️ Node.js não instalado (normal se assets são compilados no CI/CD)"
}

# 9. Logs
Add-Report ""
Add-Report "=========================================="
Add-Report "  9. LOGS"
Add-Report "=========================================="

if (Test-Path "storage/logs/laravel.log") {
    Add-Report "✅ Log do Laravel existe"
    $logSize = (Get-Item "storage/logs/laravel.log").Length / 1KB
    Add-Report "Tamanho: $([math]::Round($logSize, 2)) KB"
    Add-Report ""
    Add-Report "Últimas 10 linhas do log:"
    Get-Content "storage/logs/laravel.log" -Tail 10 | ForEach-Object {
        Add-Report "  $_"
    }
} else {
    Add-Report "⚠️ Log do Laravel não existe (pode ser normal se não houve erros)"
}

# 10. Resumo
Add-Report ""
Add-Report "=========================================="
Add-Report "  10. RESUMO"
Add-Report "=========================================="
Add-Report ""

$criticalItems = 0

if (-not (Test-Path ".env")) {
    Add-Report "❌ CRÍTICO: Arquivo .env não existe"
    $criticalItems++
}

if (-not (Test-Path "bootstrap/cache")) {
    Add-Report "❌ CRÍTICO: Diretório bootstrap/cache não existe"
    $criticalItems++
}

if (-not (Test-Path "storage/framework")) {
    Add-Report "❌ CRÍTICO: Diretório storage/framework não existe"
    $criticalItems++
}

if (-not (Test-Path "public/index.php")) {
    Add-Report "❌ CRÍTICO: Arquivo public/index.php não existe"
    $criticalItems++
}

if (-not (Test-Path "vendor")) {
    Add-Report "❌ CRÍTICO: Diretório vendor/ não existe (execute: composer install)"
    $criticalItems++
}

if ($criticalItems -eq 0) {
    Add-Report "✅ Nenhum item crítico encontrado"
} else {
    Add-Report "⚠️ Total de itens críticos: $criticalItems"
}

Add-Report ""
Add-Report "=========================================="
Add-Report "  FIM DO RELATÓRIO"
Add-Report "=========================================="
Add-Report "Arquivo de relatório salvo em: $ReportFile"
Add-Report "Data: $(Get-Date)"

Write-Host ""
Write-Host "=========================================="
Write-Host "  DIAGNÓSTICO CONCLUÍDO"
Write-Host "=========================================="
Write-Host "Relatório salvo em: $ReportFile"
Write-Host ""
Write-Host "Para visualizar o relatório:"
Write-Host "  Get-Content $ReportFile"
Write-Host ""

