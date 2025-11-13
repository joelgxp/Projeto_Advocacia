# Script para Iniciar Servidor Local - SEM ARTISAN
# Execute: PowerShell -ExecutionPolicy Bypass -File iniciar-servidor.ps1

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  INICIANDO SERVIDOR LOCAL" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se o PHP está instalado
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "ERRO: PHP não encontrado!" -ForegroundColor Red
    Write-Host "Baixe em: https://windows.php.net/download" -ForegroundColor Yellow
    exit 1
}

# Verificar se vendor/autoload.php existe
if (-not (Test-Path "vendor/autoload.php")) {
    Write-Host "ERRO: Dependências não instaladas!" -ForegroundColor Red
    Write-Host "Execute: composer install" -ForegroundColor Yellow
    exit 1
}

# Verificar se .env existe
if (-not (Test-Path ".env")) {
    Write-Host "AVISO: Arquivo .env não encontrado!" -ForegroundColor Yellow
    if (Test-Path "env.example") {
        Copy-Item "env.example" ".env"
        Write-Host "Arquivo .env criado a partir de env.example" -ForegroundColor Green
    } else {
        Write-Host "ERRO: env.example não encontrado!" -ForegroundColor Red
        exit 1
    }
}

# Limpar cache antes de iniciar
Write-Host "Limpando cache..." -ForegroundColor Yellow
if (Test-Path "bootstrap/cache") {
    Remove-Item "bootstrap/cache/*.php" -Force -ErrorAction SilentlyContinue
    Write-Host "Cache limpo" -ForegroundColor Green
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  SERVIDOR INICIANDO" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Servidor rodando em: http://localhost:8000" -ForegroundColor Green
Write-Host "Pressione Ctrl+C para parar o servidor" -ForegroundColor Yellow
Write-Host ""

# Iniciar servidor PHP built-in
php -S localhost:8000 -t public

