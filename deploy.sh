#!/bin/bash

# Script de Deploy - Sistema de Advocacia
# Executa build dos assets e prepara para produção

echo "========================================"
echo "  DEPLOY - Sistema de Advocacia"
echo "========================================"
echo ""

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Verificar se Node.js está instalado
echo -e "${YELLOW}[1/6] Verificando Node.js...${NC}"
if ! command -v node &> /dev/null; then
    echo -e "${RED}  [ERRO] Node.js não encontrado!${NC}"
    echo -e "${YELLOW}  Instale Node.js: https://nodejs.org/${NC}"
    exit 1
fi
NODE_VERSION=$(node --version)
NPM_VERSION=$(npm --version)
echo -e "${GREEN}  [OK] Node.js: $NODE_VERSION${NC}"
echo -e "${GREEN}  [OK] NPM: $NPM_VERSION${NC}"

# Verificar se Composer está instalado
echo -e "\n${YELLOW}[2/6] Verificando Composer...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${RED}  [ERRO] Composer não encontrado!${NC}"
    echo -e "${YELLOW}  Instale Composer: https://getcomposer.org/${NC}"
    exit 1
fi
echo -e "${GREEN}  [OK] Composer instalado${NC}"

# Instalar dependências NPM (se necessário)
echo -e "\n${YELLOW}[3/6] Verificando dependências NPM...${NC}"
if [ ! -d "node_modules" ]; then
    echo -e "${YELLOW}  Instalando dependências NPM...${NC}"
    npm install
    if [ $? -ne 0 ]; then
        echo -e "${RED}  [ERRO] Falha ao instalar dependências NPM!${NC}"
        exit 1
    fi
    echo -e "${GREEN}  [OK] Dependências NPM instaladas${NC}"
else
    echo -e "${GREEN}  [OK] Dependências NPM já instaladas${NC}"
fi

# Compilar assets com Vite
echo -e "\n${YELLOW}[4/6] Compilando assets (Vite)...${NC}"
npm run build
if [ $? -ne 0 ]; then
    echo -e "${RED}  [ERRO] Falha ao compilar assets!${NC}"
    exit 1
fi

# Verificar se build foi criado
if [ ! -d "public/build" ]; then
    echo -e "${RED}  [ERRO] Pasta public/build não foi criada!${NC}"
    exit 1
fi
echo -e "${GREEN}  [OK] Assets compilados em public/build/${NC}"

# Instalar dependências Composer (produção)
echo -e "\n${YELLOW}[5/6] Instalando dependências Composer (produção)...${NC}"
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo -e "${RED}  [ERRO] Falha ao instalar dependências Composer!${NC}"
    exit 1
fi
echo -e "${GREEN}  [OK] Dependências Composer instaladas${NC}"

# Gerar cache do Laravel (opcional - só se tiver .env configurado)
echo -e "\n${YELLOW}[6/6] Preparando cache do Laravel...${NC}"
if [ -f ".env" ]; then
    echo -e "  Gerando cache de configuração..."
    php artisan config:cache 2>/dev/null
    php artisan route:cache 2>/dev/null
    php artisan view:cache 2>/dev/null
    echo -e "${GREEN}  [OK] Cache gerado${NC}"
else
    echo -e "${YELLOW}  [AVISO] Arquivo .env não encontrado. Cache não gerado.${NC}"
    echo -e "${YELLOW}  Configure o .env no servidor antes de gerar cache.${NC}"
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  DEPLOY CONCLUÍDO COM SUCESSO!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${CYAN}Próximos passos:${NC}"
echo "  1. Verifique se public/build/ foi criado"
echo "  2. Envie todos os arquivos para o servidor"
echo "  3. Configure o .env no servidor"
echo "  4. Execute no servidor:"
echo "     - composer install --no-dev"
echo "     - php artisan migrate"
echo "     - php artisan config:cache"
echo "     - php artisan route:cache"
echo ""



