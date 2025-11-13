<?php
/**
 * Script para criar diretórios necessários
 * Execute: php scripts/criar-diretorios.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Criando Diretórios Necessários\n";
echo "========================================\n\n";

$dirs = array(
    'application/logs' => 0777,
    'application/cache' => 0777,
    'application/sessions' => 0777,
);

foreach ($dirs as $dir => $perms) {
    $full_path = $root_dir . '/' . $dir;
    
    if (!is_dir($full_path)) {
        if (mkdir($full_path, $perms, true)) {
            echo "✅ Criado: $dir\n";
            
            // Criar arquivo .htaccess para proteger logs
            if ($dir === 'application/logs') {
                $htaccess = $full_path . '/.htaccess';
                file_put_contents($htaccess, "deny from all\n");
                echo "   ✅ .htaccess criado para proteger logs\n";
            }
            
            // Criar arquivo index.html vazio para proteger diretórios
            $index = $full_path . '/index.html';
            file_put_contents($index, "<!DOCTYPE html>\n<html><head><title>403 Forbidden</title></head><body><h1>403 Forbidden</h1></body></html>\n");
        } else {
            echo "❌ Erro ao criar: $dir\n";
        }
    } else {
        echo "ℹ️  Já existe: $dir\n";
        
        // Verificar permissões
        $current_perms = substr(sprintf('%o', fileperms($full_path)), -4);
        $writable = is_writable($full_path);
        echo "   Permissões: $current_perms, Gravável: " . ($writable ? 'Sim' : 'Não') . "\n";
        
        if (!$writable) {
            if (chmod($full_path, $perms)) {
                echo "   ✅ Permissões ajustadas\n";
            } else {
                echo "   ⚠️  Não foi possível ajustar permissões\n";
            }
        }
    }
}

echo "\n========================================\n";
echo "✅ Concluído!\n";
echo "========================================\n";

