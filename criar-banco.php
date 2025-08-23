<?php
/**
 * Script para criar o banco de dados e tabelas automaticamente
 * 
 * Este arquivo deve ser executado uma única vez para configurar o banco
 */

echo "<h1>🗄️ Criação do Banco de Dados - Sistema de Advocacia</h1>";
echo "<hr>";

// Carrega configurações do config.php
require_once 'config.php';

// Configurações de conexão (sem especificar banco)
$host = $host;
$porta = $porta;
$usuario = $usuario;
$senha = $senha;

try {
    // Primeira conexão: sem especificar banco (para criar o banco)
    echo "<h2>🔌 Conectando ao MySQL...</h2>";
    
    $pdo = new PDO("mysql:host={$host};port={$porta};charset=utf8mb4", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ <strong>Conectado ao MySQL com sucesso!</strong><br>";
    echo "Host: {$host}:{$porta}<br>";
    echo "Usuário: {$usuario}<br><br>";
    
    // Cria o banco de dados
    echo "<h2>📁 Criando banco de dados 'advocacia'...</h2>";
    
    $sql_criar_banco = "CREATE DATABASE IF NOT EXISTS advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql_criar_banco);
    
    echo "✅ <strong>Banco 'advocacia' criado/verificado com sucesso!</strong><br><br>";
    
    // Seleciona o banco criado
    $pdo->exec("USE advocacia");
    echo "✅ <strong>Banco 'advocacia' selecionado!</strong><br><br>";
    
    // Lê e executa o script SQL
    echo "<h2>🏗️ Criando tabelas e inserindo dados...</h2>";
    
    $sql_file = 'criar-banco.sql';
    if (file_exists($sql_file)) {
        $sql_content = file_get_contents($sql_file);
        
        // Remove comentários e linhas vazias
        $sql_lines = explode("\n", $sql_content);
        $sql_commands = [];
        $current_command = '';
        
        foreach ($sql_lines as $line) {
            $line = trim($line);
            
            // Pula comentários e linhas vazias
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
                continue;
            }
            
            $current_command .= $line . ' ';
            
            // Se a linha termina com ;, é um comando completo
            if (substr($line, -1) === ';') {
                $sql_commands[] = trim($current_command);
                $current_command = '';
            }
        }
        
        // Executa cada comando SQL
        $total_commands = count($sql_commands);
        $executed_commands = 0;
        
        foreach ($sql_commands as $index => $sql) {
            try {
                if (trim($sql) === '') continue;
                
                // Pula comandos de comentário
                if (strpos(trim($sql), '--') === 0) continue;
                
                // Fecha qualquer resultado pendente antes de executar novo comando
                if (isset($stmt) && $stmt instanceof PDOStatement) {
                    $stmt->closeCursor();
                }
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $executed_commands++;
                
                echo "✅ Comando " . ($index + 1) . "/{$total_commands} executado<br>";
                
            } catch (Exception $e) {
                echo "⚠️ Comando " . ($index + 1) . "/{$total_commands} com aviso: " . $e->getMessage() . "<br>";
            }
        }
        
        // Fecha o último statement
        if (isset($stmt) && $stmt instanceof PDOStatement) {
            $stmt->closeCursor();
        }
        
        echo "<br>✅ <strong>{$executed_commands} comandos executados com sucesso!</strong><br><br>";
        
    } else {
        echo "❌ <strong>Arquivo SQL não encontrado!</strong><br>";
        echo "Verifique se o arquivo 'criar-banco.sql' existe no diretório.<br><br>";
    }
    
    // Verifica se as tabelas foram criadas
    echo "<h2>🔍 Verificando estrutura do banco...</h2>";
    
    $tabelas = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($tabelas)) {
        echo "✅ <strong>Tabelas criadas:</strong><br>";
        echo "<ul>";
        foreach ($tabelas as $tabela) {
            echo "<li>{$tabela}</li>";
        }
        echo "</ul>";
        
        // Conta registros em cada tabela
        echo "<h3>📊 Dados inseridos:</h3>";
        
        foreach ($tabelas as $tabela) {
            try {
                $count = $pdo->query("SELECT COUNT(*) as total FROM {$tabela}")->fetch()['total'];
                echo "📋 <strong>{$tabela}:</strong> {$count} registro(s)<br>";
            } catch (Exception $e) {
                echo "⚠️ <strong>{$tabela}:</strong> Erro ao contar registros<br>";
            }
        }
        
    } else {
        echo "❌ <strong>Nenhuma tabela foi criada!</strong><br>";
    }
    
    // Testa a conexão com o banco específico
    echo "<h2>🧪 Testando conexão com o banco 'advocacia'...</h2>";
    
    try {
        $teste_pdo = new PDO("mysql:host={$host};port={$porta};dbname=advocacia;charset=utf8mb4", $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        echo "✅ <strong>Conexão com banco 'advocacia' funcionando perfeitamente!</strong><br>";
        
        // Testa uma query simples
        $teste = $teste_pdo->query("SELECT 1 as teste");
        if ($teste) {
            echo "✅ Query de teste executada com sucesso!<br>";
        }
        
        $teste_pdo = null; // Fecha conexão
        
    } catch (Exception $e) {
        echo "❌ <strong>Erro na conexão com banco 'advocacia':</strong> " . $e->getMessage() . "<br>";
    }
    
    echo "<br>🎉 <strong>Configuração do banco concluída!</strong><br>";
    echo "Agora você pode acessar o sistema normalmente.<br><br>";
    
} catch (Exception $e) {
    echo "❌ <strong>Erro fatal:</strong> " . $e->getMessage() . "<br>";
    echo "<br>🔧 <strong>Soluções possíveis:</strong><br>";
    echo "<ul>";
    echo "<li>Verifique se o XAMPP está rodando</li>";
    echo "<li>Confirme se o MySQL está ativo no painel do XAMPP</li>";
    echo "<li>Verifique se a porta 3306 está disponível</li>";
    echo "<li>Confirme se o usuário 'root' tem permissões</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>🔗 Links úteis:</h3>";
echo "<p><a href='index.php'>🏠 Acessar Sistema</a> | ";
echo "<a href='teste-banco.php'>🔧 Testar Banco</a> | ";
echo "<a href='teste-porta.php'>🔌 Testar Porta</a></p>";

echo "<br><small>💡 <strong>Dica:</strong> Execute este script apenas uma vez para configurar o banco.</small>";
?>
