<?php
/**
 * Script de Verificação do Servidor
 * Execute: php verificar-servidor.php
 */

echo "========================================\n";
echo "  VERIFICAÇÃO DO SERVIDOR\n";
echo "========================================\n\n";

$erros = [];
$avisos = [];
$sucessos = [];

// 1. Verificar PHP
echo "1. Verificando PHP...\n";
$phpVersion = PHP_VERSION;
echo "   Versão: PHP $phpVersion\n";
if (version_compare($phpVersion, '8.2.0', '>=')) {
    $sucessos[] = "PHP $phpVersion OK";
} else {
    $erros[] = "PHP 8.2+ necessário. Versão atual: $phpVersion";
}

// 2. Verificar extensões PHP
echo "\n2. Verificando extensões PHP...\n";
$extensoes = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'json', 'ctype', 'fileinfo'];
foreach ($extensoes as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✓ $ext\n";
        $sucessos[] = "Extensão $ext carregada";
    } else {
        echo "   ✗ $ext (FALTANDO)\n";
        $erros[] = "Extensão $ext não encontrada";
    }
}

// 3. Verificar arquivos essenciais
echo "\n3. Verificando arquivos essenciais...\n";
$arquivos = [
    'composer.json' => 'composer.json',
    'public/index.php' => 'public/index.php',
    'public/css/vendor/bootstrap.min.css' => 'Bootstrap CSS',
    'public/js/vendor/bootstrap.bundle.min.js' => 'Bootstrap JS',
    'public/css/vendor/fontawesome.min.css' => 'Font Awesome CSS',
    'public/js/vendor/jquery.min.js' => 'jQuery',
    'public/fonts/fontawesome/fa-solid-900.woff2' => 'Font Awesome fonts',
    'public/fonts/inter/Inter-400.ttf' => 'Fonte Inter',
];

foreach ($arquivos as $arquivo => $nome) {
    if (file_exists($arquivo)) {
        echo "   ✓ $nome\n";
        $sucessos[] = "$nome encontrado";
    } else {
        echo "   ✗ $nome (FALTANDO: $arquivo)\n";
        $erros[] = "$nome não encontrado: $arquivo";
    }
}

// 4. Verificar .env
echo "\n4. Verificando .env...\n";
if (file_exists('.env')) {
    echo "   ✓ Arquivo .env existe\n";
    $envContent = file_get_contents('.env');
    
    if (strpos($envContent, 'APP_KEY=base64:') !== false) {
        echo "   ✓ APP_KEY configurado\n";
        $sucessos[] = "APP_KEY configurado";
    } else {
        echo "   ✗ APP_KEY não configurado\n";
        $avisos[] = "APP_KEY não configurado. Execute: php -r \"echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;\"";
    }
    
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        echo "   ✓ APP_ENV=production\n";
    } else {
        echo "   ⚠ APP_ENV não está em production\n";
        $avisos[] = "Configure APP_ENV=production em produção";
    }
    
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        echo "   ✓ APP_DEBUG=false\n";
    } else {
        echo "   ⚠ APP_DEBUG não está em false\n";
        $avisos[] = "Configure APP_DEBUG=false em produção";
    }
} else {
    echo "   ✗ Arquivo .env não encontrado\n";
    $erros[] = "Arquivo .env não encontrado. Copie env.example para .env";
}

// 5. Verificar permissões
echo "\n5. Verificando permissões...\n";
$pastas = ['storage', 'bootstrap/cache'];
foreach ($pastas as $pasta) {
    if (is_dir($pasta)) {
        if (is_writable($pasta)) {
            echo "   ✓ $pasta é gravável\n";
            $sucessos[] = "$pasta com permissão de escrita";
        } else {
            echo "   ✗ $pasta NÃO é gravável\n";
            $erros[] = "$pasta não tem permissão de escrita. Execute: chmod -R 775 $pasta";
        }
    } else {
        echo "   ✗ $pasta não existe\n";
        $erros[] = "Pasta $pasta não existe";
    }
}

// 6. Verificar vendor
echo "\n6. Verificando vendor/...\n";
if (is_dir('vendor')) {
    echo "   ✓ Pasta vendor/ existe\n";
    if (file_exists('vendor/autoload.php')) {
        echo "   ✓ vendor/autoload.php existe\n";
        $sucessos[] = "Dependências Composer instaladas";
    } else {
        echo "   ✗ vendor/autoload.php não encontrado\n";
        $erros[] = "Execute: composer install --no-dev";
    }
} else {
    echo "   ✗ Pasta vendor/ não existe\n";
    $erros[] = "Pasta vendor/ não existe. Execute: composer install --no-dev";
}

// 7. Verificar conexão com banco (se .env existir)
echo "\n7. Verificando conexão com banco...\n";
if (file_exists('.env')) {
    // Tentar carregar .env manualmente
    $envLines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $dbConfig = [];
    foreach ($envLines as $line) {
        if (strpos($line, 'DB_') === 0 && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $dbConfig[$key] = trim($value);
        }
    }
    
    if (isset($dbConfig['DB_CONNECTION']) && $dbConfig['DB_CONNECTION'] === 'mysql') {
        try {
            $dsn = "mysql:host={$dbConfig['DB_HOST']};port={$dbConfig['DB_PORT']};dbname={$dbConfig['DB_DATABASE']}";
            $pdo = new PDO($dsn, $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD']);
            echo "   ✓ Conexão com banco OK\n";
            $sucessos[] = "Conexão com banco de dados OK";
        } catch (PDOException $e) {
            echo "   ✗ Erro ao conectar: " . $e->getMessage() . "\n";
            $erros[] = "Erro de conexão com banco: " . $e->getMessage();
        }
    } else {
        echo "   ⚠ Configuração de banco não encontrada no .env\n";
        $avisos[] = "Configure DB_* no .env";
    }
}

// Resumo
echo "\n========================================\n";
echo "  RESUMO\n";
echo "========================================\n\n";

if (count($sucessos) > 0) {
    echo "✅ Sucessos: " . count($sucessos) . "\n";
}

if (count($avisos) > 0) {
    echo "\n⚠️  Avisos:\n";
    foreach ($avisos as $aviso) {
        echo "   - $aviso\n";
    }
}

if (count($erros) > 0) {
    echo "\n❌ Erros:\n";
    foreach ($erros as $erro) {
        echo "   - $erro\n";
    }
    echo "\n";
    exit(1);
} else {
    echo "\n✅ Sistema verificado com sucesso!\n";
    echo "\n";
    exit(0);
}

