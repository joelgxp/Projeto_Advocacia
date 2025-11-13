<?php
/**
 * Script para verificar erros 500 no servidor
 * Execute: php scripts/verificar-erro-500.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Diagnóstico de Erro 500\n";
echo "========================================\n\n";

// 1. Verificar logs
echo "1. Verificando logs de erro...\n";
$log_file = $root_dir . '/application/logs/log-' . date('Y-m-d') . '.php';
if (file_exists($log_file)) {
    echo "   ✅ Log encontrado: $log_file\n";
    $log_content = file_get_contents($log_file);
    $lines = explode("\n", $log_content);
    $recent_errors = array_slice($lines, -20);
    echo "   Últimas 5 linhas de erro:\n";
    foreach (array_slice($recent_errors, -5) as $line) {
        if (!empty(trim($line))) {
            echo "   " . trim($line) . "\n";
        }
    }
} else {
    echo "   ⚠️  Log não encontrado (pode não ter erros ainda)\n";
}

// 2. Verificar tabelas
echo "\n2. Verificando tabelas do banco...\n";
require_once $root_dir . '/application/config/database.php';

if (function_exists('getEnvVar')) {
    $db_host = getEnvVar('DB_HOSTNAME', 'localhost');
    $db_user = getEnvVar('DB_USERNAME', 'root');
    $db_pass = getEnvVar('DB_PASSWORD', '');
    $db_name = getEnvVar('DB_DATABASE', 'advocacia');
    
    try {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if (!$conn->connect_error) {
            $tables = array('usuarios', 'permissoes', 'ci_sessions', 'configuracoes');
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result && $result->num_rows > 0) {
                    echo "   ✅ Tabela $table existe\n";
                    
                    // Verificar se tem dados
                    $count = $conn->query("SELECT COUNT(*) as total FROM $table")->fetch_assoc();
                    echo "      Registros: " . $count['total'] . "\n";
                } else {
                    echo "   ❌ Tabela $table não existe\n";
                }
            }
            $conn->close();
        }
    } catch (Exception $e) {
        echo "   ❌ Erro: " . $e->getMessage() . "\n";
    }
}

// 3. Verificar usuários
echo "\n3. Verificando usuários...\n";
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        $result = $conn->query("SELECT id, nome, email, permissoes_id, ativo FROM usuarios LIMIT 5");
        if ($result && $result->num_rows > 0) {
            echo "   Usuários encontrados:\n";
            while ($row = $result->fetch_assoc()) {
                echo "   - ID: {$row['id']}, Nome: {$row['nome']}, Email: {$row['email']}, Permissões ID: " . ($row['permissoes_id'] ?: 'NULL') . ", Ativo: {$row['ativo']}\n";
            }
        } else {
            echo "   ⚠️  Nenhum usuário encontrado\n";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

// 4. Verificar permissões
echo "\n4. Verificando grupos de permissões...\n";
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        $result = $conn->query("SELECT idPermissoes, nome, situacao FROM permissoes");
        if ($result && $result->num_rows > 0) {
            echo "   Grupos de permissões encontrados:\n";
            while ($row = $result->fetch_assoc()) {
                echo "   - ID: {$row['idPermissoes']}, Nome: {$row['nome']}, Situação: {$row['situacao']}\n";
            }
        } else {
            echo "   ⚠️  Nenhum grupo de permissões encontrado\n";
            echo "   💡 Você precisa criar grupos de permissões!\n";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "✅ Diagnóstico concluído!\n";
echo "========================================\n";

