<?php
/**
 * Script para testar login e identificar erros
 * Execute: php scripts/testar-login.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

// Carregar CodeIgniter
define('ENVIRONMENT', 'production');
define('BASEPATH', $root_dir . '/system/');
define('APPPATH', $root_dir . '/application/');
define('FCPATH', $root_dir . '/');

require_once BASEPATH . 'core/CodeIgniter.php';

// Mas vamos fazer um teste mais simples primeiro
echo "========================================\n";
echo "  Teste de Login - Diagnóstico\n";
echo "========================================\n\n";

// 1. Verificar arquivos
echo "1. Verificando arquivos...\n";
$files = array(
    'application/controllers/Login.php',
    'application/models/Usuario_model.php',
    'application/models/Permissao_model.php',
    'application/config/database.php'
);

foreach ($files as $file) {
    if (file_exists($root_dir . '/' . $file)) {
        echo "   ✅ $file\n";
    } else {
        echo "   ❌ $file (não encontrado)\n";
    }
}

// 2. Testar conexão MySQL
echo "\n2. Testando conexão MySQL...\n";
require_once $root_dir . '/application/config/database.php';

// Executar função getEnvVar
if (function_exists('getEnvVar')) {
    $db_host = getEnvVar('DB_HOSTNAME', 'localhost');
    $db_user = getEnvVar('DB_USERNAME', 'root');
    $db_pass = getEnvVar('DB_PASSWORD', '');
    $db_name = getEnvVar('DB_DATABASE', 'advocacia');
    
    echo "   Host: $db_host\n";
    echo "   User: $db_user\n";
    echo "   Database: $db_name\n";
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            echo "   ❌ Erro: " . $conn->connect_error . "\n";
        } else {
            echo "   ✅ Conexão MySQL OK\n";
            $conn->close();
        }
    } catch (Exception $e) {
        echo "   ❌ Erro: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ⚠️  Função getEnvVar não encontrada\n";
}

// 3. Verificar tabelas
echo "\n3. Verificando tabelas...\n";
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        $tables = array('usuarios', 'permissoes', 'ci_sessions');
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                echo "   ✅ Tabela $table existe\n";
            } else {
                echo "   ❌ Tabela $table não existe\n";
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "✅ Diagnóstico concluído!\n";
echo "========================================\n";

