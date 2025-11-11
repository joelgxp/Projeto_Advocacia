<?php
/**
 * Teste Específico da Configuração da Porta
 * 
 * Este arquivo testa se a especificação da porta 3306 resolve o problema
 * de conexão com o banco de dados
 */

echo "<h1>🔧 Teste de Configuração da Porta</h1>";
echo "<hr>";

// Carrega as configurações
require_once 'config.php';

echo "<h2>📋 Configurações Atuais:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> {$host}</li>";
echo "<li><strong>Porta:</strong> {$porta}</li>";
echo "<li><strong>Usuário:</strong> {$usuario}</li>";
echo "<li><strong>Senha:</strong> " . (empty($senha) ? 'vazia' : '***') . "</li>";
echo "<li><strong>Banco:</strong> {$banco}</li>";
echo "</ul>";

echo "<h2>🔍 Teste de Conexão:</h2>";

// Teste 1: Sem especificar porta (deve usar padrão 3306)
echo "<h3>Teste 1: Sem especificar porta</h3>";
try {
    $dsn1 = "mysql:host={$host};dbname={$banco};charset=utf8mb4";
    $pdo1 = new PDO($dsn1, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ <strong>Sucesso!</strong> Conexão sem porta especificada funcionou.<br>";
    echo "DSN usado: <code>{$dsn1}</code><br>";
    $pdo1 = null; // Fecha conexão
} catch (Exception $e) {
    echo "❌ <strong>Falha!</strong> " . $e->getMessage() . "<br>";
    echo "DSN usado: <code>{$dsn1}</code><br>";
}

echo "<br>";

// Teste 2: Especificando porta 3306
echo "<h3>Teste 2: Com porta 3306 especificada</h3>";
try {
    $dsn2 = "mysql:host={$host};port={$porta};dbname={$banco};charset=utf8mb4";
    $pdo2 = new PDO($dsn2, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ <strong>Sucesso!</strong> Conexão com porta {$porta} funcionou.<br>";
    echo "DSN usado: <code>{$dsn2}</code><br>";
    $pdo2 = null; // Fecha conexão
} catch (Exception $e) {
    echo "❌ <strong>Falha!</strong> " . $e->getMessage() . "<br>";
    echo "DSN usado: <code>{$dsn2}</code><br>";
}

echo "<br>";

// Teste 3: Teste com arquivo conexao.php
echo "<h3>Teste 3: Usando arquivo conexao.php</h3>";
try {
    require_once 'conexao.php';
    if ($pdo !== null) {
        echo "✅ <strong>Sucesso!</strong> conexao.php funcionou.<br>";
        echo "Variável \$pdo está definida e conectada.<br>";
        
        // Testa uma query simples
        $teste = $pdo->query("SELECT 1 as teste");
        if ($teste) {
            echo "✅ Query de teste executada com sucesso.<br>";
        }
        
    } else {
        echo "❌ <strong>Falha!</strong> conexao.php não conseguiu conectar.<br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>Erro!</strong> " . $e->getMessage() . "<br>";
}

echo "<br>";

// Teste 4: Verificação de serviços
echo "<h3>Teste 4: Status dos Serviços</h3>";

// Verifica se a porta 3306 está em uso
$porta_em_uso = false;
if (function_exists('shell_exec')) {
    $output = shell_exec('netstat -an | findstr :3306 2>&1');
    if ($output && strpos($output, 'LISTENING') !== false) {
        $porta_em_uso = true;
    }
}

if ($porta_em_uso) {
    echo "✅ Porta 3306 está em uso (MySQL provavelmente rodando)<br>";
} else {
    echo "⚠️ Porta 3306 não está em uso ou não foi possível verificar<br>";
}

// Verifica se o MySQL está rodando
if (function_exists('shell_exec')) {
    $mysql_status = shell_exec('tasklist /FI "IMAGENAME eq mysqld.exe" 2>&1');
    if ($mysql_status && strpos($mysql_status, 'mysqld.exe') !== false) {
        echo "✅ Processo MySQL (mysqld.exe) está rodando<br>";
    } else {
        echo "❌ Processo MySQL (mysqld.exe) não está rodando<br>";
    }
}

echo "<br>";

// Teste 5: Sugestões de solução
echo "<h3>Teste 5: Sugestões de Solução</h3>";

if (isset($pdo) && $pdo !== null) {
    echo "🎉 <strong>Problema resolvido!</strong> A conexão está funcionando.<br>";
    echo "Você pode agora acessar o sistema normalmente.<br>";
} else {
    echo "🔧 <strong>Problemas identificados:</strong><br>";
    echo "<ul>";
    echo "<li>Verifique se o XAMPP está rodando</li>";
    echo "<li>Confirme se o MySQL está ativo no painel do XAMPP</li>";
    echo "<li>Teste a conexão via phpMyAdmin</li>";
    echo "<li>Verifique se o banco 'advocacia' existe</li>";
    echo "<li>Confirme se o usuário 'root' tem permissões</li>";
    echo "</ul>";
    
    echo "<h4>📝 Comandos para verificar:</h4>";
    echo "<code>netstat -an | findstr :3306</code> - Verifica se a porta está em uso<br>";
    echo "<code>tasklist /FI \"IMAGENAME eq mysqld.exe\"</code> - Verifica se MySQL está rodando<br>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Voltar ao Sistema</a> | ";
echo "<a href='teste-banco.php'>🔧 Teste Completo do Banco</a> | ";
echo "<a href='teste-servidor.php'>🖥️ Teste do Servidor</a></p>";
?>
