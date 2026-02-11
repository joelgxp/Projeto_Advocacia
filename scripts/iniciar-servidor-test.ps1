# Script para iniciar servidor e testar
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  INICIANDO SERVIDOR PHP BUILT-IN" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se já está rodando
$port = 8000
$testConnection = Test-NetConnection -ComputerName localhost -Port $port -InformationLevel Quiet -WarningAction SilentlyContinue 2>$null

if ($testConnection) {
    Write-Host "⚠️  Porta $port já está em uso!" -ForegroundColor Yellow
    Write-Host "   Parando processo anterior..." -ForegroundColor Cyan
    Get-Process | Where-Object {$_.ProcessName -eq "php"} | Stop-Process -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
}

Write-Host "🚀 Iniciando servidor na porta $port..." -ForegroundColor Green
Write-Host "📍 Acesse: http://localhost:$port" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pressione Ctrl+C para parar o servidor" -ForegroundColor Yellow
Write-Host ""

# Iniciar servidor
php -S localhost:$port router.php

