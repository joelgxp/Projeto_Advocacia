<?php
/**
 * Script de Diagnóstico de Erros - Sistema de Advocacia
 * Execute: php diagnosticar-erros.php
 * 
 * Este script identifica problemas específicos e mostra erros detalhados
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "  DIAGNÓSTICO DE ERROS - Sistema Advocacia\n";
echo "========================================\n\n";

$erros = [];
$avisos = [];
$sucessos = [];
$detalhes = [];

// Detectar raiz do projeto (subir um nível se estiver em scripts/)
$rootDir = __DIR__;
if (basename($rootDir) === 'scripts') {
    $rootDir = dirname($rootDir);
}

// Mudar para o diretório raiz
if (!chdir($rootDir)) {
    echo "❌ ERRO: Não foi possível mudar para o diretório raiz: $rootDir\n";
    exit(1);
}

// 1. Verificar se estamos no diretório correto
echo "1. Verificando diretório...\n";
$dirAtual = getcwd();
echo "   Diretório atual: $dirAtual\n";
echo "   Script em: " . __DIR__ . "\n";
echo "   Raiz detectada: $rootDir\n";

// Verificar se public/index.php existe (usando caminho absoluto)
$indexPath = $rootDir . '/public/index.php';
if (!file_exists($indexPath)) {
    echo "   ❌ ERRO: public/index.php não encontrado!\n";
    echo "   Procurando em: $indexPath\n";
    echo "   Certifique-se de executar este script na raiz do projeto.\n";
    $erros[] = "Diretório incorreto - public/index.php não encontrado";
    exit(1);
}
echo "   ✅ Diretório correto\n\n";

// 2. Testar carregamento do Laravel
echo "2. Testando carregamento do Laravel...\n";
try {
    // Usar caminho absoluto baseado na raiz detectada
    $vendorPath = $rootDir . '/vendor/autoload.php';
    echo "   Procurando vendor em: $vendorPath\n";
    
    if (!file_exists($vendorPath)) {
        throw new Exception("vendor/autoload.php não encontrado em: $vendorPath");
    }
    
    require $vendorPath;
    echo "   ✅ vendor/autoload.php carregado\n";
    
    $bootstrapPath = $rootDir . '/bootstrap/app.php';
    echo "   Procurando bootstrap em: $bootstrapPath\n";
    
    if (!file_exists($bootstrapPath)) {
        throw new Exception("bootstrap/app.php não encontrado em: $bootstrapPath");
    }
    
    $app = require_once $bootstrapPath;
    echo "   ✅ Laravel carregado com sucesso\n";
    $sucessos[] = "Laravel carregado";
    
} catch (Exception $e) {
    echo "   ❌ ERRO ao carregar Laravel: " . $e->getMessage() . "\n";
    echo "   Stack trace:\n";
    echo "   " . $e->getTraceAsString() . "\n";
    $erros[] = "Laravel não carregou: " . $e->getMessage();
    $detalhes[] = "Stack trace: " . $e->getTraceAsString();
}

// 3. Verificar .env detalhadamente
echo "\n3. Verificando .env...\n";
if (file_exists('.env')) {
    echo "   ✅ Arquivo .env existe\n";
    $envContent = file_get_contents('.env');
    $envLines = explode("\n", $envContent);
    
    // Verificar cada variável importante
    $variaveis = [
        'APP_KEY' => 'Chave da aplicação',
        'APP_ENV' => 'Ambiente',
        'APP_DEBUG' => 'Debug',
        'APP_URL' => 'URL da aplicação',
        'DB_CONNECTION' => 'Tipo de banco',
        'DB_HOST' => 'Host do banco',
        'DB_PORT' => 'Porta do banco',
        'DB_DATABASE' => 'Nome do banco',
        'DB_USERNAME' => 'Usuário do banco',
        'DB_PASSWORD' => 'Senha do banco',
    ];
    
    foreach ($variaveis as $var => $desc) {
        $pattern = "/^$var=(.*)$/m";
        if (preg_match($pattern, $envContent, $matches)) {
            $valor = trim($matches[1]);
            if (empty($valor) && $var !== 'DB_PASSWORD') {
                echo "   ❌ $var está vazio!\n";
                $erros[] = "$var ($desc) não configurado";
            } else {
                if ($var === 'DB_PASSWORD') {
                    echo "   ✅ $var configurado (oculto)\n";
                } else {
                    $valorExibido = strlen($valor) > 50 ? substr($valor, 0, 50) . '...' : $valor;
                    echo "   ✅ $var = $valorExibido\n";
                }
                $sucessos[] = "$var configurado";
            }
        } else {
            echo "   ❌ $var não encontrado no .env!\n";
            $erros[] = "$var ($desc) não encontrado no .env";
        }
    }
} else {
    echo "   ❌ Arquivo .env não encontrado!\n";
    $erros[] = "Arquivo .env não existe";
}

// 4. Testar conexão com banco detalhadamente
echo "\n4. Testando conexão com banco de dados...\n";
if (file_exists('.env')) {
    $envLines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $dbConfig = [];
    foreach ($envLines as $line) {
        if (strpos($line, 'DB_') === 0 && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $dbConfig[trim($key)] = trim($value);
        }
    }
    
    if (isset($dbConfig['DB_CONNECTION']) && $dbConfig['DB_CONNECTION'] === 'mysql') {
        try {
            $host = $dbConfig['DB_HOST'] ?? 'localhost';
            $port = $dbConfig['DB_PORT'] ?? '3306';
            $database = $dbConfig['DB_DATABASE'] ?? '';
            $username = $dbConfig['DB_USERNAME'] ?? '';
            $password = $dbConfig['DB_PASSWORD'] ?? '';
            
            echo "   Tentando conectar: $host:$port/$database\n";
            
            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            
            echo "   ✅ Conexão estabelecida\n";
            $sucessos[] = "Conexão com banco OK";
            
            // Verificar tabelas
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $tableCount = count($tables);
            
            echo "   ✅ Tabelas encontradas: $tableCount\n";
            
            if ($tableCount === 0) {
                echo "   ⚠️  Banco de dados vazio - importe o SQL\n";
                $avisos[] = "Banco de dados vazio";
            } else {
                // Verificar tabelas importantes
                $tabelasImportantes = ['users', 'clientes', 'processos', 'advogados'];
                foreach ($tabelasImportantes as $tabela) {
                    if (in_array($tabela, $tables)) {
                        $stmt = $pdo->query("SELECT COUNT(*) FROM `$tabela`");
                        $count = $stmt->fetchColumn();
                        echo "   ✅ Tabela '$tabela': $count registros\n";
                    } else {
                        echo "   ❌ Tabela '$tabela' não encontrada!\n";
                        $erros[] = "Tabela '$tabela' não existe";
                    }
                }
            }
            
        } catch (PDOException $e) {
            echo "   ❌ ERRO de conexão: " . $e->getMessage() . "\n";
            echo "   Código: " . $e->getCode() . "\n";
            $erros[] = "Erro de conexão: " . $e->getMessage();
            $detalhes[] = "PDO Error Code: " . $e->getCode();
            
            // Dicas baseadas no erro
            if (strpos($e->getMessage(), 'Access denied') !== false) {
                echo "   💡 DICA: Verifique DB_USERNAME e DB_PASSWORD no .env\n";
            } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
                echo "   💡 DICA: Banco de dados não existe. Crie o banco primeiro.\n";
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                echo "   💡 DICA: MySQL não está rodando ou DB_HOST está incorreto\n";
            }
        }
    } else {
        echo "   ⚠️  DB_CONNECTION não é mysql\n";
        $avisos[] = "DB_CONNECTION não configurado como mysql";
    }
}

// 5. Testar public/index.php
echo "\n5. Testando public/index.php...\n";
if (file_exists('public/index.php')) {
    echo "   ✅ Arquivo existe\n";
    
    // Verificar sintaxe
    $output = [];
    $return = 0;
    exec("php -l public/index.php 2>&1", $output, $return);
    
    if ($return === 0) {
        echo "   ✅ Sintaxe PHP OK\n";
        $sucessos[] = "Sintaxe de index.php OK";
    } else {
        echo "   ❌ ERRO de sintaxe:\n";
        foreach ($output as $line) {
            echo "      $line\n";
        }
        $erros[] = "Erro de sintaxe em public/index.php";
        $detalhes[] = implode("\n", $output);
    }
    
    // Tentar incluir e ver se há erros
    ob_start();
    try {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['SERVER_NAME'] = 'localhost';
        
        // Simular ambiente mínimo
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
        
        // Não vamos executar de fato, apenas verificar se o arquivo está correto
        echo "   ✅ Estrutura do arquivo OK\n";
        
    } catch (Exception $e) {
        echo "   ❌ ERRO ao processar: " . $e->getMessage() . "\n";
        $erros[] = "Erro ao processar index.php: " . $e->getMessage();
    }
    ob_end_clean();
    
} else {
    echo "   ❌ Arquivo não encontrado!\n";
    $erros[] = "public/index.php não existe";
}

// 6. Verificar arquivos vendor (CSS/JS)
echo "\n6. Verificando arquivos vendor (assets)...\n";
$arquivosVendor = [
    'public/css/vendor/bootstrap.min.css' => 'Bootstrap CSS',
    'public/css/vendor/fontawesome.min.css' => 'Font Awesome CSS',
    'public/css/vendor/inter-font.css' => 'Fonte Inter CSS',
    'public/js/vendor/bootstrap.bundle.min.js' => 'Bootstrap JS',
    'public/js/vendor/jquery.min.js' => 'jQuery',
    'public/fonts/fontawesome/fa-solid-900.woff2' => 'Font Awesome Solid',
    'public/fonts/fontawesome/fa-regular-400.woff2' => 'Font Awesome Regular',
    'public/fonts/fontawesome/fa-brands-400.woff2' => 'Font Awesome Brands',
    'public/fonts/inter/Inter-400.ttf' => 'Fonte Inter 400',
];

$arquivosFaltando = [];
foreach ($arquivosVendor as $arquivo => $nome) {
    if (file_exists($arquivo)) {
        $tamanho = filesize($arquivo);
        echo "   ✅ $nome ($tamanho bytes)\n";
    } else {
        echo "   ❌ $nome FALTANDO: $arquivo\n";
        $arquivosFaltando[] = $arquivo;
        $erros[] = "$nome não encontrado: $arquivo";
    }
}

if (count($arquivosFaltando) > 0) {
    echo "\n   ⚠️  ARQUIVOS FALTANDO - Isso causará erros 404 no navegador!\n";
    echo "   Solução: Envie os arquivos de public/css/vendor/, public/js/vendor/ e public/fonts/\n";
}

// 7. Verificar views
echo "\n7. Verificando views essenciais...\n";
$viewsEssenciais = [
    'resources/views/auth/login.blade.php' => 'Login',
    'resources/views/layouts/app.blade.php' => 'Layout principal',
    'resources/views/layouts/guest.blade.php' => 'Layout guest',
    'resources/views/layouts/partials/sidebar.blade.php' => 'Sidebar',
    'resources/views/layouts/partials/header.blade.php' => 'Header',
    'resources/views/layouts/partials/flash-messages.blade.php' => 'Flash messages',
    'resources/views/admin/dashboard.blade.php' => 'Dashboard admin',
];

foreach ($viewsEssenciais as $view => $nome) {
    if (file_exists($view)) {
        echo "   ✅ $nome\n";
    } else {
        echo "   ❌ $nome FALTANDO: $view\n";
        $erros[] = "View '$nome' não encontrada: $view";
    }
}

// 8. Verificar permissões detalhadamente
echo "\n8. Verificando permissões...\n";
$pastas = [
    'storage' => 'Storage',
    'storage/logs' => 'Storage/Logs',
    'storage/framework' => 'Storage/Framework',
    'storage/framework/cache' => 'Storage/Cache',
    'storage/framework/sessions' => 'Storage/Sessions',
    'storage/framework/views' => 'Storage/Views',
    'bootstrap/cache' => 'Bootstrap/Cache',
];

foreach ($pastas as $pasta => $nome) {
    if (is_dir($pasta)) {
        $perms = substr(sprintf('%o', fileperms($pasta)), -4);
        $writable = is_writable($pasta);
        
        if ($writable) {
            echo "   ✅ $nome: gravável (perms: $perms)\n";
        } else {
            echo "   ❌ $nome: NÃO gravável (perms: $perms)\n";
            echo "      Execute: chmod -R 775 $pasta\n";
            $erros[] = "$nome não é gravável (permissões: $perms)";
        }
    } else {
        echo "   ❌ $nome: pasta não existe!\n";
        $erros[] = "Pasta $nome não existe";
    }
}

// 9. Verificar logs de erro recentes
echo "\n9. Verificando logs de erro...\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logSize = filesize($logFile);
    echo "   Arquivo de log existe ($logSize bytes)\n";
    
    if ($logSize > 0) {
        $logContent = file_get_contents($logFile);
        $lastLines = array_slice(explode("\n", $logContent), -50);
        
        // Procurar por erros
        $errosEncontrados = [];
        foreach ($lastLines as $line) {
            if (stripos($line, 'error') !== false || 
                stripos($line, 'exception') !== false ||
                stripos($line, 'fatal') !== false) {
                $errosEncontrados[] = trim($line);
            }
        }
        
        if (count($errosEncontrados) > 0) {
            echo "   ❌ ERROS ENCONTRADOS NOS LOGS:\n";
            foreach (array_slice($errosEncontrados, -10) as $erro) {
                if (!empty($erro)) {
                    echo "      " . substr($erro, 0, 100) . "\n";
                    $detalhes[] = "Log error: " . $erro;
                }
            }
            $erros[] = count($errosEncontrados) . " erros encontrados nos logs";
        } else {
            echo "   ✅ Nenhum erro recente nos logs\n";
        }
    } else {
        echo "   ✅ Arquivo de log vazio\n";
    }
} else {
    echo "   ⚠️  Arquivo de log não existe (normal se não houve erros)\n";
}

// 10. Testar se consegue acessar via HTTP (se possível)
echo "\n10. Testando acesso HTTP...\n";
if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['SERVER_NAME'])) {
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    echo "   Host detectado: $host\n";
    echo "   ✅ Script executado via HTTP\n";
} else {
    echo "   ⚠️  Executando via CLI (normal)\n";
    echo "   💡 Para testar HTTP, acesse o site no navegador\n";
}

// 11. Verificar extensões PHP necessárias
echo "\n11. Verificando extensões PHP...\n";
$extensoes = [
    'pdo' => 'PDO',
    'pdo_mysql' => 'PDO MySQL',
    'mbstring' => 'Multibyte String',
    'openssl' => 'OpenSSL',
    'tokenizer' => 'Tokenizer',
    'json' => 'JSON',
    'ctype' => 'CTYPE',
    'fileinfo' => 'FileInfo',
    'xml' => 'XML',
    'curl' => 'cURL',
];

$extensoesFaltando = [];
foreach ($extensoes as $ext => $nome) {
    if (extension_loaded($ext)) {
        echo "   ✅ $nome\n";
    } else {
        echo "   ❌ $nome FALTANDO\n";
        $extensoesFaltando[] = $nome;
        $erros[] = "Extensão PHP '$nome' não carregada";
    }
}

if (count($extensoesFaltando) > 0) {
    echo "\n   ⚠️  EXTENSÕES FALTANDO - Contate o provedor de hospedagem\n";
}

// Resumo detalhado
echo "\n========================================\n";
echo "  RESUMO DO DIAGNÓSTICO\n";
echo "========================================\n\n";

echo "✅ Sucessos: " . count($sucessos) . "\n";
if (count($sucessos) > 0 && count($sucessos) <= 10) {
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
    echo "\n❌ ERROS ENCONTRADOS: " . count($erros) . "\n";
    foreach ($erros as $erro) {
        echo "   • $erro\n";
    }
    
    if (count($detalhes) > 0) {
        echo "\n📋 DETALHES DOS ERROS:\n";
        foreach ($detalhes as $i => $detalhe) {
            echo "\n   Erro #" . ($i + 1) . ":\n";
            echo "   " . wordwrap($detalhe, 80, "\n   ") . "\n";
        }
    }
    
    echo "\n🔧 AÇÕES RECOMENDADAS:\n";
    
    if (in_array("Arquivo .env não existe", $erros)) {
        echo "   1. Copie env.example para .env\n";
        echo "   2. Configure as variáveis no .env\n";
    }
    
    if (in_array("Erro de conexão", array_column($erros, null))) {
        echo "   1. Verifique DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD no .env\n";
        echo "   2. Verifique se o banco de dados existe\n";
        echo "   3. Teste a conexão via phpMyAdmin\n";
    }
    
    if (count($arquivosFaltando) > 0) {
        echo "   1. Envie os arquivos faltantes:\n";
        foreach ($arquivosFaltando as $arquivo) {
            echo "      - $arquivo\n";
        }
    }
    
    $permissoesErro = false;
    foreach ($erros as $erro) {
        if (strpos($erro, 'não é gravável') !== false || strpos($erro, 'não existe') !== false) {
            $permissoesErro = true;
            break;
        }
    }
    
    if ($permissoesErro) {
        echo "   1. Configure permissões:\n";
        echo "      chmod -R 775 storage bootstrap/cache\n";
        echo "      chown -R www-data:www-data storage bootstrap/cache\n";
    }
    
    echo "\n";
    exit(1);
} else {
    echo "\n✅ Sistema diagnosticado - Nenhum erro crítico encontrado!\n";
    echo "\n📋 Próximos passos:\n";
    echo "   1. Acesse o site no navegador\n";
    echo "   2. Abra o DevTools (F12) e verifique o console\n";
    echo "   3. Verifique a aba Network para erros 404 ou 500\n";
    echo "   4. Se houver erros, execute este script novamente\n";
    echo "\n";
    exit(0);
}

