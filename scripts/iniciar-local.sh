#!/bin/bash
# Script para iniciar servidor local - CodeIgniter 3
# Linux/Mac

echo "========================================"
echo "  Sistema de Advocacia - CodeIgniter 3"
echo "========================================"
echo ""

# Verificar se .env existe
if [ ! -f ".env" ]; then
    echo "⚠️  Arquivo .env não encontrado!"
    echo "📋 Copiando env.example para .env..."
    
    if [ -f "env.example" ]; then
        cp env.example .env
        echo "✅ Arquivo .env criado!"
        echo "⚠️  IMPORTANTE: Configure o arquivo .env antes de continuar!"
        echo ""
        read -p "Pressione Enter para continuar..."
    else
        echo "❌ Arquivo env.example não encontrado!"
        exit 1
    fi
fi

# Verificar PHP
echo "🔍 Verificando PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
    echo "✅ PHP $PHP_VERSION encontrado"
else
    echo "❌ PHP não encontrado! Instale o PHP primeiro."
    exit 1
fi

# Verificar se CodeIgniter está instalado
if [ ! -f "system/core/CodeIgniter.php" ]; then
    echo "❌ CodeIgniter não encontrado!"
    echo "📥 Baixando CodeIgniter 3.1.13..."
    
    curl -L -o codeigniter.zip https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/3.1.13.zip
    unzip -q codeigniter.zip
    mv CodeIgniter-3.1.13/system/* system/
    rm -rf codeigniter.zip CodeIgniter-3.1.13
    
    echo "✅ CodeIgniter instalado!"
fi

# Verificar porta
PORT=8000
echo ""
echo "🌐 Iniciando servidor na porta $PORT..."
echo "📍 Acesse: http://localhost:$PORT"
echo ""
echo "Pressione Ctrl+C para parar o servidor"
echo ""

# Iniciar servidor
php -S localhost:$PORT

