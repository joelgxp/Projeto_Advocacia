#!/bin/bash

# Script de Instalação do Composer para Servidor Online
# Sistema de Advocacia - PHP 8.3

echo "=========================================="
echo "Instalação do Composer no Servidor"
echo "Sistema de Advocacia"
echo "=========================================="
echo ""

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Verificar PHP
echo -e "${YELLOW}[1/6]${NC} Verificando PHP..."
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP não encontrado!${NC}"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "${GREEN}✅ PHP encontrado: $PHP_VERSION${NC}"

# Verificar se é PHP 8.3 ou superior
PHP_MAJOR=$(php -r 'echo PHP_MAJOR_VERSION;')
PHP_MINOR=$(php -r 'echo PHP_MINOR_VERSION;')

if [ "$PHP_MAJOR" -lt 7 ] || ([ "$PHP_MAJOR" -eq 7 ] && [ "$PHP_MINOR" -lt 4 ]); then
    echo -e "${RED}❌ PHP 7.4 ou superior é necessário. Versão atual: $PHP_VERSION${NC}"
    exit 1
fi

echo ""

# 2. Verificar se Composer já está instalado
echo -e "${YELLOW}[2/6]${NC} Verificando se Composer já está instalado..."
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version | head -n 1)
    echo -e "${GREEN}✅ Composer já está instalado: $COMPOSER_VERSION${NC}"
    USE_GLOBAL=true
else
    echo -e "${YELLOW}⚠️  Composer não encontrado globalmente.${NC}"
    USE_GLOBAL=false
fi

echo ""

# 3. Verificar se estamos no diretório correto
echo -e "${YELLOW}[3/6]${NC} Verificando diretório do projeto..."
if [ ! -f "composer.json" ]; then
    echo -e "${RED}❌ Arquivo composer.json não encontrado!${NC}"
    echo -e "${RED}   Certifique-se de estar no diretório raiz do projeto.${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Diretório do projeto encontrado${NC}"
echo ""

# 4. Verificar permissões
echo -e "${YELLOW}[4/6]${NC} Verificando permissões de escrita..."
if [ ! -w "." ]; then
    echo -e "${RED}❌ Sem permissão de escrita no diretório atual!${NC}"
    echo -e "${YELLOW}   Execute: chmod 755 .${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Permissões OK${NC}"
echo ""

# 5. Instalar Composer (se necessário)
if [ "$USE_GLOBAL" = false ]; then
    echo -e "${YELLOW}[5/6]${NC} Instalando Composer localmente..."
    
    # Verificar se curl está disponível
    if command -v curl &> /dev/null; then
        echo "Baixando instalador do Composer..."
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
        
        if [ ! -f "composer-setup.php" ]; then
            echo -e "${RED}❌ Erro ao baixar instalador do Composer${NC}"
            exit 1
        fi
        
        echo "Executando instalador..."
        php composer-setup.php --install-dir=. --filename=composer.phar
        
        if [ ! -f "composer.phar" ]; then
            echo -e "${RED}❌ Erro na instalação do Composer${NC}"
            php -r "unlink('composer-setup.php');"
            exit 1
        fi
        
        # Limpar arquivo temporário
        php -r "unlink('composer-setup.php');"
        
        # Dar permissão de execução
        chmod +x composer.phar
        
        echo -e "${GREEN}✅ Composer instalado localmente como composer.phar${NC}"
        COMPOSER_CMD="php composer.phar"
    else
        echo -e "${RED}❌ curl não encontrado!${NC}"
        echo -e "${YELLOW}   Por favor, instale o Composer manualmente ou instale o curl.${NC}"
        exit 1
    fi
else
    COMPOSER_CMD="composer"
    echo -e "${GREEN}✅ Usando Composer global${NC}"
fi

echo ""

# 6. Instalar dependências do projeto
echo -e "${YELLOW}[6/6]${NC} Instalando dependências do projeto..."
echo ""

# Verificar se vendor/ já existe
if [ -d "vendor" ]; then
    echo -e "${YELLOW}⚠️  Diretório vendor/ já existe.${NC}"
    read -p "Deseja reinstalar as dependências? (s/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        echo "Removendo vendor/ existente..."
        rm -rf vendor/
        rm -f composer.lock
    else
        echo -e "${GREEN}✅ Mantendo dependências existentes${NC}"
        exit 0
    fi
fi

# Instalar dependências
echo "Executando: $COMPOSER_CMD install --no-dev --optimize-autoloader"
$COMPOSER_CMD install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}=========================================="
    echo "✅ Instalação concluída com sucesso!"
    echo "==========================================${NC}"
    echo ""
    echo "Próximos passos:"
    echo "1. Verifique se o arquivo vendor/autoload.php foi criado"
    echo "2. Teste o autoloader executando: php exemplo-uso-composer.php"
    echo "3. Configure suas classes para usar: require_once 'vendor/autoload.php';"
    echo ""
    
    # Verificar se vendor/autoload.php existe
    if [ -f "vendor/autoload.php" ]; then
        echo -e "${GREEN}✅ vendor/autoload.php criado com sucesso!${NC}"
    else
        echo -e "${RED}❌ vendor/autoload.php não foi criado!${NC}"
        exit 1
    fi
else
    echo ""
    echo -e "${RED}=========================================="
    echo "❌ Erro na instalação das dependências"
    echo "==========================================${NC}"
    echo ""
    echo "Verifique:"
    echo "1. Se você tem permissão de escrita no diretório"
    echo "2. Se há espaço em disco suficiente"
    echo "3. Se a conexão com a internet está funcionando"
    exit 1
fi

echo ""
echo -e "${GREEN}Instalação finalizada!${NC}"

