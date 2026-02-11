<?php
/**
 * Script para testar se os assets estão acessíveis
 */

echo "========================================\n";
echo "  TESTE DE ACESSO AOS ASSETS\n";
echo "========================================\n\n";

$baseUrl = 'http://localhost:8000';
$assets = [
    'CSS Bootstrap' => '/assets/css/vendor/bootstrap.min.css',
    'CSS Font Awesome' => '/assets/css/vendor/fontawesome.min.css',
    'CSS Style' => '/assets/css/style.css',
    'JS jQuery' => '/assets/js/vendor/jquery.min.js',
    'JS Bootstrap' => '/assets/js/vendor/bootstrap.bundle.min.js',
    'Font Awesome Solid' => '/assets/css/webfonts/fa-solid-900.woff2',
];

echo "Testando acesso aos arquivos...\n\n";

foreach ($assets as $nome => $path) {
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "✅ $nome: OK ($url)\n";
    } else {
        echo "❌ $nome: ERRO $httpCode ($url)\n";
    }
}

echo "\n";
echo "Verificando arquivos no sistema de arquivos...\n\n";

foreach ($assets as $nome => $path) {
    $filePath = __DIR__ . '/..' . $path;
    if (file_exists($filePath)) {
        $size = filesize($filePath);
        echo "✅ $nome: Existe (" . number_format($size) . " bytes)\n";
    } else {
        echo "❌ $nome: Não encontrado em $filePath\n";
    }
}

echo "\n";
echo "========================================\n";
echo "  TESTE CONCLUÍDO\n";
echo "========================================\n";

