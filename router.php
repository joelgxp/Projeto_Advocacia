<?php
/**
 * Router para servidor PHP built-in
 * Garante que usa index.php da raiz (CodeIgniter) e não public/index.php (Laravel)
 */

// Se for um arquivo estático (CSS, JS, imagens), servir diretamente
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '/';

// Remover query string do path para verificação
$pathWithoutQuery = $path;

// Verificar se é um arquivo estático
$staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot'];
$extension = pathinfo($pathWithoutQuery, PATHINFO_EXTENSION);

if (in_array(strtolower($extension), $staticExtensions)) {
    $filePath = null;
    
    // Se começar com /assets/, procurar em assets/ (raiz)
    if (strpos($pathWithoutQuery, '/assets/') === 0) {
        $filePath = __DIR__ . $pathWithoutQuery;
    }
    // Se começar com /public/, procurar em public/
    elseif (strpos($pathWithoutQuery, '/public/') === 0) {
        $filePath = __DIR__ . $pathWithoutQuery;
    }
    // Caso contrário, tentar assets/ primeiro, depois public/
    else {
        // Tentar assets/ primeiro
        $filePath = __DIR__ . '/assets' . $pathWithoutQuery;
        if (!file_exists($filePath) || !is_file($filePath)) {
            // Tentar public/
            $filePath = __DIR__ . '/public' . $pathWithoutQuery;
        }
    }
    
    if ($filePath && file_exists($filePath) && is_file($filePath)) {
        $mimeTypes = [
            'css' => 'text/css; charset=utf-8',
            'js' => 'application/javascript; charset=utf-8',
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
        
        // Headers para cache (opcional, mas útil)
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        
        // Ler e enviar arquivo
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

