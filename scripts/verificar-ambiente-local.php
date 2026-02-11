<?php
/**
 * Script de Verificação do Ambiente Local
 * Verifica se o ambiente está preparado para rodar localmente
 */

echo "========================================\n";
echo "  VERIFICAÇÃO DO AMBIENTE LOCAL\n";
echo "  Sistema de Advocacia - CodeIgniter 3\n";
echo "========================================\n\n";

$erros = [];
$avisos = [];
$sucessos = [];

// 1. Verificar PHP
echo "1. Verificando PHP...\n";
if (function_exists('phpversion')) {
    $phpVersion = phpversion();
    echo "   ✅ PHP $phpVersion instalado\n";
    $sucessos[] = "PHP $phpVersion instalado";
    
    // Verificar versão mínima (7.4+)
    if (version_compare($phpVersion, '7.4.0', '<')) {
        $erros[] = "PHP 7.4+ é necessário. Versão atual: $phpVersion";
        echo "   ❌ Versão muito antiga (requer 7.4+)\n";
    }
} else {
    $erros[] = "PHP não encontrado";
    echo "   ❌ PHP não encontrado!\n";
}

// 2. Verificar extensões PHP necessárias
echo "\n2. Verificando extensões PHP...\n";
$extensoes = ['mysqli', 'pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl'];
foreach ($extensoes as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✅ $ext\n";
    } else {
        $erros[] = "Extensão PHP '$ext' não está instalada";
        echo "   ❌ $ext não encontrada\n";
    }
}

// 3. Verificar arquivo .env
echo "\n3. Verificando arquivo .env...\n";
if (file_exists('.env')) {
    echo "   ✅ Arquivo .env existe\n";
    $sucessos[] = "Arquivo .env existe";
    
    // Ler configurações importantes
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
    
    // Verificar APP_ENCRYPTION_KEY
    if (empty($configs['APP_ENCRYPTION_KEY']) || $configs['APP_ENCRYPTION_KEY'] === '') {
        $avisos[] = "APP_ENCRYPTION_KEY não configurado";
        echo "   ⚠️  APP_ENCRYPTION_KEY não configurado\n";
    } else {
        echo "   ✅ APP_ENCRYPTION_KEY configurado\n";
    }
    
    // Verificar configurações do banco
    $dbConfigs = ['DB_HOSTNAME', 'DB_USERNAME', 'DB_DATABASE'];
    foreach ($dbConfigs as $config) {
        if (isset($configs[$config]) && !empty($configs[$config])) {
            echo "   ✅ $config configurado\n";
        } else {
            $avisos[] = "$config não configurado no .env";
            echo "   ⚠️  $config não configurado\n";
        }
    }
    
    // Verificar APP_BASEURL
    if (isset($configs['APP_BASEURL']) && !empty($configs['APP_BASEURL'])) {
        echo "   ✅ APP_BASEURL configurado: {$configs['APP_BASEURL']}\n";
    } else {
        $avisos[] = "APP_BASEURL não configurado";
        echo "   ⚠️  APP_BASEURL não configurado\n";
    }
    
} else {
    $erros[] = "Arquivo .env não encontrado. Copie env.example para .env";
    echo "   ❌ Arquivo .env não encontrado!\n";
    echo "   💡 Execute: Copy-Item env.example .env\n";
}

// 4. Verificar CodeIgniter
echo "\n4. Verificando CodeIgniter...\n";
if (file_exists('system/core/CodeIgniter.php')) {
    echo "   ✅ CodeIgniter encontrado\n";
    $sucessos[] = "CodeIgniter instalado";
} else {
    $erros[] = "CodeIgniter não encontrado em system/core/CodeIgniter.php";
    echo "   ❌ CodeIgniter não encontrado!\n";
}

// 5. Verificar dependências Composer
echo "\n5. Verificando dependências Composer...\n";
if (file_exists('vendor/autoload.php')) {
    echo "   ✅ Dependências instaladas (vendor/autoload.php existe)\n";
    $sucessos[] = "Dependências Composer instaladas";
} else {
    $avisos[] = "Execute: composer install";
    echo "   ⚠️  Dependências não instaladas\n";
    echo "   💡 Execute: composer install\n";
}

// 6. Verificar diretórios necessários
echo "\n6. Verificando diretórios necessários...\n";
$diretorios = [
    'application/logs',
    'application/cache',
    'application/sessions',
    'public/css/webfonts'
];

