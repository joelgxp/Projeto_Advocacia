<?php
/**
 * Script para testar leitura do .env no servidor
 * Execute: php scripts/testar-env.php
 */

$root_dir = __DIR__ . '/..';
$env_file = $root_dir . '/.env';

echo "========================================\n";
echo "  Teste de Leitura do .env\n";
echo "========================================\n\n";

// Verificar se arquivo existe
echo "1. Verificando arquivo .env...\n";
if (file_exists($env_file)) {
    echo "   ✅ Arquivo .env encontrado: $env_file\n";
    echo "   Permissões: " . substr(sprintf('%o', fileperms($env_file)), -4) . "\n";
} else {
    echo "   ❌ Arquivo .env NÃO encontrado em: $env_file\n";
    exit(1);
}

// Ler arquivo .env
echo "\n2. Lendo variáveis do .env...\n";
$env_vars = array();
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Ignorar comentários e linhas vazias
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Separar chave e valor
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remover aspas
            $value = trim($value, '"\'');
            $env_vars[$key] = $value;
        }
    }
}

// Mostrar variáveis importantes
$important_vars = array(
    'APP_ENVIRONMENT',
    'APP_BASEURL',
    'DB_HOSTNAME',
    'DB_USERNAME',
    'DB_DATABASE',
    'DB_DRIVER'
);

echo "\n3. Variáveis importantes:\n";
foreach ($important_vars as $var) {
    if (isset($env_vars[$var])) {
        $value = $env_vars[$var];
        // Ocultar senha
        if ($var === 'DB_PASSWORD') {
            $value = str_repeat('*', strlen($value));
        }
        echo "   ✅ $var = $value\n";
    } else {
        echo "   ❌ $var = (não encontrado)\n";
    }
}

// Testar função getEnvVar (simulando database.php)
echo "\n4. Testando função getEnvVar()...\n";
function getEnvVar($key, $default = '') {
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    
    $env_file = __DIR__ . '/../.env';
    if (file_exists($env_file)) {
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($name, $val) = explode('=', $line, 2);
                $name = trim($name);
                $val = trim($val);
                if ($name === $key) {
                    $val = trim($val, '"\'');
                    return $val;
                }
            }
        }
    }
    
    return $default;
}

echo "   DB_HOSTNAME: " . getEnvVar('DB_HOSTNAME', 'não encontrado') . "\n";
echo "   DB_USERNAME: " . getEnvVar('DB_USERNAME', 'não encontrado') . "\n";
echo "   DB_DATABASE: " . getEnvVar('DB_DATABASE', 'não encontrado') . "\n";

// Testar conexão MySQL
echo "\n5. Testando conexão MySQL...\n";
$db_host = getEnvVar('DB_HOSTNAME', 'localhost');
$db_user = getEnvVar('DB_USERNAME', 'root');
$db_pass = getEnvVar('DB_PASSWORD', '');
$db_name = getEnvVar('DB_DATABASE', 'advocacia');

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        echo "   ❌ Erro de conexão: " . $conn->connect_error . "\n";
    } else {
        echo "   ✅ Conexão MySQL bem-sucedida!\n";
        $result = $conn->query("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = '$db_name'");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "   ✅ Tabelas encontradas: " . $row['total'] . "\n";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "   ❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "✅ Teste concluído!\n";
echo "========================================\n";

