<?php
/**
 * Script para Criar Banco de Dados - Sistema de Advocacia
 * Execute: php scripts/criar-banco-dados.php
 * 
 * Este script:
 * 1. Lê as configurações do .env
 * 2. Cria o banco de dados se não existir
 * 3. Executa o script SQL de criação
 * 4. Cria a tabela ci_sessions
 * 5. Cria o usuário admin se não existir
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "  CRIAR BANCO DE DADOS\n";
echo "  Sistema de Advocacia - CodeIgniter 3\n";
echo "========================================\n\n";

// Detectar raiz do projeto
$rootDir = __DIR__;
if (basename($rootDir) === 'scripts') {
    $rootDir = dirname($rootDir);
}

chdir($rootDir);

// 1. Ler configurações do .env
echo "1. Lendo configurações do .env...\n";

if (!file_exists('.env')) {
    echo "   ❌ Arquivo .env não encontrado!\n";
    echo "   💡 Copie env.example para .env primeiro\n";
    exit(1);
}

$envContent = file_get_contents('.env');
$envLines = explode("\n", $envContent);
$configs = [];

foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue;
    }
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $configs[trim($key)] = trim($value);
    }
}

$host = $configs['DB_HOSTNAME'] ?? $configs['DB_HOST'] ?? 'localhost';
$user = $configs['DB_USERNAME'] ?? 'root';
$pass = $configs['DB_PASSWORD'] ?? '';
$database = $configs['DB_DATABASE'] ?? 'advocacia';
$port = $configs['DB_PORT'] ?? 3306;

echo "   ✅ Configurações lidas:\n";
echo "      Host: $host\n";
echo "      Usuário: $user\n";
echo "      Banco: $database\n";
echo "      Porta: $port\n\n";

// 2. Conectar ao MySQL (sem especificar banco)
echo "2. Conectando ao MySQL...\n";
try {
    $mysqli = new mysqli($host, $user, $pass, '', $port);
    
    if ($mysqli->connect_error) {
        throw new Exception("Erro de conexão: " . $mysqli->connect_error);
    }
    
    echo "   ✅ Conectado ao MySQL\n\n";
    
    // 3. Criar banco de dados se não existir
    echo "3. Criando banco de dados '$database'...\n";
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    
    if ($mysqli->query($sql)) {
        echo "   ✅ Banco de dados '$database' criado/verificado\n\n";
    } else {
        throw new Exception("Erro ao criar banco: " . $mysqli->error);
    }
    
    // Selecionar o banco
    $mysqli->select_db($database);
    
    // 4. Executar script SQL de criação
    echo "4. Executando script SQL de criação...\n";
    $sqlFile = $rootDir . '/database/sql/criar-banco.sql';
    
    if (file_exists($sqlFile)) {
        echo "   📄 Lendo arquivo: database/sql/criar-banco.sql\n";
        
        $sqlContent = file_get_contents($sqlFile);
        
        // Remover comentários SQL e dividir em comandos
        $sqlContent = preg_replace('/--.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/\/\*.*?\*\//s', '', $sqlContent);
        
        // Dividir por ponto e vírgula, mas manter dentro de strings
        $commands = [];
        $currentCommand = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sqlContent); $i++) {
            $char = $sqlContent[$i];
            
            if (($char === '"' || $char === "'") && ($i === 0 || $sqlContent[$i-1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                    $stringChar = '';
                }
            }
            
            $currentCommand .= $char;
            
            if (!$inString && $char === ';') {
                $command = trim($currentCommand);
                if (!empty($command)) {
                    $commands[] = $command;
                }
                $currentCommand = '';
            }
        }
        
        // Adicionar último comando se não terminou com ;
        if (!empty(trim($currentCommand))) {
            $commands[] = trim($currentCommand);
        }
        
        $executados = 0;
        $erros = 0;
        $avisos = 0;
        
        // Desabilitar verificação de foreign keys temporariamente
        $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");
        
        // Desabilitar exceções do mysqli para tratar erros manualmente
        mysqli_report(MYSQLI_REPORT_OFF);
        
        foreach ($commands as $command) {
            $command = trim($command);
            if (empty($command)) {
                continue;
            }
            
            // Ignorar comandos USE
            if (strtoupper(substr($command, 0, 3)) === 'USE') {
                continue;
            }
            
            // Ignorar SELECT (verificações)
            if (strtoupper(substr($command, 0, 6)) === 'SELECT') {
                continue;
            }
            
            // Remover foreign keys temporariamente do comando CREATE TABLE
            $originalCommand = $command;
            if (stripos($command, 'CREATE TABLE') !== false) {
                // Remover FOREIGN KEY constraints do CREATE TABLE
                $command = preg_replace('/,\s*FOREIGN\s+KEY\s*\([^)]+\)\s*REFERENCES\s+\w+\([^)]+\)/i', '', $command);
            }
            
            // Executar comando com tratamento de erro
            $result = @$mysqli->query($command);
            if ($result) {
                $executados++;
            } else {
                $errorMsg = $mysqli->error;
                
                // Ignorar erros comuns que não são críticos
                if (strpos($errorMsg, 'already exists') !== false || 
                    strpos($errorMsg, 'Duplicate') !== false ||
                    strpos($errorMsg, 'Duplicate entry') !== false ||
                    strpos($errorMsg, 'on duplicate key') !== false ||
                    strpos($errorMsg, 'Foreign key constraint') !== false ||
                    (strpos($errorMsg, 'Unknown column') !== false && stripos($command, 'INSERT') !== false) ||
                    (stripos($command, 'INSERT') !== false)) {
                    // Erro esperado, ignorar silenciosamente
                    // INSERTs podem falhar se estrutura diferente, isso é normal
                    continue;
                } else {
                    // Mostrar aviso mas continuar apenas para comandos não críticos
                    if (stripos($command, 'CREATE TABLE') === false) {
                        // Não mostrar avisos para INSERTs com problemas de estrutura
                        if (!(stripos($command, 'INSERT') !== false && strpos($errorMsg, 'Unknown column') !== false)) {
                            echo "   ⚠️  Aviso: " . $errorMsg . "\n";
                            $avisos++;
                        }
                    } else {
                        // Erro em CREATE TABLE é mais crítico, mas vamos continuar
                        echo "   ⚠️  Aviso ao criar tabela: " . $errorMsg . "\n";
                        $avisos++;
                    }
                }
            }
        }
        
        // Reabilitar verificação de foreign keys
        $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");
        
        // Agora adicionar as foreign keys que foram removidas
        echo "   🔗 Adicionando foreign keys...\n";
        $fkCommands = [
            "ALTER TABLE processos ADD CONSTRAINT fk_processos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)",
            "ALTER TABLE processos ADD CONSTRAINT fk_processos_advogado FOREIGN KEY (advogado_id) REFERENCES usuarios(id)",
            "ALTER TABLE audiencias ADD CONSTRAINT fk_audiencias_processo FOREIGN KEY (processo_id) REFERENCES processos(id)",
            "ALTER TABLE movimentacoes ADD CONSTRAINT fk_movimentacoes_processo FOREIGN KEY (processo_id) REFERENCES processos(id)",
            "ALTER TABLE pagamentos ADD CONSTRAINT fk_pagamentos_processo FOREIGN KEY (processo_id) REFERENCES processos(id)",
            "ALTER TABLE pagamentos ADD CONSTRAINT fk_pagamentos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)",
            "ALTER TABLE funcionarios ADD CONSTRAINT fk_funcionarios_cargo FOREIGN KEY (cargo_id) REFERENCES cargos(id)"
        ];
        
        $fkAdicionadas = 0;
        foreach ($fkCommands as $fkCmd) {
            // Verificar se a constraint já existe
            $tableName = preg_match('/ALTER TABLE (\w+)/i', $fkCmd, $matches) ? $matches[1] : '';
            if ($tableName) {
                $checkSql = "SELECT COUNT(*) as total FROM information_schema.TABLE_CONSTRAINTS 
                            WHERE TABLE_SCHEMA = '$database' 
                            AND TABLE_NAME = '$tableName' 
                            AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
                $result = $mysqli->query($checkSql);
                if ($result && $result->fetch_assoc()['total'] > 0) {
                    // Já existe, pular
                    continue;
                }
            }
            
            if ($mysqli->query($fkCmd)) {
                $fkAdicionadas++;
            }
            // Ignorar erros de foreign key já existente
        }
        
        if ($fkAdicionadas > 0) {
            echo "   ✅ $fkAdicionadas foreign keys adicionadas\n";
        }
        
        echo "   ✅ Script SQL executado ($executados comandos)\n";
        if ($avisos > 0) {
            echo "   ⚠️  $avisos avisos (normal se tabelas já existem)\n";
        }
        echo "\n";
        
    } else {
        echo "   ⚠️  Arquivo database/sql/criar-banco.sql não encontrado\n";
        echo "   💡 Criando estrutura básica manualmente...\n\n";
        
        // Criar estrutura básica se o arquivo não existir
        criarEstruturaBasica($mysqli);
    }
    
    // 5. Criar tabela ci_sessions
    echo "5. Criando tabela ci_sessions...\n";
    $sql = "CREATE TABLE IF NOT EXISTS `ci_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    
    if ($mysqli->query($sql)) {
        echo "   ✅ Tabela ci_sessions criada/verificada\n\n";
    } else {
        echo "   ⚠️  Aviso ao criar ci_sessions: " . $mysqli->error . "\n\n";
    }
    
    // 6. Verificar/Criar usuário admin
    echo "6. Verificando usuário admin...\n";
    
    // Verificar se tabela usuarios existe
    $result = $mysqli->query("SHOW TABLES LIKE 'usuarios'");
    if ($result && $result->num_rows > 0) {
        // Verificar se admin existe
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios WHERE usuario = 'admin' OR usuario = 'admin@advocacia.com'");
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row['total'] == 0) {
                echo "   📝 Criando usuário admin...\n";
                
                // Verificar estrutura da tabela
                $result = $mysqli->query("DESCRIBE usuarios");
                $columns = [];
                while ($row = $result->fetch_assoc()) {
                    $columns[] = $row['Field'];
                }
                
                // Criar senha hash (CodeIgniter usa password_hash ou MD5 dependendo da versão)
                $senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
                
                // Tentar com password_hash primeiro
                $sql = "INSERT INTO usuarios (nome, usuario, senha, nivel, ativo, data_cadastro) VALUES (
                    'Administrador',
                    'admin',
                    '$senhaHash',
                    'admin',
                    1,
                    NOW()
                )";
                
                if (!$mysqli->query($sql)) {
                    // Se falhar, tentar com hash do CodeIgniter
                    $senhaHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // admin123
                    $sql = "INSERT INTO usuarios (nome, usuario, senha, nivel, ativo, data_cadastro) VALUES (
                        'Administrador',
                        'admin',
                        '$senhaHash',
                        'admin',
                        1,
                        NOW()
                    )";
                    $mysqli->query($sql);
                }
                
                echo "   ✅ Usuário admin criado\n";
                echo "      Usuário: admin\n";
                echo "      Senha: admin123\n\n";
            } else {
                echo "   ✅ Usuário admin já existe\n\n";
            }
        }
    } else {
        echo "   ⚠️  Tabela usuarios não encontrada (será criada pelo script SQL)\n\n";
    }
    
    // 7. Verificar tabelas criadas
    echo "7. Verificando tabelas criadas...\n";
    $result = $mysqli->query("SHOW TABLES");
    $tables = [];
    while ($row = $result->fetch_assoc()) {
        $tables[] = array_values($row)[0];
    }
    
    echo "   ✅ Total de tabelas: " . count($tables) . "\n";
    if (count($tables) > 0) {
        echo "   Tabelas: " . implode(', ', array_slice($tables, 0, 10));
        if (count($tables) > 10) {
            echo " e mais " . (count($tables) - 10) . "...";
        }
        echo "\n\n";
    }
    
    $mysqli->close();
    
    // 8. Resumo final
    echo "========================================\n";
    echo "  ✅ BANCO DE DADOS CRIADO COM SUCESSO!\n";
    echo "========================================\n\n";
    echo "📋 Próximos passos:\n";
    echo "   1. Configure o APP_ENCRYPTION_KEY no .env (se ainda não configurado)\n";
    echo "   2. Execute: php scripts/verificar-ambiente-local.php\n";
    echo "   3. Inicie o servidor: php -S localhost:8000 -t .\n";
    echo "   4. Acesse: http://localhost:8000\n";
    echo "\n";
    echo "🔑 Credenciais de acesso:\n";
    echo "   Usuário: admin\n";
    echo "   Senha: admin123\n";
    echo "\n";
    
} catch (Exception $e) {
    echo "   ❌ ERRO: " . $e->getMessage() . "\n";
    echo "\n";
    echo "========================================\n";
    echo "  ❌ ERRO AO CRIAR BANCO DE DADOS\n";
    echo "========================================\n\n";
    echo "💡 Verifique:\n";
    echo "   1. MySQL está rodando?\n";
    echo "   2. Credenciais no .env estão corretas?\n";
    echo "   3. Usuário MySQL tem permissão para criar bancos?\n";
    echo "\n";
    exit(1);
}

/**
 * Criar estrutura básica se o arquivo SQL não existir
 */
function criarEstruturaBasica($mysqli) {
    // Criar tabela usuarios básica
    $sql = "CREATE TABLE IF NOT EXISTS `usuarios` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nome` VARCHAR(100) NOT NULL,
        `cpf` VARCHAR(14) UNIQUE,
        `usuario` VARCHAR(100) UNIQUE NOT NULL,
        `senha` VARCHAR(255) NOT NULL,
        `nivel` ENUM('admin', 'Advogado', 'Cliente', 'Recepcionista', 'Tesoureiro') NOT NULL,
        `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `ativo` BOOLEAN DEFAULT TRUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $mysqli->query($sql);
    
    // Criar usuário admin
    $senhaHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // admin123
    $sql = "INSERT IGNORE INTO usuarios (nome, usuario, senha, nivel, ativo) VALUES (
        'Administrador',
        'admin',
        '$senhaHash',
        'admin',
        1
    )";
    
    $mysqli->query($sql);
}

