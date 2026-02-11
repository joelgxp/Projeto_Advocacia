<?php
/**
 * Verifica configurações de cache que podem impedir mudanças de aparecer
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Verificação de Cache\n";
echo "========================================\n\n";

// 1. Verificar ambiente
echo "1. AMBIENTE\n";
$env_file = $root_dir . '/.env';
$env = 'production';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^(APP_ENV|APP_ENVIRONMENT)\s*=\s*(\w+)/', $line, $matches)) {
            $env = trim($matches[2]);
            break;
        }
    }
}
echo "   Ambiente: $env\n";
if ($env !== 'development') {
    echo "   ⚠️  Ambiente não é 'development' - cache pode estar ativo!\n";
    echo "   💡 Altere APP_ENVIRONMENT=development no .env\n";
} else {
    echo "   ✅ Ambiente é 'development' - cache desabilitado\n";
}

// 2. Verificar OPcache
echo "\n2. OPCACHE\n";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    if ($status && $status['opcache_enabled']) {
        echo "   ⚠️  OPcache está HABILITADO - pode cachear arquivos!\n";
        echo "   💡 Desabilite no php.ini ou use: ini_set('opcache.enable', 0);\n";
    } else {
        echo "   ✅ OPcache está desabilitado\n";
    }
} else {
    echo "   ✅ OPcache não está disponível\n";
}

// 3. Verificar cache do CodeIgniter
echo "\n3. CACHE DO CODEIGNITER\n";
$cache_dir = $root_dir . '/application/cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    $count = count(array_filter($files, 'is_file'));
    if ($count > 0) {
        echo "   ⚠️  $count arquivos em cache encontrados\n";
        echo "   💡 Execute: php scripts/limpar-cache-local.php\n";
    } else {
        echo "   ✅ Nenhum arquivo em cache\n";
    }
} else {
    echo "   ✅ Diretório de cache não existe\n";
}

// 4. Verificar timestamp de arquivos
echo "\n4. VERIFICANDO ARQUIVOS RECENTES\n";
$arquivos = [
    'application/controllers/Login.php',
    'application/views/auth/login.php',
    'application/config/config.php'
];

foreach ($arquivos as $arquivo) {
    $path = $root_dir . '/' . $arquivo;
    if (file_exists($path)) {
        $modificado = filemtime($path);
        $tempo = time() - $modificado;
        if ($tempo < 300) { // Menos de 5 minutos
            echo "   ✅ $arquivo (modificado há " . round($tempo) . " segundos)\n";
        } else {
            echo "   ℹ️  $arquivo (modificado há " . round($tempo/60) . " minutos)\n";
        }
    }
}

echo "\n========================================\n";
echo "💡 DICAS:\n";
echo "   1. Certifique-se que APP_ENVIRONMENT=development no .env\n";
echo "   2. Limpe o cache: php scripts/limpar-cache-local.php\n";
echo "   3. Recarregue a página com Ctrl+F5 (limpa cache do navegador)\n";
echo "   4. Reinicie o servidor após mudanças grandes\n";
echo "========================================\n";



