<?php
/**
 * Script para testar acesso ao Admin
 */

// Simular ambiente CodeIgniter
define('BASEPATH', __DIR__ . '/../');
define('ENVIRONMENT', 'development');

echo "========================================\n";
echo "  TESTE DE ACESSO AO ADMIN\n";
echo "========================================\n\n";

// 1. Verificar se controller existe
echo "1. Verificando controller Admin...\n";
if (file_exists(__DIR__ . '/../application/controllers/Admin.php')) {
    echo "   ✅ Controller Admin existe\n";
} else {
    echo "   ❌ Controller Admin não encontrado\n";
    exit(1);
}

// 2. Verificar se MY_Controller existe
echo "\n2. Verificando MY_Controller...\n";
if (file_exists(__DIR__ . '/../application/core/MY_Controller.php')) {
    echo "   ✅ MY_Controller existe\n";
} else {
    echo "   ❌ MY_Controller não encontrado\n";
    exit(1);
}

// 3. Verificar biblioteca Permission
echo "\n3. Verificando biblioteca Permission...\n";
if (file_exists(__DIR__ . '/../application/libraries/Permission.php')) {
    echo "   ✅ Biblioteca Permission existe\n";
} else {
    echo "   ⚠️  Biblioteca Permission não encontrada\n";
}

// 4. Verificar modelos
echo "\n4. Verificando modelos...\n";
$modelos = ['Processo_model', 'Cliente_model', 'Advogado_model', 'Usuario_model'];
foreach ($modelos as $modelo) {
    $arquivo = __DIR__ . '/../application/models/' . $modelo . '.php';
    if (file_exists($arquivo)) {
        echo "   ✅ $modelo existe\n";
    } else {
        echo "   ❌ $modelo não encontrado\n";
    }
}

// 5. Verificar views
echo "\n5. Verificando views...\n";
$views = [
    'tema/topo.php',
    'tema/menu.php',
    'tema/conteudo.php',
    'tema/rodape.php',
    'admin/dashboard.php'
];

foreach ($views as $view) {
    $arquivo = __DIR__ . '/../application/views/' . $view;
    if (file_exists($arquivo)) {
        echo "   ✅ $view existe\n";
    } else {
        echo "   ❌ $view não encontrado\n";
    }
}

// 6. Verificar rotas
echo "\n6. Verificando rotas...\n";
$routes = file_get_contents(__DIR__ . '/../application/config/routes.php');
if (strpos($routes, "'admin'") !== false || strpos($routes, '"admin"') !== false) {
    echo "   ✅ Rota 'admin' configurada\n";
} else {
    echo "   ⚠️  Rota 'admin' não encontrada em routes.php\n";
}

// 7. Verificar .htaccess
echo "\n7. Verificando .htaccess...\n";
if (file_exists(__DIR__ . '/../.htaccess')) {
    $htaccess = file_get_contents(__DIR__ . '/../.htaccess');
    if (strpos($htaccess, 'mod_rewrite') !== false) {
        echo "   ✅ .htaccess contém mod_rewrite\n";
    } else {
        echo "   ⚠️  .htaccess não contém mod_rewrite\n";
    }
} else {
    echo "   ⚠️  .htaccess não encontrado\n";
}

echo "\n";
echo "========================================\n";
echo "  TESTE CONCLUÍDO\n";
echo "========================================\n";
echo "\n";
echo "💡 Próximos passos:\n";
echo "   1. Acesse: http://localhost/admin (ou http://localhost:8000/admin)\n";
echo "   2. Verifique os logs em: application/logs/\n";
echo "   3. Se usar Apache, verifique se mod_rewrite está habilitado\n";

