<?php
/**
 * Arquivo de teste para verificar se o servidor está funcionando
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Advocacia - Teste do Servidor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 5px; 
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .links { margin-top: 20px; }
        .links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .links a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Sistema de Advocacia - Servidor Funcionando!</h1>
        
        <div class="status success">
            ✅ <strong>Servidor PHP funcionando perfeitamente!</strong>
        </div>
        
        <div class="status info">
            📅 <strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i:s'); ?><br>
            🐘 <strong>Versão do PHP:</strong> <?php echo PHP_VERSION; ?><br>
            🌐 <strong>Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Built-in Server'; ?>
        </div>

        <?php
        // Testa se o Composer está funcionando
        if (file_exists('vendor/autoload.php')) {
            echo '<div class="status success">✅ <strong>Composer:</strong> Autoloader encontrado e funcionando!</div>';
            
            require_once 'vendor/autoload.php';
            
            if (class_exists('Advocacia\Database\Connection')) {
                echo '<div class="status success">✅ <strong>PSR-4:</strong> Classes carregadas corretamente!</div>';
            } else {
                echo '<div class="status warning">⚠️ <strong>PSR-4:</strong> Classe Connection não encontrada!</div>';
            }
        } else {
            echo '<div class="status warning">⚠️ <strong>Composer:</strong> Autoloader não encontrado!</div>';
        }
        ?>

        <div class="links">
            <h3>🔗 Links Úteis:</h3>
            <a href="index.php">📋 Página Principal</a>
            <a href="criar-usuario-mysql.php" style="background: #dc3545;">👤 Resolver Senha MySQL</a>
            <a href="criar-banco.php" style="background: #28a745;">🗄️ Criar Banco</a>
            <a href="teste-banco.php">🔧 Teste do Banco</a>
            <a href="teste-porta.php">🔌 Teste da Porta</a>
            <a href="exemplo-migracao.php">🔄 Exemplo de Migração</a>
            <a href="exemplo-uso-composer.php">⚙️ Teste do Composer</a>
            <a href="admin/index.php">👨‍💼 Área Admin</a>
            <a href="advogado/index.php">⚖️ Área Advogado</a>
            <a href="recepcao/index.php">📞 Área Recepção</a>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3>📖 Como acessar:</h3>
            <p><strong>URL do servidor:</strong> <code>http://localhost:8000</code></p>
            <p><strong>Este arquivo:</strong> <code>http://localhost:8000/teste-servidor.php</code></p>
            
            <h4>💻 Comandos úteis:</h4>
            <ul>
                <li><code>composer test</code> - Executar testes</li>
                <li><code>composer dev</code> - Iniciar servidor</li>
                <li><code>php -S localhost:8000</code> - Servidor manual</li>
            </ul>
        </div>
    </div>
</body>
</html>
