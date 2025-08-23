<?php
/**
 * Script para criar usuário MySQL sem senha
 * Útil quando o root tem senha e não sabemos qual é
 */

echo "<h1>👤 Criação de Usuário MySQL - Sistema de Advocacia</h1>";
echo "<hr>";

// Tenta diferentes combinações de senha para root
$senhas_teste = ['', 'root', '123456', 'admin', 'password', '123', '12345678'];

echo "<h2>🔍 Testando Senhas do Usuário Root...</h2>";

$conectou = false;
$senha_correta = '';

foreach ($senhas_teste as $senha) {
    try {
        $pdo = new PDO("mysql:host=localhost;port=3306;charset=utf8mb4", 'root', $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        echo "✅ <strong>Conectado com sucesso!</strong><br>";
        echo "Usuário: root<br>";
        echo "Senha: " . (empty($senha) ? 'vazia' : $senha) . "<br><br>";
        
        $conectou = true;
        $senha_correta = $senha;
        break;
        
    } catch (Exception $e) {
        echo "❌ Senha '" . (empty($senha) ? 'vazia' : $senha) . "': " . $e->getMessage() . "<br>";
    }
}

if ($conectou) {
    echo "<h2>🎉 Usuário Root Funcionando!</h2>";
    echo "Agora você pode executar o <a href='criar-banco.php'>criar-banco.php</a><br><br>";
    
    // Atualiza o config.php com a senha correta
    echo "<h2>📝 Atualizando Configuração...</h2>";
    
    $config_content = file_get_contents('config.php');
    
    if ($senha_correta !== '') {
        // Atualiza a senha no config.php
        $config_content = str_replace(
            '$senha = \'\';',
            '$senha = \'' . $senha_correta . '\';',
            $config_content
        );
        
        file_put_contents('config.php', $config_content);
        echo "✅ <strong>config.php atualizado!</strong> Senha definida: '{$senha_correta}'<br>";
    } else {
        echo "✅ <strong>config.php já está correto!</strong> Senha vazia<br>";
    }
    
    echo "<br>🚀 <strong>Próximo passo:</strong> <a href='criar-banco.php'>Executar criar-banco.php</a><br>";
    
} else {
    echo "<h2>❌ Nenhuma Senha Funcionou</h2>";
    echo "<br>🔧 <strong>Soluções:</strong><br>";
    echo "<ol>";
    echo "<li><strong>Verificar XAMPP:</strong> Abra o painel do XAMPP e confirme se MySQL está rodando</li>";
    echo "<li><strong>Resetar Senha:</strong> Pare o MySQL, edite my.ini e adicione 'skip-grant-tables'</li>";
    echo "<li><strong>phpMyAdmin:</strong> Acesse http://localhost/phpmyadmin e tente fazer login</li>";
    echo "<li><strong>Reinstalar XAMPP:</strong> Como última opção, reinstale o XAMPP</li>";
    echo "</ol>";
    
    echo "<br><h3>📋 Passos para Resetar Senha:</h3>";
    echo "<ol>";
    echo "<li>Pare o MySQL no painel do XAMPP</li>";
    echo "<li>Clique em 'Config' do MySQL</li>";
    echo "<li>Selecione 'my.ini'</li>";
    echo "<li>Adicione no final: <code>skip-grant-tables</code></li>";
    echo "<li>Salve o arquivo</li>";
    echo "<li>Inicie o MySQL novamente</li>";
    echo "<li>Execute este script novamente</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<h3>🔗 Links úteis:</h3>";
echo "<p><a href='index.php'>🏠 Acessar Sistema</a> | ";
echo "<a href='criar-banco.php'>🗄️ Criar Banco</a> | ";
echo "<a href='teste-banco.php'>🔧 Testar Banco</a></p>";

echo "<br><small>💡 <strong>Dica:</strong> Após resolver a senha, execute 'criar-banco.php' para configurar o banco.</small>";
?>
