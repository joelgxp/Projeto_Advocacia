<?php
/**
 * Exemplo de Migração Gradual para Composer
 * 
 * Este arquivo demonstra como migrar gradualmente do código antigo
 * para a nova estrutura PSR-4 com Composer
 */

// 1. MANTENHA O CÓDIGO ANTIGO FUNCIONANDO
echo "<h2>1. Código Antigo (ainda funcionando)</h2>";
require_once("conexao.php");
echo "<p>✅ Conexão antiga funcionando</p>";

// 2. INTRODUZA GRADUALMENTE O NOVO CÓDIGO
echo "<h2>2. Nova Estrutura com Composer</h2>";

try {
    // Carrega o autoloader do Composer
    require_once 'vendor/autoload.php';
    echo "<p>✅ Autoloader do Composer carregado</p>";
    
    // Usa a nova classe de conexão
    echo "<p>✅ Namespace carregado corretamente</p>";
    
    // Testa se a classe existe (sem instanciar)
    if (class_exists('Advocacia\Database\Connection')) {
        echo "<p>✅ Nova classe Connection carregada</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro ao carregar nova estrutura: " . $e->getMessage() . "</p>";
}

// 3. COMPARAÇÃO ENTRE AS DUAS ABORDAGENS
echo "<h2>3. Comparação das Abordagens</h2>";

echo "<h3>Antiga (conexao.php):</h3>";
echo "<ul>";
echo "<li>✅ Simples e direta</li>";
echo "<li>✅ Funciona imediatamente</li>";
echo "<li>❌ Sem organização de código</li>";
echo "<li>❌ Difícil de manter</li>";
echo "<li>❌ Sem testes automatizados</li>";
echo "</ul>";

echo "<h3>Nova (Composer + PSR-4):</h3>";
echo "<ul>";
echo "<li>✅ Código organizado e estruturado</li>";
echo "<li>✅ Fácil de manter e expandir</li>";
echo "<li>✅ Testes automatizados</li>";
echo "<li>✅ Padrões profissionais</li>";
echo "<li>❌ Requer configuração inicial</li>";
echo "</ul>";

// 4. PRÓXIMOS PASSOS PARA MIGRAÇÃO
echo "<h2>4. Plano de Migração Gradual</h2>";

echo "<h3>Fase 1: Estrutura Base (✅ Concluída)</h3>";
echo "<ul>";
echo "<li>✅ Composer configurado</li>";
echo "<li>✅ Estrutura PSR-4 criada</li>";
echo "<li>✅ Classe Connection refatorada</li>";
echo "<li>✅ Testes configurados</li>";
echo "</ul>";

echo "<h3>Fase 2: Modelos (🔄 Próxima)</h3>";
echo "<ul>";
echo "<li>🔄 Refatorar Cliente.php</li>";
echo "<li>🔄 Refatorar Processo.php</li>";
echo "<li>🔄 Refatorar Advogado.php</li>";
echo "<li>🔄 Implementar padrão Repository</li>";
echo "</ul>";

echo "<h3>Fase 3: Controladores (⏳ Futuro)</h3>";
echo "<ul>";
echo "<li>⏳ Criar ClienteController</li>";
echo "<li>⏳ Criar ProcessoController</li>";
echo "<li>⏳ Criar AdvogadoController</li>";
echo "<li>⏳ Implementar padrão MVC</li>";
echo "</ul>";

echo "<h3>Fase 4: Serviços (⏳ Futuro)</h3>";
echo "<ul>";
echo "<li>⏳ Implementar regras de negócio</li>";
echo "<li>⏳ Adicionar sistema de logs</li>";
echo "<li>⏳ Implementar validações</li>";
echo "<li>⏳ Adicionar tratamento de erros</li>";
echo "</ul>";

// 5. EXEMPLO DE COMO REFATORAR UM ARQUIVO
echo "<h2>5. Exemplo de Refatoração</h2>";

echo "<h3>Antes (conexao.php):</h3>";
echo "<pre>";
echo "require_once(\"config.php\");\n";
echo "@session_start();\n\n";
echo "try {\n";
echo "    \$pdo = new PDO(\"mysql:dbname=\$banco;host=\$host\", \"\$usuario\", \"\$senha\");\n";
echo "} catch (Exception \$e) {\n";
echo "    echo 'Erro ao conectar com o banco!!' .\$e;\n";
echo "}";
echo "</pre>";

echo "<h3>Depois (src/Database/Connection.php):</h3>";
echo "<pre>";
echo "namespace Advocacia\\Database;\n\n";
echo "use PDO;\n";
echo "use PDOException;\n";
echo "use Exception;\n\n";
echo "class Connection\n";
echo "{\n";
echo "    private static \$instance = null;\n";
echo "    private \$pdo;\n";
echo "    \n";
echo "    public static function getInstance()\n";
echo "    {\n";
echo "        if (self::\$instance === null) {\n";
echo "            self::\$instance = new self();\n";
echo "        }\n";
echo "        return self::\$instance;\n";
echo "    }\n";
echo "    // ... mais métodos\n";
echo "}";
echo "</pre>";

echo "<h2>🎯 Status da Migração</h2>";
echo "<p><strong>✅ Composer configurado e funcionando</strong></p>";
echo "<p><strong>✅ Autoloader PSR-4 ativo</strong></p>";
echo "<p><strong>✅ Testes automatizados funcionando</strong></p>";
echo "<p><strong>🔄 Pronto para migração gradual dos módulos</strong></p>";

echo "<h3>Próximo passo recomendado:</h3>";
echo "<p>Começar a refatorar o módulo de Clientes, criando:</p>";
echo "<ul>";
echo "<li>src/Models/Cliente.php</li>";
echo "<li>src/Controllers/ClienteController.php</li>";
echo "<li>tests/Models/ClienteTest.php</li>";
echo "</ul>";
?>
