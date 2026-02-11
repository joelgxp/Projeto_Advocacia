<?php
/**
 * Script para verificar configuração do Apache
 */

echo "========================================\n";
echo "  VERIFICAÇÃO DO APACHE\n";
echo "========================================\n\n";

// 1. Verificar se está usando Apache
echo "1. Verificando servidor...\n";
$server = $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido';
echo "   Servidor: $server\n";

if (strpos($server, 'Apache') !== false) {
    echo "   ✅ Usando Apache\n";
} else {
    echo "   ⚠️  Não está usando Apache (pode ser PHP built-in)\n";
}

// 2. Verificar mod_rewrite
echo "\n2. Verificando mod_rewrite...\n";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "   ✅ mod_rewrite está habilitado\n";
    } else {
        echo "   ❌ mod_rewrite NÃO está habilitado\n";
        echo "   💡 Habilite no httpd.conf: LoadModule rewrite_module modules/mod_rewrite.so\n";
    }
} else {
    echo "   ⚠️  Não foi possível verificar (função apache_get_modules não disponível)\n";
    echo "   💡 Verifique manualmente no httpd.conf\n";
}

// 3. Verificar .htaccess
echo "\n3. Verificando .htaccess...\n";
$htaccessPath = __DIR__ . '/../.htaccess';
if (file_exists($htaccessPath)) {
    echo "   ✅ .htaccess existe\n";
    $content = file_get_contents($htaccessPath);
    if (strpos($content, 'RewriteEngine On') !== false) {
        echo "   ✅ RewriteEngine está configurado\n";
    } else {
        echo "   ⚠️  RewriteEngine não encontrado\n";
    }
} else {
    echo "   ❌ .htaccess não encontrado\n";
}

// 4. Testar URL rewriting
echo "\n4. Testando URL rewriting...\n";
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
echo "   REQUEST_URI: $requestUri\n";
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
echo "   SCRIPT_NAME: $scriptName\n";

// 5. Verificar se index.php está sendo usado
echo "\n5. Verificando index.php...\n";
if (file_exists(__DIR__ . '/../index.php')) {
    echo "   ✅ index.php existe na raiz\n";
} else {
    echo "   ❌ index.php não encontrado na raiz\n";
}

// 6. Verificar DocumentRoot
echo "\n6. Informações do DocumentRoot...\n";
$docRoot = $_SERVER['DOCUMENT_ROOT'] ?? 'Não definido';
echo "   DOCUMENT_ROOT: $docRoot\n";
$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? 'Não definido';
echo "   SCRIPT_FILENAME: $scriptFilename\n";

echo "\n";
echo "========================================\n";
echo "  RECOMENDAÇÕES\n";
echo "========================================\n\n";

if (strpos($server, 'Apache') !== false) {
    echo "Se você está usando Apache:\n";
    echo "1. Verifique se mod_rewrite está habilitado\n";
    echo "2. Verifique se AllowOverride All está configurado\n";
    echo "3. Verifique se DocumentRoot aponta para a raiz do projeto\n";
    echo "4. Reinicie o Apache após alterações\n";
} else {
    echo "Você está usando o servidor PHP built-in.\n";
    echo "Use: php -S localhost:8000 router.php\n";
    echo "E acesse: http://localhost:8000\n";
}

echo "\n";

