<?php
/**
 * Limpa todo o cache local
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Limpando Cache Local\n";
echo "========================================\n\n";

// 1. Limpar cache do CodeIgniter
echo "1. Limpando cache do CodeIgniter...\n";
$cache_dir = $root_dir . '/application/cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "   ✅ $count arquivos removidos de application/cache/\n";
} else {
    echo "   ⚠️  Diretório application/cache/ não existe\n";
}

// 2. Limpar logs (opcional)
echo "\n2. Limpando logs antigos...\n";
$logs_dir = $root_dir . '/application/logs';
if (is_dir($logs_dir)) {
    $files = glob($logs_dir . '/log-*.php');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < (time() - 86400)) { // Mais de 1 dia
            unlink($file);
            $count++;
        }
    }
    echo "   ✅ $count logs antigos removidos\n";
}

// 3. Limpar sessões antigas
echo "\n3. Limpando sessões antigas...\n";
$sessions_dir = $root_dir . '/application/sessions';
if (is_dir($sessions_dir)) {
    $files = glob($sessions_dir . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < (time() - 3600)) { // Mais de 1 hora
            unlink($file);
            $count++;
        }
    }
    echo "   ✅ $count sessões antigas removidas\n";
}

// 4. Verificar opcache (se habilitado)
echo "\n4. Verificando OPcache...\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "   ✅ OPcache resetado\n";
    } else {
        echo "   ⚠️  OPcache não pôde ser resetado\n";
    }
} else {
    echo "   ℹ️  OPcache não está habilitado\n";
}

echo "\n========================================\n";
echo "✅ Cache limpo! Recarregue a página no navegador (Ctrl+F5)\n";
echo "========================================\n";



