<?php
/**
 * Script de Verificação da Instalação do Composer
 * 
 * Execute este arquivo após instalar o Composer para verificar
 * se tudo está funcionando corretamente.
 */

echo "========================================\n";
echo "Verificação da Instalação do Composer\n";
echo "Sistema de Advocacia\n";
echo "========================================\n\n";

// 1. Verificar PHP
echo "[1/7] Verificando PHP...\n";
$phpVersion = PHP_VERSION;
echo "✅ PHP encontrado: $phpVersion\n";

if (version_compare($phpVersion, '7.4.0', '<')) {
    echo "❌ ERRO: PHP 7.4 ou superior é necessário!\n";
    exit(1);
}

$phpMajor = PHP_MAJOR_VERSION;
$phpMinor = PHP_MINOR_VERSION;
echo "   Versão: $phpMajor.$phpMinor.x\n\n";

// 2. Verificar composer.json
echo "[2/7] Verificando composer.json...\n";
if (!file_exists('composer.json')) {
    echo "❌ ERRO: composer.json não encontrado!\n";
    exit(1);
}
echo "✅ composer.json encontrado\n\n";

// 3. Verificar vendor/autoload.php
echo "[3/7] Verificando vendor/autoload.php...\n";
if (!file_exists('vendor/autoload.php')) {
    echo "❌ ERRO: vendor/autoload.php não encontrado!\n";
    echo "   Execute: composer install\n";
    exit(1);
}
echo "✅ vendor/autoload.php encontrado\n\n";

// 4. Verificar composer.lock
echo "[4/7] Verificando composer.lock...\n";
if (!file_exists('composer.lock')) {
    echo "⚠️  AVISO: composer.lock não encontrado\n";
    echo "   Execute: composer install\n";
} else {
    echo "✅ composer.lock encontrado\n";
}
echo "\n";

// 5. Testar autoloader
echo "[5/7] Testando autoloader...\n";
try {
    require_once 'vendor/autoload.php';
    echo "✅ Autoloader carregado com sucesso\n";
} catch (Exception $e) {
    echo "❌ ERRO ao carregar autoloader: " . $e->getMessage() . "\n";
    exit(1);
}
echo "\n";

// 6. Verificar classes do projeto
echo "[6/7] Verificando classes do projeto...\n";

$classes = [
    'Advocacia\\Database\\Connection',
    'Advocacia\\Config\\Database'
];

$classesFound = 0;
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✅ Classe encontrada: $class\n";
        $classesFound++;
    } else {
        echo "⚠️  Classe não encontrada: $class\n";
    }
}

if ($classesFound === 0) {
    echo "⚠️  AVISO: Nenhuma classe do projeto foi encontrada\n";
    echo "   Verifique se as classes estão no diretório src/\n";
} else {
    echo "   Total: $classesFound/" . count($classes) . " classes encontradas\n";
}
echo "\n";

// 7. Verificar estrutura de diretórios
echo "[7/7] Verificando estrutura de diretórios...\n";

$directories = [
    'src',
    'src/Database',
    'src/Config',
    'vendor'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        echo "✅ Diretório encontrado: $dir/\n";
    } else {
        echo "⚠️  Diretório não encontrado: $dir/\n";
    }
}
echo "\n";

// Resumo
echo "========================================\n";
echo "Resumo da Verificação\n";
echo "========================================\n";
echo "PHP: $phpVersion\n";
echo "Composer: " . (file_exists('vendor/autoload.php') ? 'Instalado' : 'Não instalado') . "\n";
echo "Autoloader: " . (class_exists('Composer\\Autoload\\ClassLoader') ? 'Funcionando' : 'Não funcionando') . "\n";
echo "Classes do projeto: $classesFound/" . count($classes) . "\n";
echo "\n";

if (file_exists('vendor/autoload.php') && $classesFound > 0) {
    echo "✅ Instalação concluída com sucesso!\n";
    echo "\n";
    echo "Próximos passos:\n";
    echo "1. Use require_once 'vendor/autoload.php' nos seus arquivos PHP\n";
    echo "2. Use os namespaces: use Advocacia\\Database\\Connection;\n";
    echo "3. Migre gradualmente seu código para a nova estrutura\n";
} else {
    echo "⚠️  Alguns problemas foram detectados. Revise os avisos acima.\n";
}

echo "\n";

