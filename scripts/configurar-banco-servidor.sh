#!/bin/bash
# Script para configurar banco de dados no servidor

echo "========================================"
echo "  Configurar Banco de Dados"
echo "========================================"
echo ""

# Credenciais
DB_HOST="localhost"
DB_PORT="3306"
DB_USER="hotel631_joeladv"
DB_PASS="mXrnP61Gc&K$"
DB_NAME="hotel631_advocacia"

# Verificar se .env existe
if [ ! -f ".env" ]; then
    echo "⚠️  Arquivo .env não encontrado!"
    echo "📋 Criando .env a partir do env.example..."
    
    if [ -f "env.example" ]; then
        cp env.example .env
        echo "✅ Arquivo .env criado!"
    else
        echo "❌ Arquivo env.example não encontrado!"
        exit 1
    fi
fi

# Atualizar .env com credenciais do banco
echo "📝 Atualizando configurações do banco de dados..."

# Usar sed para atualizar (ou criar se não existir)
sed -i "s|^DB_HOSTNAME=.*|DB_HOSTNAME=$DB_HOST|" .env 2>/dev/null || echo "DB_HOSTNAME=$DB_HOST" >> .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env 2>/dev/null || echo "DB_USERNAME=$DB_USER" >> .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env 2>/dev/null || echo "DB_PASSWORD=$DB_PASS" >> .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env 2>/dev/null || echo "DB_DATABASE=$DB_NAME" >> .env
sed -i "s|^DB_DRIVER=.*|DB_DRIVER=mysqli|" .env 2>/dev/null || echo "DB_DRIVER=mysqli" >> .env

# Configurar ambiente de produção
sed -i "s|^APP_ENVIRONMENT=.*|APP_ENVIRONMENT=production|" .env 2>/dev/null || echo "APP_ENVIRONMENT=production" >> .env

# Gerar APP_ENCRYPTION_KEY se não existir
if ! grep -q "^APP_ENCRYPTION_KEY=" .env || grep -q "^APP_ENCRYPTION_KEY=$" .env; then
    echo "🔑 Gerando APP_ENCRYPTION_KEY..."
    ENCRYPTION_KEY=$(php -r "echo base64_encode(random_bytes(32));")
    sed -i "s|^APP_ENCRYPTION_KEY=.*|APP_ENCRYPTION_KEY=$ENCRYPTION_KEY|" .env 2>/dev/null || echo "APP_ENCRYPTION_KEY=$ENCRYPTION_KEY" >> .env
    echo "✅ Chave gerada!"
fi

# Configurar permissões
chmod 600 .env
echo "✅ Permissões do .env ajustadas (600)"

# Testar conexão
echo ""
echo "🔍 Testando conexão com o banco de dados..."
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT 'Conexão OK!' as status;" 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Conexão com banco de dados funcionando!"
else
    echo "❌ Erro na conexão. Verifique as credenciais."
fi

echo ""
echo "========================================"
echo "✅ Configuração concluída!"
echo "========================================"

