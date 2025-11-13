<?php
/**
 * Verifica se local e online estão configurados igual
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Verificação: Local = Online?\n";
echo "========================================\n\n";

$erros = [];

// 1. Verificar index.php
echo "1. INDEX.PHP\n";
$index_raiz = file_exists($root_dir . '/index.php');
$index_public = file_exists($root_dir . '/public/index.php');

if (!$index_raiz) {
    $erros[] = "index.php não encontrado na raiz";
    echo "   ❌ index.php (raiz) não existe\n";
} else {
    // Verificar se é CodeIgniter
    $content = file_get_contents($root_dir . '/index.php');
    if (strpos($content, 'CodeIgniter') !== false) {
        echo "   ✅ index.php (raiz) - CodeIgniter\n";
    } else {
        $erros[] = "index.php da raiz não é CodeIgniter";
        echo "   ❌ index.php (raiz) não é CodeIgniter\n";
    }
}

if ($index_public) {
    $content = file_get_contents($root_dir . '/public/index.php');
    if (strpos($content, 'Laravel') !== false) {
        echo "   ⚠️  public/index.php existe (Laravel) - será ignorado\n";
    }
}

// 2. Verificar .htaccess
echo "\n2. .HTACCESS\n";
$htaccess = file_exists($root_dir . '/.htaccess');
if ($htaccess) {
    $content = file_get_contents($root_dir . '/.htaccess');
    // Verificar se bloqueia public/index.php
    $bloqueia_public = strpos($content, '/public/index') !== false || strpos($content, 'public/index') !== false;
    // Verificar se redireciona para index.php da raiz
    $redireciona_raiz = strpos($content, 'index.php/$1') !== false || strpos($content, 'index.php') !== false;
    
    if ($redireciona_raiz) {
        echo "   ✅ .htaccess redireciona para index.php da raiz\n";
        if ($bloqueia_public) {
            echo "   ✅ .htaccess bloqueia acesso a public/index.php\n";
        }
    } else {
        $erros[] = ".htaccess não redireciona para index.php da raiz";
        echo "   ❌ .htaccess não redireciona corretamente\n";
    }
} else {
    echo "   ⚠️  .htaccess não encontrado (pode ser normal no servidor)\n";
}

// 3. Verificar assets
echo "\n3. ASSETS\n";
$assets_paths = [
    'assets/css/vendor/bootstrap.min.css',
    'assets/css/vendor/fontawesome.min.css',
    'assets/js/vendor/jquery.min.js'
];

$todos_assets = true;
foreach ($assets_paths as $path) {
    if (file_exists($root_dir . '/' . $path)) {
        echo "   ✅ $path\n";
    } else {
        $todos_assets = false;
        $erros[] = "Asset não encontrado: $path";
        echo "   ❌ $path\n";
    }
}

// 4. Verificar views CodeIgniter
echo "\n4. VIEWS (CodeIgniter)\n";
$views = [
    'application/views/auth/login.php',
    'application/views/tema/topo.php'
];

$todas_views = true;
foreach ($views as $view) {
    if (file_exists($root_dir . '/' . $view)) {
        echo "   ✅ $view\n";
    } else {
        $todas_views = false;
        $erros[] = "View não encontrada: $view";
        echo "   ❌ $view\n";
    }
}

// 5. Verificar controllers CodeIgniter
echo "\n5. CONTROLLERS (CodeIgniter)\n";
$controllers = [
    'application/controllers/Login.php',
    'application/controllers/Admin.php'
];

$todos_controllers = true;
foreach ($controllers as $controller) {
    if (file_exists($root_dir . '/' . $controller)) {
        echo "   ✅ $controller\n";
    } else {
        $todos_controllers = false;
        $erros[] = "Controller não encontrado: $controller";
        echo "   ❌ $controller\n";
    }
}

// Resumo
echo "\n========================================\n";
if (empty($erros)) {
    echo "✅ TUDO IGUAL - Local e Online devem funcionar igual\n";
    echo "\nAmbos usam:\n";
    echo "  ✅ index.php da RAIZ (CodeIgniter)\n";
    echo "  ✅ Assets em assets/\n";
    echo "  ✅ Views em application/views/\n";
    echo "  ✅ Controllers em application/controllers/\n";
} else {
    echo "❌ PROBLEMAS ENCONTRADOS:\n";
    foreach ($erros as $erro) {
        echo "   - $erro\n";
    }
}
echo "========================================\n";

