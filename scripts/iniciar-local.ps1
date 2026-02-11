# Script para iniciar servidor local - CodeIgniter 3
# Windows PowerShell

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Sistema de Advocacia - CodeIgniter 3" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se .env existe
if (-not (Test-Path ".env")) {
    Write-Host "⚠️  Arquivo .env não encontrado!" -ForegroundColor Yellow
    Write-Host "📋 Copiando env.example para .env..." -ForegroundColor Cyan
    
    if (Test-Path "env.example") {
        Copy-Item env.example .env
        Write-Host "✅ Arquivo .env criado!" -ForegroundColor Green
        Write-Host "⚠️  IMPORTANTE: Configure o arquivo .env antes de continuar!" -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Pressione qualquer tecla para abrir o .env no editor..."
        $null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
        notepad .env
    } else {
        Write-Host "❌ Arquivo env.example não encontrado!" -ForegroundColor Red
        exit 1
    }
}

# Verificar PHP
Write-Host "🔍 Verificando PHP..." -ForegroundColor Cyan
$phpVersion = php -v 2>&1 | Select-String -Pattern "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }

if ($phpVersion) {
    Write-Host "✅ PHP $phpVersion encontrado" -ForegroundColor Green
} else {
    Write-Host "❌ PHP não encontrado! Instale o PHP primeiro." -ForegroundColor Red
    exit 1
}

# Verificar se CodeIgniter está instalado
if (-not (Test-Path "system/core/CodeIgniter.php")) {
    Write-Host "❌ CodeIgniter não encontrado!" -ForegroundColor Red
    Write-Host "📥 Baixando CodeIgniter 3.1.13..." -ForegroundColor Cyan
    
    $ProgressPreference = 'SilentlyContinue'
    Invoke-WebRequest -Uri "https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/3.1.13.zip" -OutFile "codeigniter.zip"
    Expand-Archive -Path "codeigniter.zip" -DestinationPath "." -Force
    Move-Item -Path "CodeIgniter-3.1.13\system\*" -Destination "system\" -Force -ErrorAction SilentlyContinue
    Remove-Item -Path "codeigniter.zip" -Force -ErrorAction SilentlyContinue
    Remove-Item -Path "CodeIgniter-3.1.13" -Recurse -Force -ErrorAction SilentlyContinue
    
    Write-Host "✅ CodeIgniter instalado!" -ForegroundColor Green
}

# Limpar cache antes de iniciar
Write-Host "🧹 Limpando cache..." -ForegroundColor Cyan
if (Test-Path "application/cache") {
    Get-ChildItem "application/cache" -File | Remove-Item -Force -ErrorAction SilentlyContinue
    Write-Host "   ✅ Cache limpo" -ForegroundColor Green
}

# Criar diretórios necessários
Write-Host "📁 Criando diretórios necessários..." -ForegroundColor Cyan
$dirs = @("application/logs", "application/cache", "application/sessions")
foreach ($dir in $dirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "   ✅ Criado: $dir" -ForegroundColor Green
    }
}

# Criar diretório webfonts se necessário
if (-not (Test-Path "public/css/webfonts")) {
    New-Item -ItemType Directory -Path "public/css/webfonts" -Force | Out-Null
    Write-Host "   ✅ Criado: public/css/webfonts" -ForegroundColor Green
}

# Verificar porta
$port = 8000
Write-Host ""
Write-Host "🌐 Iniciando servidor na porta $port..." -ForegroundColor Cyan
Write-Host "📍 Acesse: http://localhost:$port" -ForegroundColor Green
Write-Host ""
Write-Host "Pressione Ctrl+C para parar o servidor" -ForegroundColor Yellow
Write-Host ""

# Iniciar servidor (CodeIgniter precisa do DocumentRoot na raiz)
# Mesmo comportamento do servidor online - usar index.php da raiz
Write-Host "🚀 Iniciando servidor (igual ao online)..." -ForegroundColor Cyan
Write-Host "   Usando index.php da RAIZ (CodeIgniter)" -ForegroundColor Green
Write-Host ""

# Servidor PHP built-in com router.php para servir assets corretamente
php -S localhost:$port router.php
