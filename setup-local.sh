#!/bin/bash

# Script de Setup Local - Sistema de Advocacia
# Execute: bash setup-local.sh

echo "========================================="
echo "  SETUP LOCAL - Sistema de Advocacia"
echo "========================================="
echo ""

# Verificar PHP
echo "1. Verificando PHP..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -1 | cut -d' ' -f2)
    echo "   ✅ PHP $PHP_VERSION instalado"
else
    echo "   ❌ ERRO: PHP não encontrado!"
    echo "   Instale o PHP 8.3+ primeiro"
    exit 1
fi

# Verificar Composer
echo "2. Verificando Composer..."
if command -v composer &> /dev/null; then
    echo "   ✅ Composer instalado"
else
    echo "   ❌ ERRO: Composer não encontrado!"
    echo "   Baixe em: https://getcomposer.org/download/"
    exit 1
fi

# Instalar dependências
echo "3. Instalando dependências PHP..."
composer install --no-interaction
if [ $? -eq 0 ]; then
    echo "   ✅ Dependências instaladas"
else
    echo "   ❌ Erro ao instalar dependências"
    exit 1
fi

# Configurar .env
echo "4. Configurando arquivo .env..."
if [ ! -f ".env" ]; then
    if [ -f "env.example" ]; then
        cp env.example .env
        echo "   ✅ Arquivo .env criado"
    else
        echo "   ❌ env.example não encontrado"
        exit 1
    fi
else
    echo "   ✅ Arquivo .env já existe"
fi

# Gerar APP_KEY
echo "5. Gerando APP_KEY..."
APP_KEY=$(php -r "echo 'base64:' . base64_encode(random_bytes(32));")
if [ ! -z "$APP_KEY" ]; then
    sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
    echo "   ✅ APP_KEY gerado"
fi

echo ""
echo "========================================="
echo "  CONFIGURAÇÃO DO BANCO DE DADOS"
echo "========================================="
echo ""
echo "Deseja criar o banco 'advocacia'? (s/n): "
read criar

if [ "$criar" = "s" ] || [ "$criar" = "S" ]; then
    echo "Digite a senha do MySQL root: "
    read -s senha
    
    mysql -uroot -p"$senha" -e "CREATE DATABASE IF NOT EXISTS advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "   ✅ Banco 'advocacia' criado!"
        
        # Atualizar .env
        sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$senha|" .env
    else
        echo "   ❌ Erro ao criar banco"
    fi
fi

echo ""
echo "========================================="
echo "  SETUP CONCLUÍDO!"
echo "========================================="
echo ""
echo "Próximos passos:"
echo ""
echo "1. Se ainda não criou o banco:"
echo "   mysql -uroot -p -e 'CREATE DATABASE advocacia;'"
echo ""
echo "2. Importar banco de dados:"
echo "   mysql -uroot -p advocacia < database/sql/advocacia.sql"
echo ""
echo "3. Iniciar o servidor:"
echo "   php -S localhost:8000 -t public"
echo ""
echo "5. Acessar:"
echo "   http://localhost:8000"
echo ""
echo "Credenciais padrão:"
echo "   Admin: admin@sistema.com / password"
echo ""

