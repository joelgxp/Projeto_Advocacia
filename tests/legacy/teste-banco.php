<?php
/**
 * Teste de Configuração do Banco de Dados
 * 
 * Este arquivo testa se as configurações do banco estão corretas
 */

// Carrega o autoloader do Composer
require_once 'vendor/autoload.php';

use Advocacia\Config\Database;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Configuração do Banco - Sistema de Advocacia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #2c3e50; }
        .status { 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 8px; 
            border-left: 5px solid;
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            border-left-color: #28a745;
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border-left-color: #dc3545;
        }
        .warning { 
            background: #fff3cd; 
            color: #856404; 
            border-left-color: #ffc107;
        }
        .info { 
            background: #d1ecf1; 
            color: #0c5460; 
            border-left-color: #17a2b8;
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .config-table th, .config-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .config-table th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .code {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            border: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Teste de Configuração do Banco de Dados</h1>
        
        <div class="status info">
            <h3>📋 Informações do Sistema</h3>
            <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
            <p><strong>Versão do PHP:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Extensões PDO:</strong> <?php echo implode(', ', PDO::getAvailableDrivers()); ?></p>
        </div>

        <?php
        // Testa a validação das configurações
        $validationErrors = Database::validate();
        
        if (empty($validationErrors)) {
            echo '<div class="status success">✅ <strong>Configurações válidas!</strong> Todos os campos obrigatórios estão preenchidos.</div>';
        } else {
            echo '<div class="status error">❌ <strong>Erros de configuração encontrados:</strong></div>';
            foreach ($validationErrors as $error) {
                echo '<div class="status error">• ' . htmlspecialchars($error) . '</div>';
            }
        }
        
        // Mostra as configurações atuais
        $config = Database::getConfig();
        ?>
        
        <div class="status info">
            <h3>⚙️ Configurações Atuais do Banco</h3>
            <table class="config-table">
                <tr>
                    <th>Configuração</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>Host</td>
                    <td><?php echo htmlspecialchars($config['host']); ?></td>
                    <td><?php echo !empty($config['host']) ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>Porta</td>
                    <td><?php echo htmlspecialchars($config['port']); ?></td>
                    <td>✅</td>
                </tr>
                <tr>
                    <td>Banco de Dados</td>
                    <td><?php echo htmlspecialchars($config['database']); ?></td>
                    <td><?php echo !empty($config['database']) ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>Usuário</td>
                    <td><?php echo htmlspecialchars($config['username']); ?></td>
                    <td><?php echo !empty($config['username']) ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>Senha</td>
                    <td><?php echo !empty($config['password']) ? '***' : '<em>vazia</em>'; ?></td>
                    <td><?php echo '✅'; ?></td>
                </tr>
                <tr>
                    <td>Charset</td>
                    <td><?php echo htmlspecialchars($config['charset']); ?></td>
                    <td>✅</td>
                </tr>
            </table>
        </div>

        <?php
        // Testa a conexão com o banco
        echo '<h3>🔌 Teste de Conexão</h3>';
        
        try {
            $connectionTest = Database::testConnection();
            
            if ($connectionTest['success']) {
                echo '<div class="status success">';
                echo '✅ <strong>' . htmlspecialchars($connectionTest['message']) . '</strong><br>';
                echo '<strong>Host:</strong> ' . htmlspecialchars($connectionTest['info']['host']) . '<br>';
                echo '<strong>Banco:</strong> ' . htmlspecialchars($connectionTest['info']['database']) . '<br>';
                echo '<strong>PHP:</strong> ' . htmlspecialchars($connectionTest['info']['php_version']) . '<br>';
                echo '<strong>Drivers PDO:</strong> ' . implode(', ', $connectionTest['info']['pdo_drivers']);
                echo '</div>';
            } else {
                echo '<div class="status error">';
                echo '❌ <strong>' . htmlspecialchars($connectionTest['message']) . '</strong><br>';
                if (isset($connectionTest['error_code'])) {
                    echo '<strong>Código do erro:</strong> ' . htmlspecialchars($connectionTest['error_code']);
                }
                echo '</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="status error">❌ <strong>Erro ao testar conexão:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>

        <div class="status info">
            <h3>📁 Arquivos de Configuração</h3>
            <p><strong>Configuração principal:</strong> <code>config.php</code></p>
            <p><strong>Classe de configuração:</strong> <code>src/Config/Database.php</code></p>
            <p><strong>Classe de conexão:</strong> <code>src/Database/Connection.php</code></p>
        </div>

        <div class="status warning">
            <h3>⚠️ Solução de Problemas</h3>
            
            <h4>Se a conexão falhar:</h4>
            <ol>
                <li><strong>Verifique se o MySQL está rodando</strong></li>
                <li><strong>Confirme as credenciais no arquivo config.php</strong></li>
                <li><strong>Verifique se o banco 'advocacia' existe</strong></li>
                <li><strong>Teste a conexão via phpMyAdmin ou MySQL Workbench</strong></li>
            </ol>
            
            <h4>Para criar o banco:</h4>
            <div class="code">
                CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            </div>
        </div>

        <div class="status info">
            <h3>🔗 Links Úteis</h3>
            <p><a href="teste-servidor.php">🖥️ Teste do Servidor</a> | 
               <a href="exemplo-migracao.php">🔄 Exemplo de Migração</a> | 
               <a href="index.php">🏠 Página Principal</a></p>
        </div>
    </div>
</body>
</html>
