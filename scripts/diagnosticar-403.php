<?php
/**
 * Script de Diagnóstico de Erro 403 (Forbidden)
 * Execute: php scripts/diagnosticar-403.php
 * 
 * Este script identifica problemas que causam erro 403 no servidor
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "  DIAGNÓSTICO DE ERRO 403 (Forbidden)\n";
echo "========================================\n\n";

// Detectar raiz do projeto
$rootDir = __DIR__;
if (basename($rootDir) === 'scripts') {
    $rootDir = dirname($rootDir);
}

chdir($rootDir);

$problemas = [];
$solucoes = [];

// 1. Verificar se public/index.php existe e tem permissões corretas
echo "1. Verificando public/index.php...\n";
$indexPath = $rootDir . '/public/index.php';
if (file_exists($indexPath)) {
    $perms = substr(sprintf('%o', fileperms($indexPath)), -4);
    $readable = is_readable($indexPath);
    
    echo "   ✅ Arquivo existe\n";
    echo "   Permissões: $perms\n";
    
    if (!$readable) {
        echo "   ❌ Arquivo NÃO é legível pelo servidor web!\n";
        $problemas[] = "public/index.php não é legível";
        $solucoes[] = "chmod 644 public/index.php";
    } else {
        echo "   ✅ Arquivo é legível\n";
    }
    
    if (intval($perms) < 644) {
        echo "   ⚠️  Permissões muito restritivas (recomendado: 644)\n";
        $solucoes[] = "chmod 644 public/index.php";
    }
} else {
    echo "   ❌ Arquivo não existe!\n";
    $problemas[] = "public/index.php não existe";
}

// 2. Verificar permissões da pasta public/
echo "\n2. Verificando pasta public/...\n";
$publicDir = $rootDir . '/public';
if (is_dir($publicDir)) {
    $perms = substr(sprintf('%o', fileperms($publicDir)), -4);
    $readable = is_readable($publicDir);
    $executable = is_executable($publicDir);
    
    echo "   ✅ Pasta existe\n";
    echo "   Permissões: $perms\n";
    
    if (!$readable) {
        echo "   ❌ Pasta NÃO é legível!\n";
        $problemas[] = "Pasta public/ não é legível";
        $solucoes[] = "chmod 755 public";
    } else {
        echo "   ✅ Pasta é legível\n";
    }
    
    if (!$executable) {
        echo "   ⚠️  Pasta não é executável (necessário para navegação)\n";
        $solucoes[] = "chmod 755 public";
    }
    
    if (intval($perms) < 755) {
        echo "   ⚠️  Permissões muito restritivas (recomendado: 755)\n";
        $solucoes[] = "chmod 755 public";
    }
} else {
    echo "   ❌ Pasta não existe!\n";
    $problemas[] = "Pasta public/ não existe";
}

// 3. Verificar .htaccess (Apache)
echo "\n3. Verificando .htaccess...\n";
$htaccessPath = $rootDir . '/public/.htaccess';
if (file_exists($htaccessPath)) {
    echo "   ✅ Arquivo .htaccess existe\n";
    
    $content = file_get_contents($htaccessPath);
    
    // Verificar se há regras que podem causar 403
    if (strpos($content, 'Deny from all') !== false) {
        echo "   ❌ .htaccess contém 'Deny from all' - isso bloqueia acesso!\n";
        $problemas[] = ".htaccess bloqueia acesso";
        $solucoes[] = "Remova 'Deny from all' do .htaccess";
    }
    
    if (strpos($content, 'Require all denied') !== false) {
        echo "   ❌ .htaccess contém 'Require all denied' - isso bloqueia acesso!\n";
        $problemas[] = ".htaccess bloqueia acesso";
        $solucoes[] = "Remova 'Require all denied' do .htaccess";
    }
    
    // Verificar se redireciona para index.php
    if (strpos($content, 'RewriteRule') === false && strpos($content, 'DirectoryIndex') === false) {
        echo "   ⚠️  .htaccess pode não estar configurado corretamente para Laravel\n";
    }
} else {
    echo "   ⚠️  Arquivo .htaccess não existe (pode ser necessário para Apache)\n";
    $solucoes[] = "Crie .htaccess em public/ com configuração do Laravel";
}

// 4. Verificar se o DocumentRoot está correto
echo "\n4. Verificando estrutura do projeto...\n";
$estrutura = [
    'public/index.php' => 'Arquivo principal',
    'public/.htaccess' => 'Configuração Apache',
    'bootstrap/app.php' => 'Bootstrap Laravel',
    '.env' => 'Configuração',
];

foreach ($estrutura as $arquivo => $desc) {
    $path = $rootDir . '/' . $arquivo;
    if (file_exists($path)) {
        echo "   ✅ $desc\n";
    } else {
        if ($arquivo !== 'public/.htaccess') {
            echo "   ❌ $desc não encontrado: $arquivo\n";
            $problemas[] = "$desc não encontrado";
        }
    }
}

// 5. Verificar permissões do usuário do servidor web
echo "\n5. Verificando permissões de propriedade...\n";
$publicOwner = fileowner($publicDir);
$indexOwner = fileowner($indexPath);

echo "   Proprietário de public/: " . (function_exists('posix_getpwuid') ? posix_getpwuid($publicOwner)['name'] : "UID $publicOwner") . "\n";
echo "   Proprietário de index.php: " . (function_exists('posix_getpwuid') ? posix_getpwuid($indexOwner)['name'] : "UID $indexOwner") . "\n";

// 6. Verificar se há arquivo .htaccess na raiz que pode estar bloqueando
echo "\n6. Verificando .htaccess na raiz...\n";
$rootHtaccess = $rootDir . '/.htaccess';
if (file_exists($rootHtaccess)) {
    echo "   ⚠️  .htaccess encontrado na raiz do projeto\n";
    $content = file_get_contents($rootHtaccess);
    
    if (strpos($content, 'Deny from all') !== false || strpos($content, 'Require all denied') !== false) {
        echo "   ❌ .htaccess na raiz está bloqueando acesso!\n";
        $problemas[] = ".htaccess na raiz bloqueia acesso";
        $solucoes[] = "Remova ou corrija .htaccess na raiz do projeto";
    }
} else {
    echo "   ✅ Nenhum .htaccess na raiz\n";
}

// 7. Verificar .htaccess na raiz
echo "\n7. Verificando .htaccess na raiz...\n";
$rootHtaccess = $rootDir . '/.htaccess';
if (file_exists($rootHtaccess)) {
    echo "   ✅ .htaccess encontrado na raiz\n";
    $content = file_get_contents($rootHtaccess);
    
    // Verificar se contém redirecionamento para public/
    if (strpos($content, 'public/$1') !== false || strpos($content, 'public/') !== false) {
        echo "   ✅ .htaccess redireciona para public/\n";
    } else {
        echo "   ⚠️  .htaccess pode não estar redirecionando corretamente\n";
        echo "   💡 Deve conter: RewriteRule ^(.*)$ public/$1 [L]\n";
        $solucoes[] = "Atualize .htaccess na raiz para redirecionar para public/";
    }
    
    if (strpos($content, 'Deny from all') !== false || strpos($content, 'Require all denied') !== false) {
        echo "   ❌ .htaccess na raiz está bloqueando acesso!\n";
        $problemas[] = ".htaccess na raiz bloqueia acesso";
        $solucoes[] = "Remova 'Deny from all' ou 'Require all denied' do .htaccess na raiz";
    }
} else {
    echo "   ⚠️  .htaccess não encontrado na raiz\n";
    echo "   💡 Crie .htaccess na raiz para redirecionar para public/\n";
    $solucoes[] = "Crie .htaccess na raiz com: RewriteRule ^(.*)$ public/$1 [L]";
}

// 8. Verificar configuração do servidor (se possível)
echo "\n8. Informações do servidor...\n";
if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "   Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
}
if (isset($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    echo "   DocumentRoot atual: $docRoot\n";
    echo "   Raiz do projeto: $rootDir\n";
    echo "   DocumentRoot esperado: $rootDir/public\n";
    
    if ($docRoot !== $rootDir . '/public' && $docRoot !== $rootDir) {
        echo "   ⚠️  DocumentRoot não aponta para public/\n";
        echo "   💡 Configure no cPanel ou use .htaccess na raiz\n";
        $problemas[] = "DocumentRoot não está configurado corretamente";
        $solucoes[] = "Configure o DocumentRoot do Apache/Nginx para apontar para: $rootDir/public";
        $solucoes[] = "OU crie/atualize .htaccess na raiz para redirecionar para public/";
    } elseif ($docRoot === $rootDir) {
        echo "   ⚠️  DocumentRoot aponta para raiz (não para public/)\n";
        echo "   💡 Use .htaccess na raiz para redirecionar\n";
        if (!file_exists($rootHtaccess)) {
            $problemas[] = "DocumentRoot aponta para raiz mas não há .htaccess para redirecionar";
            $solucoes[] = "Crie .htaccess na raiz com: RewriteRule ^(.*)$ public/$1 [L]";
        }
    } else {
        echo "   ✅ DocumentRoot está correto\n";
    }
} else {
    echo "   ⚠️  DocumentRoot não detectado (executando via CLI)\n";
    echo "   💡 Execute via navegador ou configure manualmente\n";
}

// Resumo
echo "\n========================================\n";
echo "  RESUMO\n";
echo "========================================\n\n";

if (count($problemas) > 0) {
    echo "❌ PROBLEMAS ENCONTRADOS: " . count($problemas) . "\n";
    foreach ($problemas as $problema) {
        echo "   • $problema\n";
    }
    
    echo "\n🔧 SOLUÇÕES RECOMENDADAS:\n";
    foreach (array_unique($solucoes) as $solucao) {
        echo "   • $solucao\n";
    }
    
    echo "\n📋 COMANDOS PARA EXECUTAR:\n";
    echo "   # Corrigir permissões\n";
    echo "   chmod 755 public\n";
    echo "   chmod 644 public/index.php\n";
    echo "   chmod -R 755 public\n";
    echo "\n";
    echo "   # Se o DocumentRoot estiver errado, configure no Apache/Nginx:\n";
    echo "   # Apache: DocumentRoot $rootDir/public\n";
    echo "   # Nginx: root $rootDir/public;\n";
    echo "\n";
    exit(1);
} else {
    echo "✅ Nenhum problema óbvio encontrado\n";
    echo "\n💡 Possíveis causas adicionais:\n";
    echo "   • DocumentRoot do servidor web não está apontando para public/\n";
    echo "   • Configuração do servidor web bloqueando acesso\n";
    echo "   • Firewall ou regras de segurança do servidor\n";
    echo "   • Problemas com módulos do Apache (mod_rewrite, etc)\n";
    echo "\n";
    exit(0);
}