foreach ($diretorios as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir);
        if ($writable) {
            echo "   ✅ $dir (gravável)\n";
        } else {
            $avisos[] = "Diretório $dir não é gravável";
            echo "   ⚠️  $dir não é gravável\n";
        }
    } else {
        $avisos[] = "Diretório $dir não existe";
        echo "   ⚠️  $dir não existe\n";
    }
}

// 7. Verificar conexão com banco de dados
echo "\n7. Testando conexão com banco de dados...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    $envLines = explode("\n", $envContent);
    $dbConfig = [];
    
    foreach ($envLines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $dbConfig[trim($key)] = trim($value);
        }
    }
    
    $host = $dbConfig['DB_HOSTNAME'] ?? 'localhost';
    $user = $dbConfig['DB_USERNAME'] ?? 'root';
    $pass = $dbConfig['DB_PASSWORD'] ?? '';
    $database = $dbConfig['DB_DATABASE'] ?? 'advocacia';
    
    try {
        $mysqli = new mysqli($host, $user, $pass, $database);
        
        if ($mysqli->connect_error) {
            $erros[] = "Erro de conexão: " . $mysqli->connect_error;
            echo "   ❌ Erro de conexão: {$mysqli->connect_error}\n";
        } else {
            echo "   ✅ Conexão estabelecida com sucesso\n";
            $sucessos[] = "Conexão com banco de dados OK";
            
            // Verificar tabela de sessões
            $result = $mysqli->query("SHOW TABLES LIKE 'ci_sessions'");
            if ($result && $result->num_rows > 0) {
                echo "   ✅ Tabela ci_sessions existe\n";
            } else {
                $avisos[] = "Tabela ci_sessions não existe. Execute o SQL de criação";
                echo "   ⚠️  Tabela ci_sessions não existe\n";
            }
            
            // Verificar usuário admin
            $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios WHERE usuario = 'admin'");
            if ($result) {
                $row = $result->fetch_assoc();
                if ($row['total'] > 0) {
                    echo "   ✅ Usuário admin existe\n";
                } else {
                    $avisos[] = "Usuário admin não encontrado. Execute o SQL de criação";
                    echo "   ⚠️  Usuário admin não encontrado\n";
                }
            }
            
            $mysqli->close();
        }
    } catch (Exception $e) {
        $erros[] = "Erro ao conectar: " . $e->getMessage();
        echo "   ❌ Erro: {$e->getMessage()}\n";
    }
} else {
    $avisos[] = "Não foi possível testar conexão (arquivo .env não existe)";
    echo "   ⚠️  Não foi possível testar (arquivo .env não existe)\n";
}

// 8. Verificar arquivo index.php
echo "\n8. Verificando arquivo index.php...\n";
if (file_exists('index.php')) {
    echo "   ✅ index.php existe\n";
    $sucessos[] = "Arquivo index.php existe";
} else {
    $erros[] = "Arquivo index.php não encontrado";
    echo "   ❌ index.php não encontrado!\n";
}

// Resumo
echo "\n";
echo "========================================\n";
echo "  RESUMO DA VERIFICAÇÃO\n";
echo "========================================\n\n";

if (count($sucessos) > 0) {
    echo "✅ Sucessos (" . count($sucessos) . "):\n";
    foreach ($sucessos as $sucesso) {
        echo "   • $sucesso\n";
    }
    echo "\n";
}

if (count($avisos) > 0) {
    echo "⚠️  Avisos (" . count($avisos) . "):\n";
    foreach ($avisos as $aviso) {
        echo "   • $aviso\n";
    }
    echo "\n";
}

if (count($erros) > 0) {
    echo "❌ Erros (" . count($erros) . "):\n";
    foreach ($erros as $erro) {
        echo "   • $erro\n";
    }
    echo "\n";
}

// Conclusão
echo "========================================\n";
if (count($erros) === 0) {
    echo "✅ AMBIENTE PRONTO PARA RODAR LOCALMENTE!\n";
    echo "\n";
    echo "Para iniciar o servidor:\n";
    echo "   php -S localhost:8000 -t .\n";
    echo "\n";
    echo "Ou use o script:\n";
    echo "   .\\scripts\\iniciar-local.ps1\n";
    echo "\n";
    echo "Acesse: http://localhost:8000\n";
    exit(0);
} else {
    echo "❌ CORRIJA OS ERROS ANTES DE CONTINUAR\n";
    echo "\n";
    echo "Consulte o arquivo RODAR_LOCAL.md para instruções detalhadas.\n";
    exit(1);
}

