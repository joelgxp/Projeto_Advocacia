<?php

echo "Testando instalação do Laravel...\n\n";

// 1. Verificar autoloader
echo "1. Verificando autoloader...\n";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "   ✓ vendor/autoload.php existe\n";
    require __DIR__ . '/vendor/autoload.php';
} else {
    echo "   ✗ vendor/autoload.php NÃO existe\n";
    exit(1);
}

// 2. Verificar classe Application
echo "\n2. Verificando classe Illuminate\\Foundation\\Application...\n";
if (class_exists('Illuminate\Foundation\Application')) {
    echo "   ✓ Classe encontrada\n";
} else {
    echo "   ✗ Classe NÃO encontrada\n";
    
    // Verificar se o arquivo existe
    $appFile = __DIR__ . '/vendor/laravel/framework/src/Foundation/Application.php';
    if (file_exists($appFile)) {
        echo "   ℹ Arquivo existe em: $appFile\n";
        echo "   ℹ Tentando carregar manualmente...\n";
        require_once $appFile;
        if (class_exists('Illuminate\Foundation\Application')) {
            echo "   ✓ Classe carregada manualmente\n";
        } else {
            echo "   ✗ Ainda não funciona\n";
        }
    } else {
        echo "   ✗ Arquivo NÃO existe: $appFile\n";
    }
}

// 3. Verificar outros componentes
echo "\n3. Verificando outros componentes...\n";
$components = [
    'Illuminate\Support\Facades\Facade',
    'Illuminate\Database\Eloquent\Model',
    'Illuminate\Routing\Router',
];

foreach ($components as $component) {
    if (class_exists($component)) {
        echo "   ✓ $component\n";
    } else {
        echo "   ✗ $component\n";
    }
}

// 4. Testar criar aplicação
echo "\n4. Tentando criar aplicação Laravel...\n";
try {
    $app = new Illuminate\Foundation\Application(__DIR__);
    echo "   ✓ Aplicação criada com sucesso!\n";
    echo "   ✓ Versão: " . $app->version() . "\n";
} catch (Exception $e) {
    echo "   ✗ Erro: " . $e->getMessage() . "\n";
}

echo "\nTeste concluído!\n";

