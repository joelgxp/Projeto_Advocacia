<?php
/**
 * Script para comparar diferenças entre ambiente local e online
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Comparação: Local vs Online\n";
echo "========================================\n\n";

// 1. Verificar ambiente
echo "1. AMBIENTE\n";
echo "   Local esperado: development\n";
echo "   Online esperado: production\n\n";

$env_file = $root_dir . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^(APP_ENV|APP_ENVIRONMENT)\s*=\s*(\w+)/', $line, $matches)) {
            echo "   Ambiente atual: " . trim($matches[2]) . "\n";
            break;
        }
    }
} else {
    echo "   ⚠️  .env não encontrado\n";
}

// 2. Verificar base_url
echo "\n2. BASE_URL\n";
// Ler .env diretamente sem carregar CodeIgniter
$env_file = $root_dir . '/.env';
$base_url = '';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^(APP_BASEURL|APP_URL)\s*=\s*(.+)/', $line, $matches)) {
            $base_url = trim($matches[2], ' "\'');
            break;
        }
    }
}
echo "   Base URL configurada: " . ($base_url ?: 'AUTO-DETECTADA') . "\n";
echo "   Local esperado: http://localhost:8000/\n";
echo "   Online esperado: https://adv.joelsouza.com.br/\n";

// 3. Verificar estrutura de diretórios
echo "\n3. ESTRUTURA DE DIRETÓRIOS\n";
$dirs = [
    'application' => 'CodeIgniter',
    'system' => 'CodeIgniter',
    'assets' => 'Assets estáticos',
    'public' => 'Laravel (antigo)',
    'app' => 'Laravel (antigo)',
    'resources' => 'Laravel (antigo)'
];

foreach ($dirs as $dir => $tipo) {
    $exists = is_dir($root_dir . '/' . $dir);
    echo "   " . ($exists ? "✅" : "❌") . " $dir/ - $tipo\n";
}

// 4. Verificar index.php
echo "\n4. INDEX.PHP\n";
$index_root = file_exists($root_dir . '/index.php');
$index_public = file_exists($root_dir . '/public/index.php');

echo "   " . ($index_root ? "✅" : "❌") . " index.php (raiz) - CodeIgniter\n";
echo "   " . ($index_public ? "⚠️" : "✅") . " public/index.php - Laravel (deve ser ignorado)\n";
echo "   Local: Deve usar index.php da RAIZ via router.php\n";
echo "   Online: Deve usar index.php da RAIZ (DocumentRoot na raiz)\n";

// 5. Verificar assets
echo "\n5. ASSETS\n";
$assets_paths = [
    'assets/css/vendor/bootstrap.min.css' => 'Bootstrap CSS',
    'assets/css/vendor/fontawesome.min.css' => 'Font Awesome CSS',
    'assets/js/vendor/jquery.min.js' => 'jQuery',
    'assets/css/webfonts/fa-solid-900.woff2' => 'Font Awesome Fonts',
    'public/css/webfonts/fa-solid-900.woff2' => 'Font Awesome Fonts (public)'
];

foreach ($assets_paths as $path => $nome) {
    $exists = file_exists($root_dir . '/' . $path);
    echo "   " . ($exists ? "✅" : "❌") . " $path - $nome\n";
}

// 6. Verificar views
echo "\n6. VIEWS\n";
$views_codeigniter = [
    'application/views/auth/login.php' => 'Login (CodeIgniter)',
    'application/views/tema/topo.php' => 'Topo (CodeIgniter)'
];

$views_laravel = [
    'resources/views/auth/login.blade.php' => 'Login (Laravel - antigo)',
    'resources/views/layouts/app.blade.php' => 'Layout (Laravel - antigo)'
];

echo "   CodeIgniter (usado):\n";
foreach ($views_codeigniter as $path => $nome) {
    $exists = file_exists($root_dir . '/' . $path);
    echo "      " . ($exists ? "✅" : "❌") . " $nome\n";
}

echo "   Laravel (antigo, não usado):\n";
foreach ($views_laravel as $path => $nome) {
    $exists = file_exists($root_dir . '/' . $path);
    echo "      " . ($exists ? "⚠️" : "✅") . " $nome\n";
}

// 7. Verificar controllers
echo "\n7. CONTROLLERS\n";
$controllers_codeigniter = [
    'application/controllers/Login.php' => 'Login (CodeIgniter)',
    'application/controllers/Admin.php' => 'Admin (CodeIgniter)'
];

$controllers_laravel = [
    'app/Http/Controllers/Auth/LoginController.php' => 'Login (Laravel - antigo)'
];

echo "   CodeIgniter (usado):\n";
foreach ($controllers_codeigniter as $path => $nome) {
    $exists = file_exists($root_dir . '/' . $path);
    echo "      " . ($exists ? "✅" : "❌") . " $nome\n";
}

echo "   Laravel (antigo, não usado):\n";
foreach ($controllers_laravel as $path => $nome) {
    $exists = file_exists($root_dir . '/' . $path);
    echo "      " . ($exists ? "⚠️" : "✅") . " $nome\n";
}

// 8. Verificar banco de dados
echo "\n8. BANCO DE DADOS\n";
$db_host = 'localhost';
$db_name = 'advocacia';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^DB_HOSTNAME\s*=\s*(.+)/', $line, $matches)) {
            $db_host = trim($matches[1], ' "\'');
        }
        if (preg_match('/^DB_DATABASE\s*=\s*(.+)/', $line, $matches)) {
            $db_name = trim($matches[1], ' "\'');
        }
    }
}
echo "   Host: $db_host\n";
echo "   Database: $db_name\n";
echo "   Local: Geralmente 'localhost' e 'advocacia'\n";
echo "   Online: Geralmente 'localhost' e nome específico do servidor\n";

// 9. Resumo das diferenças principais
echo "\n========================================\n";
echo "  RESUMO DAS DIFERENÇAS PRINCIPAIS\n";
echo "========================================\n\n";

echo "LOCAL:\n";
echo "  ✅ Usa router.php para garantir index.php da raiz\n";
echo "  ✅ Ambiente: development (mostra erros)\n";
echo "  ✅ Base URL: http://localhost:8000/\n";
echo "  ✅ Assets em: assets/\n";
echo "  ✅ Views em: application/views/\n";
echo "  ✅ Controllers em: application/controllers/\n\n";

echo "ONLINE:\n";
echo "  ✅ DocumentRoot aponta para raiz do projeto\n";
echo "  ✅ Ambiente: production (esconde erros)\n";
echo "  ✅ Base URL: https://adv.joelsouza.com.br/\n";
echo "  ✅ Assets em: assets/ (mesmo caminho)\n";
echo "  ✅ Views em: application/views/ (mesmo caminho)\n";
echo "  ✅ Controllers em: application/controllers/ (mesmo caminho)\n\n";

echo "⚠️  PROBLEMA COMUM:\n";
echo "  Se online estiver usando public/index.php (Laravel),\n";
echo "  vai dar erro. Deve usar index.php da raiz (CodeIgniter).\n";

echo "\n========================================\n";

