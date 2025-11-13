<?php
/**
 * Script de Correção de Problemas - Sistema de Advocacia
 * Execute: php scripts/corrigir-problemas.php
 * 
 * Este script corrige problemas comuns identificados pelo diagnóstico
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo "  CORREÇÃO DE PROBLEMAS - Sistema Advocacia\n";
echo "========================================\n\n";

// Detectar raiz do projeto
$rootDir = __DIR__;
if (basename($rootDir) === 'scripts') {
    $rootDir = dirname($rootDir);
}

chdir($rootDir);

$correcoes = [];
$erros = [];

// 1. Criar pasta storage/framework/sessions
echo "1. Criando pasta storage/framework/sessions...\n";
$sessionsDir = $rootDir . '/storage/framework/sessions';
if (!is_dir($sessionsDir)) {
    if (mkdir($sessionsDir, 0775, true)) {
        echo "   ✅ Pasta criada com sucesso\n";
        $correcoes[] = "Pasta storage/framework/sessions criada";
    } else {
        echo "   ❌ ERRO ao criar pasta\n";
        $erros[] = "Não foi possível criar storage/framework/sessions";
    }
} else {
    echo "   ✅ Pasta já existe\n";
}

// 2. Verificar e criar tabela users
echo "\n2. Verificando tabela 'users'...\n";

// Carregar configuração do .env
$envPath = $rootDir . '/.env';
if (!file_exists($envPath)) {
    echo "   ❌ Arquivo .env não encontrado!\n";
    $erros[] = "Arquivo .env não encontrado";
    exit(1);
}

$envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dbConfig = [];
foreach ($envLines as $line) {
    if (strpos($line, 'DB_') === 0 && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        list($key, $value) = explode('=', $line, 2);
        $dbConfig[trim($key)] = trim($value);
    }
}

$host = $dbConfig['DB_HOST'] ?? 'localhost';
$port = $dbConfig['DB_PORT'] ?? '3306';
$database = $dbConfig['DB_DATABASE'] ?? '';
$username = $dbConfig['DB_USERNAME'] ?? '';
$password = $dbConfig['DB_PASSWORD'] ?? '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // Verificar se tabela users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $usersExists = $stmt->rowCount() > 0;
    
    if (!$usersExists) {
        echo "   ⚠️  Tabela 'users' não existe. Criando...\n";
        
        $sql = "CREATE TABLE `users` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            `cpf` varchar(14) DEFAULT NULL,
            `telefone` varchar(20) DEFAULT NULL,
            `ativo` tinyint(1) NOT NULL DEFAULT 1,
            `remember_token` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_email_unique` (`email`),
            UNIQUE KEY `users_cpf_unique` (`cpf`),
            KEY `users_email_index` (`email`),
            KEY `users_cpf_index` (`cpf`),
            KEY `users_ativo_index` (`ativo`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($sql);
        echo "   ✅ Tabela 'users' criada com sucesso\n";
        $correcoes[] = "Tabela 'users' criada";
    } else {
        echo "   ✅ Tabela 'users' já existe\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ ERRO: " . $e->getMessage() . "\n";
    $erros[] = "Erro ao criar tabela users: " . $e->getMessage();
}

// 3. Verificar e criar tabela advogados
echo "\n3. Verificando tabela 'advogados'...\n";

try {
    // Verificar se tabela advogados existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'advogados'");
    $advogadosExists = $stmt->rowCount() > 0;
    
    if (!$advogadosExists) {
        echo "   ⚠️  Tabela 'advogados' não existe. Criando...\n";
        
        // Primeiro verificar se users existe (é necessária para a foreign key)
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        if ($stmt->rowCount() === 0) {
            echo "   ❌ ERRO: Tabela 'users' não existe. Crie-a primeiro.\n";
            $erros[] = "Tabela 'users' necessária para criar 'advogados'";
        } else {
            $sql = "CREATE TABLE `advogados` (
                `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) UNSIGNED NOT NULL,
                `oab` varchar(20) DEFAULT NULL,
                `foto` varchar(255) DEFAULT NULL,
                `biografia` text DEFAULT NULL,
                `ativo` tinyint(1) NOT NULL DEFAULT 1,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `advogados_oab_unique` (`oab`),
                KEY `advogados_user_id_foreign` (`user_id`),
                KEY `advogados_oab_index` (`oab`),
                KEY `advogados_ativo_index` (`ativo`),
                CONSTRAINT `advogados_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $pdo->exec($sql);
            echo "   ✅ Tabela 'advogados' criada com sucesso\n";
            $correcoes[] = "Tabela 'advogados' criada";
        }
    } else {
        echo "   ✅ Tabela 'advogados' já existe\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ ERRO: " . $e->getMessage() . "\n";
    $erros[] = "Erro ao criar tabela advogados: " . $e->getMessage();
}

// Resumo
echo "\n========================================\n";
echo "  RESUMO DAS CORREÇÕES\n";
echo "========================================\n\n";

if (count($correcoes) > 0) {
    echo "✅ Correções aplicadas: " . count($correcoes) . "\n";
    foreach ($correcoes as $correcao) {
        echo "   • $correcao\n";
    }
}

if (count($erros) > 0) {
    echo "\n❌ Erros encontrados: " . count($erros) . "\n";
    foreach ($erros as $erro) {
        echo "   • $erro\n";
    }
    echo "\n";
    exit(1);
} else {
    echo "\n✅ Todas as correções foram aplicadas com sucesso!\n";
    echo "\n📋 Próximos passos:\n";
    echo "   1. Execute o diagnóstico novamente: php scripts/diagnosticar-erros.php\n";
    echo "   2. Verifique se todos os problemas foram resolvidos\n";
    echo "\n";
    exit(0);
}

