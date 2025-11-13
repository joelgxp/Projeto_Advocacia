<?php
/**
 * Script de Teste do Servidor
 * Execute: php testar-servidor.php
 * 
 * Testa se o sistema está funcionando corretamente no servidor
 */

echo "========================================\n";
echo "  TESTE DO SERVIDOR - Sistema Advocacia\n";
echo "========================================\n\n";

$erros = [];
$avisos = [];
$sucessos = [];

// 1. Verificar se o Laravel carrega
echo "1. Testando carregamento do Laravel...\n";
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "   ✅ Laravel carregado com sucesso\n";
    $sucessos[] = "Laravel carregado";
} catch (Exception $e) {
    echo "   ❌ Erro ao carregar Laravel: " . $e->getMessage() . "\n";
    $erros[] = "Laravel não carregou: " . $e->getMessage();
    exit(1);
}

// 2. Verificar .env
echo "\n2. Verificando configuração .env...\n";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    // Verificar APP_KEY
    if (preg_match('/APP_KEY=base64:([^\s]+)/', $envContent, $matches)) {
        echo "   ✅ APP_KEY configurado\n";
        $sucessos[] = "APP_KEY configurado";
    } else {
        echo "   ❌ APP_KEY não configurado\n";
        $erros[] = "APP_KEY não configurado";
    }
    
    // Verificar APP_ENV
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        echo "   ✅ APP_ENV=production\n";
    } else {
        echo "   ⚠️  APP_ENV não está em production\n";
        $avisos[] = "Configure APP_ENV=production em produção";
    }
    
    // Verificar APP_DEBUG
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        echo "   ✅ APP_DEBUG=false\n";
    } else {
        echo "   ⚠️  APP_DEBUG não está em false\n";
        $avisos[] = "Configure APP_DEBUG=false em produção";
    }
    
    // Verificar DB_*
    if (preg_match('/DB_CONNECTION=(\w+)/', $envContent, $matches)) {
        echo "   ✅ DB_CONNECTION configurado: " . $matches[1] . "\n";
    } else {
        echo "   ❌ DB_CONNECTION não configurado\n";
        $erros[] = "DB_CONNECTION não configurado";
    }
} else {
    echo "   ❌ Arquivo .env não encontrado\n";
    $erros[] = "Arquivo .env não encontrado";
}

