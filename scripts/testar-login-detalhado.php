<?php
/**
 * Script para testar login detalhadamente
 * Execute: php scripts/testar-login-detalhado.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

// Definir ambiente
define('ENVIRONMENT', 'production');
define('BASEPATH', $root_dir . '/system/');
define('APPPATH', $root_dir . '/application/');
define('FCPATH', $root_dir . '/');

// Carregar CodeIgniter manualmente
require_once BASEPATH . 'core/Common.php';

// Carregar configuração
if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
    require APPPATH . 'config/' . ENVIRONMENT . '/config.php';
} else {
    require APPPATH . 'config/config.php';
}

echo "========================================\n";
echo "  Teste Detalhado de Login\n";
echo "========================================\n\n";

// 1. Verificar estrutura da tabela usuarios
echo "1. Verificando estrutura da tabela usuarios...\n";
require_once $root_dir . '/application/config/database.php';

if (function_exists('getEnvVar')) {
    $db_host = getEnvVar('DB_HOSTNAME', 'localhost');
    $db_user = getEnvVar('DB_USERNAME', 'root');
    $db_pass = getEnvVar('DB_PASSWORD', '');
    $db_name = getEnvVar('DB_DATABASE', 'advocacia');
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            echo "   ❌ Erro de conexão: " . $conn->connect_error . "\n";
            exit(1);
        }
        
        echo "   ✅ Conexão MySQL OK\n";
        
        // Verificar estrutura da tabela
        $result = $conn->query("DESCRIBE usuarios");
        if ($result) {
            echo "   Colunas da tabela usuarios:\n";
            while ($row = $result->fetch_assoc()) {
                echo "      - {$row['Field']} ({$row['Type']}) " . ($row['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
            }
        }
        
        // Verificar usuários
        echo "\n2. Verificando usuários...\n";
        $result = $conn->query("SELECT id, nome, email, permissoes_id, ativo, senha IS NOT NULL as tem_senha FROM usuarios LIMIT 5");
        if ($result && $result->num_rows > 0) {
            echo "   Usuários encontrados:\n";
            while ($row = $result->fetch_assoc()) {
                echo "      ID: {$row['id']}\n";
                echo "      Nome: {$row['nome']}\n";
                echo "      Email: {$row['email']}\n";
                echo "      Permissões ID: " . ($row['permissoes_id'] ?: 'NULL') . "\n";
                echo "      Ativo: {$row['ativo']}\n";
                echo "      Tem senha: " . ($row['tem_senha'] ? 'Sim' : 'Não') . "\n";
                echo "      ---\n";
            }
        } else {
            echo "   ⚠️  Nenhum usuário encontrado\n";
        }
        
        // Verificar grupos de permissões
        echo "\n3. Verificando grupos de permissões...\n";
        $result = $conn->query("SELECT idPermissoes, nome, situacao FROM permissoes");
        if ($result && $result->num_rows > 0) {
            echo "   Grupos encontrados:\n";
            while ($row = $result->fetch_assoc()) {
                echo "      ID: {$row['idPermissoes']}, Nome: {$row['nome']}, Situação: {$row['situacao']}\n";
            }
        } else {
            echo "   ⚠️  Nenhum grupo de permissões encontrado\n";
            echo "   💡 Execute: mysql -u $db_user -p $db_name < scripts/criar-dados-iniciais.sql\n";
        }
        
        // Testar query de login
        echo "\n4. Testando query de login...\n";
        $test_email = 'admin@example.com'; // Substitua por um email real
        $result = $conn->query("SELECT id, nome, email, permissoes_id, ativo, senha FROM usuarios WHERE email = '" . $conn->real_escape_string($test_email) . "' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "   ✅ Usuário encontrado para email: $test_email\n";
            echo "      ID: {$user['id']}\n";
            echo "      Permissões ID: " . ($user['permissoes_id'] ?: 'NULL') . "\n";
            echo "      Ativo: {$user['ativo']}\n";
            echo "      Senha hash: " . (empty($user['senha']) ? 'VAZIA' : 'PRESENTE (' . strlen($user['senha']) . ' chars)') . "\n";
        } else {
            echo "   ⚠️  Nenhum usuário encontrado para email: $test_email\n";
        }
        
        $conn->close();
    } catch (Exception $e) {
        echo "   ❌ Erro: " . $e->getMessage() . "\n";
        echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
}

// 5. Verificar logs de erro
echo "\n5. Verificando logs de erro...\n";
$log_dir = $root_dir . '/application/logs/';
if (is_dir($log_dir)) {
    $log_file = $log_dir . 'log-' . date('Y-m-d') . '.php';
    if (file_exists($log_file)) {
        echo "   ✅ Log encontrado: $log_file\n";
        $content = file_get_contents($log_file);
        // Remover header PHP do log
        $content = preg_replace('/^<\?php.*?\?>/s', '', $content);
        $lines = explode("\n", $content);
        $error_lines = array_filter($lines, function($line) {
            return stripos($line, 'error') !== false || 
                   stripos($line, 'exception') !== false ||
                   stripos($line, 'fatal') !== false;
        });
        if (count($error_lines) > 0) {
            echo "   Últimos erros encontrados:\n";
            foreach (array_slice($error_lines, -10) as $line) {
                if (!empty(trim($line))) {
                    echo "      " . trim($line) . "\n";
                }
            }
        } else {
            echo "   ℹ️  Nenhum erro encontrado no log de hoje\n";
        }
    } else {
        echo "   ⚠️  Log de hoje não encontrado\n";
    }
} else {
    echo "   ⚠️  Diretório de logs não encontrado\n";
}

// 6. Verificar permissões de diretórios
echo "\n6. Verificando permissões...\n";
$dirs = array(
    'application/logs' => 'Logs',
    'application/cache' => 'Cache',
    'application/sessions' => 'Sessions'
);

foreach ($dirs as $dir => $name) {
    $full_path = $root_dir . '/' . $dir;
    if (is_dir($full_path)) {
        $perms = substr(sprintf('%o', fileperms($full_path)), -4);
        $writable = is_writable($full_path);
        echo "   $name: $full_path\n";
        echo "      Permissões: $perms, Gravável: " . ($writable ? 'Sim' : 'Não') . "\n";
    } else {
        echo "   $name: $full_path (não existe)\n";
    }
}

echo "\n========================================\n";
echo "✅ Diagnóstico concluído!\n";
echo "========================================\n";

