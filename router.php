<?php
/**
 * Router para servidor PHP built-in
 * Garante que usa index.php da raiz (CodeIgniter) e não public/index.php (Laravel)
 */

// Se for um arquivo estático (CSS, JS, imagens), servir diretamente
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '/';

// Verificar se é um arquivo estático
$staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot'];
$extension = pathinfo($path, PATHINFO_EXTENSION);

if (in_array(strtolower($extension), $staticExtensions)) {
    // Tentar servir de assets/ ou public/
    $filePath = __DIR__ . $path;
    
    // Se começar com /assets/, procurar em assets/
    if (strpos($path, '/assets/') === 0) {
        $filePath = __DIR__ . $path;
    }
    // Se começar com /public/, procurar em public/
    elseif (strpos($path, '/public/') === 0) {
        $filePath = __DIR__ . $path;
    }
    // Caso contrário, tentar assets/ primeiro, depois public/
    else {
        $filePath = __DIR__ . '/assets' . $path;
        if (!file_exists($filePath)) {
            $filePath = __DIR__ . '/public' . $path;
        }
    }
    
    if (file_exists($filePath) && is_file($filePath)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $mimeType = isset($mimeTypes[strtolower($extension)]) ? $mimeTypes[strtolower($extension)] : 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    }
}

// Para todas as outras requisições, usar index.php da raiz (CodeIgniter)
// IMPORTANTE: NÃO usar public/index.php (Laravel)
if (file_exists(__DIR__ . '/index.php')) {
    // Limpar qualquer referência ao public/
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    $_SERVER['REQUEST_URI'] = str_replace('/public', '', $_SERVER['REQUEST_URI']);
    
    // Garantir que não está tentando acessar public/index.php
    if (strpos($_SERVER['REQUEST_URI'], '/public/') === 0) {
        $_SERVER['REQUEST_URI'] = str_replace('/public', '', $_SERVER['REQUEST_URI']);
    }
    
    chdir(__DIR__);
    require __DIR__ . '/index.php';
} else {
    http_response_code(404);
    echo 'index.php not found in root directory. Make sure you are using CodeIgniter index.php, not Laravel public/index.php';
}

