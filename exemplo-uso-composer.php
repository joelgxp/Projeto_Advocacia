<?php
/**
 * Exemplo de uso do Composer e da nova estrutura PSR-4
 * 
 * Este arquivo demonstra como usar as classes refatoradas
 * após a implementação do Composer
 */

// Carrega o autoloader do Composer
require_once 'vendor/autoload.php';

// Inicia a sessão
session_start();

// Usa a nova classe de conexão
use Advocacia\Database\Connection;

try {
    // Obtém instância da conexão
    $db = Connection::getInstance();
    
    // Exemplo de consulta usando a nova estrutura
    $clientes = $db->fetchAll("SELECT * FROM clientes LIMIT 5");
    
    echo "<h2>Clientes encontrados:</h2>";
    foreach ($clientes as $cliente) {
        echo "<p>Nome: {$cliente['nome']}</p>";
    }
    
    // Exemplo de transação
    $db->beginTransaction();
    
    try {
        // Suas operações aqui
        $db->query("INSERT INTO log_acesso (usuario, data_acesso) VALUES (?, NOW())", ['usuario_teste']);
        
        $db->commit();
        echo "<p>Transação executada com sucesso!</p>";
        
    } catch (Exception $e) {
        $db->rollback();
        echo "<p>Erro na transação: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>Erro: " . $e->getMessage() . "</p>";
}

// Exemplo de como migrar gradualmente
echo "<h3>Como migrar gradualmente:</h3>";
echo "<ol>";
echo "<li>Mantenha os arquivos antigos funcionando</li>";
echo "<li>Refatore um arquivo por vez</li>";
echo "<li>Teste cada mudança</li>";
echo "<li>Atualize as referências gradualmente</li>";
echo "</ol>";

echo "<h3>Próximos passos recomendados:</h3>";
echo "<ol>";
echo "<li>Refatorar modelos (Clientes, Processos, Advogados)</li>";
echo "<li>Criar controladores para cada módulo</li>";
echo "<li>Implementar validações e regras de negócio</li>";
echo "<li>Adicionar tratamento de erros robusto</li>";
echo "<li>Implementar sistema de logs</li>";
echo "</ol>";
?>

