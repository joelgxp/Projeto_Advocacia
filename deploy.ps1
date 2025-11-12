# Script de Deploy - Sistema de Advocacia
# Executa build dos assets e prepara para produção

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  DEPLOY - Sistema de Advocacia" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se Node.js está instalado
Write-Host "[1/6] Verificando Node.js..." -ForegroundColor Yellow
try {
    $nodeVersion = node --version
    $npmVersion = npm --version
    Write-Host "  [OK] Node.js: $nodeVersion" -ForegroundColor Green
    Write-Host "  [OK] NPM: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "  [ERRO] Node.js não encontrado!" -ForegroundColor Red
    Write-Host "  Instale Node.js: https://nodejs.org/" -ForegroundColor Yellow
    exit 1
}

# Verificar se Composer está instalado
Write-Host "`n[2/6] Verificando Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version
    Write-Host "  [OK] Composer instalado" -ForegroundColor Green
} catch {
    Write-Host "  [ERRO] Composer não encontrado!" -ForegroundColor Red
    Write-Host "  Instale Composer: https://getcomposer.org/" -ForegroundColor Yellow
    exit 1
}

# Instalar dependências NPM (se necessário)
Write-Host "`n[3/6] Verificando dependências NPM..." -ForegroundColor Yellow
if (-not (Test-Path "node_modules")) {
    Write-Host "  Instalando dependências NPM..." -ForegroundColor Yellow
    npm install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "  [ERRO] Falha ao instalar dependências NPM!" -ForegroundColor Red
        exit 1
    }
    Write-Host "  [OK] Dependências NPM instaladas" -ForegroundColor Green
} else {
    Write-Host "  [OK] Dependências NPM já instaladas" -ForegroundColor Green
}

# Compilar assets com Vite
Write-Host "`n[4/6] Compilando assets (Vite)..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "  [ERRO] Falha ao compilar assets!" -ForegroundColor Red
    exit 1
}

# Verificar se build foi criado
if (-not (Test-Path "public/build")) {
    Write-Host "  [ERRO] Pasta public/build não foi criada!" -ForegroundColor Red
    exit 1
}
Write-Host "  [OK] Assets compilados em public/build/" -ForegroundColor Green

# Instalar dependências Composer (produção)
Write-Host "`n[5/6] Instalando dependências Composer (produção)..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader
if ($LASTEXITCODE -ne 0) {
    Write-Host "  [ERRO] Falha ao instalar dependências Composer!" -ForegroundColor Red
    exit 1
}
Write-Host "  [OK] Dependências Composer instaladas" -ForegroundColor Green

# Gerar cache do Laravel (opcional - só se tiver .env configurado)
Write-Host "`n[6/6] Preparando cache do Laravel..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "  Gerando cache de configuração..." -ForegroundColor Gray
    php artisan config:cache 2>&1 | Out-Null
    php artisan route:cache 2>&1 | Out-Null
    php artisan view:cache 2>&1 | Out-Null
    Write-Host "  [OK] Cache gerado" -ForegroundColor Green
} else {
    Write-Host "  [AVISO] Arquivo .env não encontrado. Cache não gerado." -ForegroundColor Yellow
    Write-Host "  Configure o .env no servidor antes de gerar cache." -ForegroundColor Yellow
}

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "  DEPLOY CONCLUÍDO COM SUCESSO!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Próximos passos:" -ForegroundColor Cyan
Write-Host "  1. Verifique se public/build/ foi criado" -ForegroundColor White
Write-Host "  2. Envie todos os arquivos para o servidor" -ForegroundColor White
Write-Host "  3. Configure o .env no servidor" -ForegroundColor White
Write-Host "  4. Execute no servidor:" -ForegroundColor White
Write-Host "     - composer install --no-dev" -ForegroundColor Gray
Write-Host "     - php artisan migrate" -ForegroundColor Gray
Write-Host "     - php artisan config:cache" -ForegroundColor Gray
Write-Host "     - php artisan route:cache" -ForegroundColor Gray
Write-Host ""




