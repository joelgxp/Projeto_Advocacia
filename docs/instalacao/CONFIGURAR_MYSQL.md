# Configurar MySQL - Sistema de Advocacia

## ⚠️ Problema: MySQL não está acessível

O erro `SQLSTATE[HY000] [2002] Nenhuma conexão pôde ser feita porque a máquina de destino as recusou ativamente` indica que o MySQL não está rodando ou não está acessível.

## 🔧 Solução

### 1. Iniciar MySQL (XAMPP)

Se você está usando XAMPP:

1. Abra o **XAMPP Control Panel**
2. Clique em **Start** no MySQL
3. Aguarde até aparecer "Running" em verde

### 2. Iniciar MySQL (WAMP)

Se você está usando WAMP:

1. Abra o **WAMP Server**
2. Clique com botão direito no ícone do WAMP na bandeja
3. Selecione **MySQL** > **Service** > **Start/Resume Service**

### 3. Verificar se MySQL está rodando

Execute no terminal:

```bash
# Verificar se a porta 3306 está aberta
Test-NetConnection -ComputerName 127.0.0.1 -Port 3306
```

### 4. Criar Banco de Dados

Após iniciar o MySQL, crie o banco de dados:

**Opção 1: Via phpMyAdmin**
1. Acesse: http://localhost/phpmyadmin
2. Clique em "Novo" (New)
3. Nome do banco: `advocacia`
4. Collation: `utf8mb4_unicode_ci`
5. Clique em "Criar"

**Opção 2: Via linha de comando**
```bash
mysql -u root -p
```

Depois execute:
```sql
CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5. Verificar Configuração no .env

Certifique-se de que o arquivo `.env` está configurado corretamente:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=advocacia
DB_USERNAME=root
DB_PASSWORD=
```

**Nota:** Se sua senha do MySQL não for vazia, adicione em `DB_PASSWORD=`

### 6. Testar Conexão

Após iniciar o MySQL e criar o banco:

```bash
php artisan config:clear
php artisan migrate:status
```

Se funcionar, você verá a lista de migrations.

### 7. Executar Migrations

```bash
php artisan migrate
```

### 8. Popular Banco de Dados

```bash
php artisan db:seed
```

## 🔍 Troubleshooting

### MySQL não inicia

1. Verifique se a porta 3306 não está sendo usada por outro serviço
2. Verifique os logs do MySQL no XAMPP/WAMP
3. Tente reiniciar o serviço MySQL

### Erro de permissão

Se houver erro de permissão, verifique:
- Usuário `root` tem acesso
- Senha está correta (ou vazia se for XAMPP padrão)

### Porta diferente

Se o MySQL estiver em outra porta, ajuste no `.env`:
```env
DB_PORT=3307  # ou a porta que você usa
```

## ✅ Após Configurar

Quando o MySQL estiver rodando e o banco criado:

1. Execute: `php artisan migrate`
2. Execute: `php artisan db:seed`
3. Acesse: http://127.0.0.1:8000
4. Faça login com: admin@advocacia.com / 123456

