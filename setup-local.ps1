# Script de Setup Local - Sistema de Advocacia
# Execute como Administrador: PowerShell -ExecutionPolicy Bypass -File setup-local.ps1

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  SETUP LOCAL - Sistema de Advocacia" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se o PHP está instalado
Write-Host "1. Verificando PHP..." -ForegroundColor Yellow
if (Get-Command php -ErrorAction SilentlyContinue) {
    $phpVersion = php -v | Select-String -Pattern "PHP (\d+\.\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
    Write-Host "   PHP $phpVersion instalado" -ForegroundColor Green
} else {
    Write-Host "   ERRO: PHP não encontrado!" -ForegroundColor Red
    Write-Host "   Baixe em: https://windows.php.net/download" -ForegroundColor Yellow
    exit 1
}

# Verificar Composer
Write-Host "2. Verificando Composer..." -ForegroundColor Yellow
if (Get-Command composer -ErrorAction SilentlyContinue) {
    Write-Host "   Composer instalado" -ForegroundColor Green
} else {
    Write-Host "   ERRO: Composer não encontrado!" -ForegroundColor Red
    Write-Host "   Baixe em: https://getcomposer.org/download/" -ForegroundColor Yellow
    exit 1
}

# Instalar dependências
Write-Host "3. Instalando dependências PHP..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "   Dependências instaladas com sucesso" -ForegroundColor Green
} else {
    Write-Host "   ERRO ao instalar dependências" -ForegroundColor Red
    exit 1
}

# Configurar .env
Write-Host "4. Configurando arquivo .env..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    if (Test-Path "env.example") {
        Copy-Item "env.example" ".env"
        Write-Host "   Arquivo .env criado" -ForegroundColor Green
    } else {
        Write-Host "   ERRO: env.example não encontrado" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "   Arquivo .env já existe" -ForegroundColor Green
}

# Gerar APP_KEY
Write-Host "5. Gerando APP_KEY..." -ForegroundColor Yellow
$appKey = & php -r "echo 'base64:' . base64_encode(random_bytes(32));"
if ($appKey) {
    # Atualizar APP_KEY no .env
    $envContent = Get-Content ".env" -Raw
    if ($envContent -match "APP_KEY=\s*$") {
        $envContent = $envContent -replace "APP_KEY=\s*$", "APP_KEY=$appKey"
        Set-Content ".env" -Value $envContent -NoNewline
        Write-Host "   APP_KEY gerado e configurado" -ForegroundColor Green
    } else {
        Write-Host "   APP_KEY já está configurado" -ForegroundColor Green
    }
}

# Solicitar configurações do banco
Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  CONFIGURAÇÃO DO BANCO DE DADOS" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Configure o banco de dados no arquivo .env:" -ForegroundColor Yellow
Write-Host "  - DB_DATABASE=advocacia" -ForegroundColor White
Write-Host "  - DB_USERNAME=root" -ForegroundColor White
Write-Host "  - DB_PASSWORD=(sua senha MySQL)" -ForegroundColor White
Write-Host ""
Write-Host "Criar banco? (s/n): " -ForegroundColor Yellow -NoNewline
$criar = Read-Host

if ($criar -eq 's' -or $criar -eq 'S') {
    Write-Host "Digite a senha do MySQL root: " -ForegroundColor Yellow -NoNewline
    $senhaMySQL = Read-Host -AsSecureString
    $senhaPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($senhaMySQL))
    
    Write-Host "Criando banco de dados..." -ForegroundColor Yellow
    $createDB = "CREATE DATABASE IF NOT EXISTS advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    & mysql -uroot "-p$senhaPlain" -e $createDB 2>$null
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "   Banco 'advocacia' criado com sucesso!" -ForegroundColor Green
        
        # Atualizar .env com a senha
        $envContent = Get-Content ".env" -Raw
        $envContent = $envContent -replace "DB_PASSWORD=.*", "DB_PASSWORD=$senhaPlain"
        Set-Content ".env" -Value $envContent -NoNewline
    } else {
        Write-Host "   Erro ao criar banco (verifique se o MySQL está rodando)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  SETUP CONCLUÍDO!" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Próximos passos:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Se ainda não criou o banco, execute:" -ForegroundColor White
Write-Host "   mysql -uroot -p -e 'CREATE DATABASE advocacia;'" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Importar banco de dados:" -ForegroundColor White
Write-Host "   Via phpMyAdmin: Importar database/sql/advocacia.sql" -ForegroundColor Gray
Write-Host "   OU via comando: mysql -uroot -p advocacia < database\sql\advocacia.sql" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Iniciar o servidor:" -ForegroundColor White
Write-Host "   php -S localhost:8000 -t public" -ForegroundColor Gray
Write-Host ""
Write-Host "5. Acessar no navegador:" -ForegroundColor White
Write-Host "   http://localhost:8000" -ForegroundColor Green
Write-Host ""
Write-Host "Credenciais padrão (após db:seed):" -ForegroundColor Yellow
Write-Host "   Admin: admin@sistema.com / password" -ForegroundColor Gray
Write-Host "   Advogado: advogado@sistema.com / password" -ForegroundColor Gray
Write-Host ""

