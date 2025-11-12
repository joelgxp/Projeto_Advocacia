# Script de Verificação e Configuração Local - SEM ARTISAN
# Execute: PowerShell -ExecutionPolicy Bypass -File verificar-local.ps1

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  VERIFICAÇÃO LOCAL - Sistema Advocacia" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

$erros = @()
$avisos = @()

# 1. Verificar PHP
Write-Host "1. Verificando PHP..." -ForegroundColor Yellow
if (Get-Command php -ErrorAction SilentlyContinue) {
    $phpVersion = php -v | Select-String -Pattern "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
    Write-Host "   PHP $phpVersion instalado" -ForegroundColor Green
} else {
    $erros += "PHP não encontrado! Instale em: https://windows.php.net/download"
    Write-Host "   ERRO: PHP não encontrado!" -ForegroundColor Red
}

# 2. Verificar Composer
Write-Host "2. Verificando Composer..." -ForegroundColor Yellow
if (Get-Command composer -ErrorAction SilentlyContinue) {
    Write-Host "   Composer instalado" -ForegroundColor Green
} else {
    $erros += "Composer não encontrado! Instale em: https://getcomposer.org/download/"
    Write-Host "   ERRO: Composer não encontrado!" -ForegroundColor Red
}

# 3. Verificar vendor/autoload.php
Write-Host "3. Verificando dependências..." -ForegroundColor Yellow
if (Test-Path "vendor/autoload.php") {
    Write-Host "   Dependências instaladas" -ForegroundColor Green
} else {
    $avisos += "Execute: composer install"
    Write-Host "   AVISO: Execute 'composer install'" -ForegroundColor Yellow
}

# 4. Verificar .env
Write-Host "4. Verificando arquivo .env..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "   Arquivo .env existe" -ForegroundColor Green
    
    # Verificar APP_KEY
    $envContent = Get-Content ".env" -Raw
    if ($envContent -match "APP_KEY=\s*$" -or $envContent -notmatch "APP_KEY=base64:") {
        $avisos += "APP_KEY não configurado. Execute: php -r `"echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;`""
        Write-Host "   AVISO: APP_KEY não configurado" -ForegroundColor Yellow
    } else {
        Write-Host "   APP_KEY configurado" -ForegroundColor Green
    }
} else {
    $erros += "Arquivo .env não encontrado! Copie env.example para .env"
    Write-Host "   ERRO: Arquivo .env não encontrado!" -ForegroundColor Red
}

# 5. Verificar banco de dados
Write-Host "5. Verificando configuração do banco..." -ForegroundColor Yellow
if (Test-Path ".env") {
    $envContent = Get-Content ".env" -Raw
    if ($envContent -match "DB_DATABASE=advocacia") {
        Write-Host "   Banco 'advocacia' configurado" -ForegroundColor Green
    } else {
        $avisos += "Configure DB_DATABASE=advocacia no .env"
        Write-Host "   AVISO: Banco não configurado" -ForegroundColor Yellow
    }
}

# 6. Verificar permissões storage
Write-Host "6. Verificando permissões storage..." -ForegroundColor Yellow
if (Test-Path "storage") {
    Write-Host "   Pasta storage existe" -ForegroundColor Green
} else {
    $erros += "Pasta storage não existe!"
    Write-Host "   ERRO: Pasta storage não existe!" -ForegroundColor Red
}

# 7. Verificar bootstrap/cache
Write-Host "7. Verificando bootstrap/cache..." -ForegroundColor Yellow
if (Test-Path "bootstrap/cache") {
    Write-Host "   Pasta bootstrap/cache existe" -ForegroundColor Green
} else {
    $erros += "Pasta bootstrap/cache não existe!"
    Write-Host "   ERRO: Pasta bootstrap/cache não existe!" -ForegroundColor Red
}

# 8. Limpar cache manualmente
Write-Host "8. Limpando cache..." -ForegroundColor Yellow
if (Test-Path "bootstrap/cache") {
    $cacheFiles = Get-ChildItem "bootstrap/cache/*.php" -ErrorAction SilentlyContinue
    if ($cacheFiles) {
        Remove-Item "bootstrap/cache/*.php" -Force -ErrorAction SilentlyContinue
        Write-Host "   Cache limpo" -ForegroundColor Green
    } else {
        Write-Host "   Nenhum cache para limpar" -ForegroundColor Green
    }
}

# 9. Verificar arquivo SQL
Write-Host "9. Verificando arquivo SQL..." -ForegroundColor Yellow
if (Test-Path "database/sql/advocacia.sql") {
    Write-Host "   Arquivo SQL encontrado" -ForegroundColor Green
} else {
    $avisos += "Arquivo database/sql/advocacia.sql não encontrado"
    Write-Host "   AVISO: Arquivo SQL não encontrado" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  RESUMO" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

if ($erros.Count -gt 0) {
    Write-Host ""
    Write-Host "ERROS encontrados:" -ForegroundColor Red
    foreach ($erro in $erros) {
        Write-Host "  - $erro" -ForegroundColor Red
    }
}

if ($avisos.Count -gt 0) {
    Write-Host ""
    Write-Host "AVISOS:" -ForegroundColor Yellow
    foreach ($aviso in $avisos) {
        Write-Host "  - $aviso" -ForegroundColor Yellow
    }
}

if ($erros.Count -eq 0) {
    Write-Host ""
    Write-Host "✅ Sistema pronto para rodar!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Para iniciar o servidor:" -ForegroundColor Cyan
    Write-Host "  php -S localhost:8000 -t public" -ForegroundColor White
    Write-Host ""
    Write-Host "Acesse: http://localhost:8000" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "❌ Corrija os erros antes de continuar" -ForegroundColor Red
    exit 1
}

