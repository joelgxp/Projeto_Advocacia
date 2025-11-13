<?php
/**
 * Script para capturar e exibir erros 500
 * Coloque este arquivo na raiz do projeto e acesse via navegador
 * ou execute: php scripts/capturar-erro-500.php
 */

// Habilitar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$root_dir = __DIR__ . '/..';
chdir($root_dir);

// Definir constantes do CodeIgniter
define('ENVIRONMENT', 'development'); // Mudar para development para ver erros
define('BASEPATH', $root_dir . '/system/');
define('APPPATH', $root_dir . '/application/');
define('FCPATH', $root_dir . '/');

echo "<!DOCTYPE html>\n";
echo "<html><head><meta charset='UTF-8'><title>Teste de Login</title></head><body>\n";
echo "<h1>Teste de Login - Captura de Erros</h1>\n";
echo "<pre>\n";

try {
    // Simular requisição POST
    $_POST['email'] = 'teste@example.com';
    $_POST['senha'] = 'senha123';
    
    // Carregar CodeIgniter
    require_once BASEPATH . 'core/CodeIgniter.php';
    
    echo "✅ CodeIgniter carregado com sucesso\n";
    
} catch (Throwable $e) {
    echo "❌ ERRO CAPTURADO:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    
    // Verificar se é erro de propriedade dinâmica
    if (strpos($e->getMessage(), 'dynamic property') !== false) {
        echo "\n💡 Este é um erro de propriedade dinâmica (PHP 8.2+).\n";
        echo "   Verifique se o index.php está suprimindo warnings de deprecação.\n";
    }
}

echo "</pre>\n";
echo "</body></html>\n";

