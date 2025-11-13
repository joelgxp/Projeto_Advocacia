<?php
/**
 * Script para criar diretório webfonts e copiar arquivos de fonte
 * Execute: php scripts/criar-webfonts.php
 */

$root_dir = __DIR__ . '/..';
chdir($root_dir);

echo "========================================\n";
echo "  Criando Diretório webfonts\n";
echo "========================================\n\n";

// Caminhos
$font_source = $root_dir . '/public/fonts/fontawesome';
$webfonts_public = $root_dir . '/public/css/webfonts';
$webfonts_assets = $root_dir . '/assets/css/webfonts';

// Criar diretórios
$dirs = array($webfonts_public, $webfonts_assets);

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Criado: $dir\n";
        } else {
            echo "❌ Erro ao criar: $dir\n";
        }
    } else {
        echo "ℹ️  Já existe: $dir\n";
    }
}

// Arquivos de fonte
$font_files = array(
    'fa-solid-900.woff2',
    'fa-solid-900.ttf',
    'fa-regular-400.woff2',
    'fa-regular-400.ttf',
    'fa-brands-400.woff2',
    'fa-brands-400.ttf'
);

// Copiar arquivos
echo "\nCopiando arquivos de fonte...\n";
foreach ($font_files as $file) {
    $source = $font_source . '/' . $file;
    
    // Se o arquivo .woff2 existe mas .ttf não, pular .ttf
    if (strpos($file, '.ttf') !== false) {
        $woff2_file = str_replace('.ttf', '.woff2', $file);
        if (!file_exists($font_source . '/' . $woff2_file)) {
            continue;
        }
    }
    
    if (file_exists($source)) {
        // Copiar para public/css/webfonts
        $dest_public = $webfonts_public . '/' . $file;
        if (copy($source, $dest_public)) {
            echo "✅ Copiado para public: $file\n";
        } else {
            echo "❌ Erro ao copiar para public: $file\n";
        }
        
        // Copiar para assets/css/webfonts (se diretório existir)
        if (is_dir(dirname($webfonts_assets))) {
            $dest_assets = $webfonts_assets . '/' . $file;
            if (copy($source, $dest_assets)) {
                echo "✅ Copiado para assets: $file\n";
            } else {
                echo "❌ Erro ao copiar para assets: $file\n";
            }
        }
    } else {
        echo "⚠️  Arquivo não encontrado: $file\n";
    }
}

echo "\n========================================\n";
echo "✅ Concluído!\n";
echo "========================================\n";
echo "\nOs arquivos de fonte agora estão em:\n";
echo "  - public/css/webfonts/\n";
if (is_dir(dirname($webfonts_assets))) {
    echo "  - assets/css/webfonts/\n";
}
echo "\n";

