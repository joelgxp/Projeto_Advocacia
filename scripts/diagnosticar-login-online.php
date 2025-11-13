<?php
/**
 * Script para diagnosticar problemas de login no servidor online
 * Execute: php scripts/diagnosticar-login-online.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Diagnóstico de Login Online\n";
echo "========================================\n\n";

// Função para ler .env
function getEnvVar($key, $default = '') {
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    
    $env_file = __DIR__ . '/../.env';
    if (file_exists($env_file)) {
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($name, $val) = explode('=', $line, 2);
                $name = trim($name);
                $val = trim($val);
                $val = trim($val, '"\''); // Remove aspas
                
                // Mapeamento Laravel -> CodeIgniter
                if ($key === 'DB_HOSTNAME' && $name === 'DB_HOST') {
                    return $val;
                }
                if ($key === 'DB_USERNAME' && $name === 'DB_USERNAME') {
                    return $val;
                }
                if ($key === 'DB_PASSWORD' && $name === 'DB_PASSWORD') {
                    return $val;
                }
                if ($key === 'DB_DATABASE' && $name === 'DB_DATABASE') {
                    return $val;
                }
                if ($name === $key) {
                    return $val;
                }
            }
        }
    }
    return $default;
}

// 1. Verificar estrutura da tabela usuarios
echo "1. Verificando estrutura da tabela usuarios...\n";

$db_host = getEnvVar('DB_HOSTNAME', 'localhost');
$db_user = getEnvVar('DB_USERNAME', 'root');
$db_pass = getEnvVar('DB_PASSWORD', '');
$db_name = getEnvVar('DB_DATABASE', 'advocacia');
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        echo "   ❌ Erro de conexão: " . $conn->connect_error . "\n";
        echo "   Host: $db_host\n";
        echo "   User: $db_user\n";
        echo "   Database: $db_name\n";
        exit(1);
    }
    
    echo "   ✅ Conexão MySQL OK\n";
    echo "   Host: $db_host\n";
    echo "   User: $db_user\n";
    echo "   Database: $db_name\n";
    
    // Verificar estrutura
    $result = $conn->query("DESCRIBE usuarios");
    if ($result) {
        $campos = array();
        while ($row = $result->fetch_assoc()) {
            $campos[] = $row['Field'];
        }
        echo "   Campos encontrados: " . implode(', ', $campos) . "\n";
        
        $tem_usuario = in_array('usuario', $campos);
        $tem_email = in_array('email', $campos);
        $tem_nivel = in_array('nivel', $campos);
        $tem_permissoes_id = in_array('permissoes_id', $campos);
        $tem_senha = in_array('senha', $campos);
        $tem_ativo = in_array('ativo', $campos);
        
        echo "\n   Verificações:\n";
        echo "   " . ($tem_usuario ? "✅" : "❌") . " Campo 'usuario': " . ($tem_usuario ? "EXISTE" : "NÃO EXISTE") . "\n";
        echo "   " . ($tem_email ? "✅" : "⚠️ ") . " Campo 'email': " . ($tem_email ? "EXISTE" : "NÃO EXISTE") . "\n";
        echo "   " . ($tem_nivel ? "✅" : "❌") . " Campo 'nivel': " . ($tem_nivel ? "EXISTE" : "NÃO EXISTE") . "\n";
        echo "   " . ($tem_permissoes_id ? "✅" : "⚠️ ") . " Campo 'permissoes_id': " . ($tem_permissoes_id ? "EXISTE" : "NÃO EXISTE") . "\n";
        echo "   " . ($tem_senha ? "✅" : "❌") . " Campo 'senha': " . ($tem_senha ? "EXISTE" : "NÃO EXISTE") . "\n";
        echo "   " . ($tem_ativo ? "✅" : "⚠️ ") . " Campo 'ativo': " . ($tem_ativo ? "EXISTE" : "NÃO EXISTE") . "\n";
    }
    
    // 2. Verificar usuários
    echo "\n2. Verificando usuários...\n";
    $result = $conn->query("SELECT id, nome, usuario, email, nivel, permissoes_id, ativo, 
                            CASE WHEN senha IS NULL OR senha = '' THEN 'SEM SENHA' 
                                 WHEN LENGTH(senha) < 20 THEN 'SENHA CURTA (MD5?)' 
                                 ELSE 'SENHA OK' END as status_senha
                            FROM usuarios LIMIT 10");
    if ($result && $result->num_rows > 0) {
        echo "   Usuários encontrados:\n";
        while ($row = $result->fetch_assoc()) {
            echo "   ---\n";
            echo "   ID: {$row['id']}\n";
            echo "   Nome: {$row['nome']}\n";
            echo "   Usuário: " . ($row['usuario'] ?: 'NULL') . "\n";
            echo "   Email: " . ($row['email'] ?: 'NULL') . "\n";
            echo "   Nível: " . ($row['nivel'] ?: 'NULL') . "\n";
            echo "   Permissões ID: " . ($row['permissoes_id'] ?: 'NULL') . "\n";
            echo "   Ativo: " . ($row['ativo'] ?: '0') . "\n";
            echo "   Status Senha: {$row['status_senha']}\n";
        }
    } else {
        echo "   ⚠️  Nenhum usuário encontrado\n";
    }
    
    // 3. Verificar tabela de sessões
    echo "\n3. Verificando tabela de sessões...\n";
    $result = $conn->query("SHOW TABLES LIKE 'ci_sessions'");
    if ($result && $result->num_rows > 0) {
        echo "   ✅ Tabela ci_sessions existe\n";
        
        $result = $conn->query("SELECT COUNT(*) as total FROM ci_sessions");
        $count = $result->fetch_assoc();
        echo "   Sessões ativas: {$count['total']}\n";
    } else {
        echo "   ❌ Tabela ci_sessions NÃO EXISTE!\n";
        echo "   💡 Execute o SQL para criar:\n";
        echo "   CREATE TABLE IF NOT EXISTS `ci_sessions` (\n";
        echo "     `id` varchar(128) NOT NULL,\n";
        echo "     `ip_address` varchar(45) NOT NULL,\n";
        echo "     `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,\n";
        echo "     `data` blob NOT NULL,\n";
        echo "     KEY `ci_sessions_timestamp` (`timestamp`)\n";
        echo "   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
    }
    
    // 4. Verificar logs de erro
    echo "\n4. Verificando logs de erro...\n";
    $log_dir = $root_dir . '/application/logs/';
    if (is_dir($log_dir)) {
        $log_file = $log_dir . 'log-' . date('Y-m-d') . '.php';
        if (file_exists($log_file)) {
            echo "   ✅ Log encontrado: $log_file\n";
            $content = file_get_contents($log_file);
            $content = preg_replace('/^<\?php.*?\?>/s', '', $content);
            $lines = explode("\n", $content);
            $error_lines = array_filter($lines, function($line) {
                return stripos($line, 'error') !== false || 
                       stripos($line, 'login') !== false ||
                       stripos($line, 'exception') !== false;
            });
            if (count($error_lines) > 0) {
                echo "   Últimos erros relacionados a login:\n";
                foreach (array_slice($error_lines, -10) as $line) {
                    if (!empty(trim($line))) {
                        echo "      " . trim($line) . "\n";
                    }
                }
            } else {
                echo "   ℹ️  Nenhum erro relacionado a login encontrado\n";
            }
        } else {
            echo "   ⚠️  Log de hoje não encontrado\n";
        }
    } else {
        echo "   ⚠️  Diretório de logs não encontrado\n";
    }
    
    // 5. Verificar permissões de diretórios
    echo "\n5. Verificando permissões...\n";
    $dirs = array(
        'application/logs' => 'Logs',
        'application/cache' => 'Cache',
        'application/sessions' => 'Sessions'
    );
    
    foreach ($dirs as $dir => $name) {
        $full_path = $root_dir . '/' . $dir;
        if (is_dir($full_path)) {
            $writable = is_writable($full_path);
            echo "   $name: " . ($writable ? "✅ Gravável" : "❌ NÃO Gravável") . "\n";
        } else {
            echo "   $name: ❌ Não existe\n";
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n========================================\n";
echo "✅ Diagnóstico concluído!\n";
echo "========================================\n";