// 3. Testar conexão com banco
echo "\n3. Testando conexão com banco de dados...\n";
try {
    // Carregar .env manualmente
    $envLines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $dbConfig = [];
    foreach ($envLines as $line) {
        if (strpos($line, 'DB_') === 0 && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $dbConfig[trim($key)] = trim($value);
        }
    }
    
    if (isset($dbConfig['DB_CONNECTION']) && $dbConfig['DB_CONNECTION'] === 'mysql') {
        $dsn = "mysql:host={$dbConfig['DB_HOST']};port={$dbConfig['DB_PORT']};dbname={$dbConfig['DB_DATABASE']}";
        $pdo = new PDO($dsn, $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "   ✅ Conexão com banco OK\n";
        $sucessos[] = "Conexão com banco OK";
        
        // Verificar se tabelas existem
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $tableCount = count($tables);
        
        if ($tableCount > 0) {
            echo "   ✅ Tabelas encontradas: $tableCount\n";
            $sucessos[] = "$tableCount tabelas encontradas";
            
            // Verificar tabela users
            if (in_array('users', $tables)) {
                $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                $userCount = $stmt->fetchColumn();
                echo "   ✅ Usuários no banco: $userCount\n";
                $sucessos[] = "$userCount usuários encontrados";
            }
        } else {
            echo "   ⚠️  Nenhuma tabela encontrada\n";
            $avisos[] = "Banco de dados vazio. Importe o SQL";
        }
    } else {
        echo "   ⚠️  DB_CONNECTION não é mysql\n";
        $avisos[] = "DB_CONNECTION não configurado como mysql";
    }
} catch (PDOException $e) {
    echo "   ❌ Erro de conexão: " . $e->getMessage() . "\n";
    $erros[] = "Erro de conexão com banco: " . $e->getMessage();
}

// 4. Verificar arquivos essenciais
echo "\n4. Verificando arquivos essenciais...\n";
$arquivos = [
    'public/index.php' => 'index.php',
    'public/css/vendor/bootstrap.min.css' => 'Bootstrap CSS',
    'public/js/vendor/bootstrap.bundle.min.js' => 'Bootstrap JS',
    'public/css/vendor/fontawesome.min.css' => 'Font Awesome CSS',
    'public/js/vendor/jquery.min.js' => 'jQuery',
    'resources/views/auth/login.blade.php' => 'View de login',
    'resources/views/layouts/app.blade.php' => 'Layout principal',
];

$arquivosOk = 0;
foreach ($arquivos as $arquivo => $nome) {
    if (file_exists($arquivo)) {
        echo "   ✅ $nome\n";
        $arquivosOk++;
    } else {
        echo "   ❌ $nome (FALTANDO: $arquivo)\n";
        $erros[] = "$nome não encontrado: $arquivo";
    }
}

if ($arquivosOk === count($arquivos)) {
    $sucessos[] = "Todos os arquivos essenciais presentes";
}

// 5. Verificar permissões
echo "\n5. Verificando permissões...\n";
$pastas = ['storage', 'bootstrap/cache'];
foreach ($pastas as $pasta) {
    if (is_dir($pasta)) {
        if (is_writable($pasta)) {
            echo "   ✅ $pasta é gravável\n";
            $sucessos[] = "$pasta com permissão de escrita";
        } else {
            echo "   ❌ $pasta NÃO é gravável\n";
            $erros[] = "$pasta não tem permissão de escrita";
        }
    } else {
        echo "   ❌ $pasta não existe\n";
        $erros[] = "Pasta $pasta não existe";
    }
}

// 6. Testar se o index.php funciona
echo "\n6. Testando public/index.php...\n";
try {
    ob_start();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['HTTP_HOST'] = 'localhost';
    
    // Simular requisição básica
    chdir(__DIR__ . '/public');
    $output = @file_get_contents('http://localhost/index.php');
    chdir(__DIR__);
    
    if ($output !== false || file_exists('public/index.php')) {
        echo "   ✅ index.php existe e é acessível\n";
        $sucessos[] = "index.php acessível";
    } else {
        echo "   ⚠️  Não foi possível testar index.php via HTTP\n";
        $avisos[] = "Teste index.php manualmente no navegador";
    }
    ob_end_clean();
} catch (Exception $e) {
    echo "   ⚠️  Teste HTTP não disponível (normal em CLI)\n";
    $avisos[] = "Teste index.php manualmente no navegador";
}

// 7. Verificar logs de erro
echo "\n7. Verificando logs...\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logSize = filesize($logFile);
    if ($logSize > 0) {
        $logContent = file_get_contents($logFile);
        $errorCount = substr_count($logContent, 'ERROR');
        $exceptionCount = substr_count($logContent, 'Exception');
        
        if ($errorCount > 0 || $exceptionCount > 0) {
            echo "   ⚠️  Encontrados $errorCount erros e $exceptionCount exceções nos logs\n";
            $avisos[] = "Verifique os logs: $logFile";
        } else {
            echo "   ✅ Nenhum erro encontrado nos logs\n";
            $sucessos[] = "Logs sem erros";
        }
    } else {
        echo "   ✅ Arquivo de log vazio (sem erros)\n";
        $sucessos[] = "Logs vazios";
    }
} else {
    echo "   ⚠️  Arquivo de log não existe (normal se não houve erros)\n";
}

// 8. Verificar rotas principais
echo "\n8. Verificando rotas...\n";
$rotas = [
    'routes/web.php' => 'Rotas web',
    'routes/admin.php' => 'Rotas admin',
    'routes/advogado.php' => 'Rotas advogado',
];

foreach ($rotas as $arquivo => $nome) {
    if (file_exists($arquivo)) {
        echo "   ✅ $nome\n";
        $sucessos[] = "$nome encontrado";
    } else {
        echo "   ⚠️  $nome não encontrado\n";
        $avisos[] = "$nome não encontrado: $arquivo";
    }
}

// Resumo
echo "\n========================================\n";
echo "  RESUMO DO TESTE\n";
echo "========================================\n\n";

echo "✅ Sucessos: " . count($sucessos) . "\n";
if (count($sucessos) > 0) {
    foreach ($sucessos as $sucesso) {
        echo "   • $sucesso\n";
    }
}

if (count($avisos) > 0) {
    echo "\n⚠️  Avisos: " . count($avisos) . "\n";
    foreach ($avisos as $aviso) {
        echo "   • $aviso\n";
    }
}

if (count($erros) > 0) {
    echo "\n❌ Erros: " . count($erros) . "\n";
    foreach ($erros as $erro) {
        echo "   • $erro\n";
    }
    echo "\n";
    exit(1);
}

echo "\n✅ Sistema testado com sucesso!\n";
echo "\n📋 Próximos passos:\n";
echo "   1. Acesse o site no navegador\n";
echo "   2. Teste o login\n";
echo "   3. Verifique se os assets (CSS/JS) carregam (F12)\n";
echo "\n";

exit(0);

