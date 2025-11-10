<?php
/**
 * Script de Verificação da Instalação Local
 * 
 * Execute este arquivo para verificar se tudo está configurado
 * corretamente para rodar o projeto localmente.
 */

echo "========================================\n";
echo "Verificação da Instalação Local\n";
echo "Sistema de Advocacia\n";
echo "========================================\n\n";

$erros = [];
$avisos = [];
$sucessos = [];

// 1. Verificar PHP
echo "[1/8] Verificando PHP...\n";
$phpVersion = PHP_VERSION;
$phpMajor = PHP_MAJOR_VERSION;
$phpMinor = PHP_MINOR_VERSION;

if (version_compare($phpVersion, '7.4.0', '<')) {
    $erros[] = "PHP 7.4 ou superior é necessário! Versão atual: $phpVersion";
    echo "❌ ERRO: PHP 7.4 ou superior é necessário!\n";
} else {
    $sucessos[] = "PHP $phpVersion encontrado";
    echo "✅ PHP encontrado: $phpVersion\n";
}
echo "\n";

// 2. Verificar extensões PHP necessárias
echo "[2/8] Verificando extensões PHP...\n";
$extensoes = ['pdo', 'pdo_mysql', 'mbstring', 'json'];

foreach ($extensoes as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extensão $ext carregada\n";
    } else {
        $erros[] = "Extensão PHP '$ext' não está instalada";
        echo "❌ ERRO: Extensão $ext não encontrada!\n";
    }
}
echo "\n";

// 3. Verificar arquivo config.php
echo "[3/8] Verificando config.php...\n";
if (!file_exists('config.php')) {
    $erros[] = "Arquivo config.php não encontrado";
    echo "❌ ERRO: config.php não encontrado!\n";
} else {
    echo "✅ config.php encontrado\n";
    require_once('config.php');
    
    // Verificar se as variáveis estão definidas
    if (!isset($host) || !isset($usuario) || !isset($banco)) {
        $erros[] = "Variáveis de configuração não definidas em config.php";
        echo "❌ ERRO: Variáveis de configuração não definidas!\n";
    } else {
        echo "   Host: $host\n";
        echo "   Porta: $porta\n";
        echo "   Usuário: $usuario\n";
        echo "   Banco: $banco\n";
        $sucessos[] = "Configurações do banco encontradas";
    }
}
echo "\n";

// 4. Verificar conexão.php
echo "[4/8] Verificando conexao.php...\n";
if (!file_exists('conexao.php')) {
    $erros[] = "Arquivo conexao.php não encontrado";
    echo "❌ ERRO: conexao.php não encontrado!\n";
} else {
    echo "✅ conexao.php encontrado\n";
}
echo "\n";

// 5. Testar conexão com banco de dados
echo "[5/8] Testando conexão com banco de dados...\n";
if (file_exists('conexao.php')) {
    require_once('conexao.php');
    
    if ($pdo === null) {
        $erros[] = "Não foi possível conectar ao banco de dados";
        echo "❌ ERRO: Não foi possível conectar ao banco de dados!\n";
        echo "   Verifique:\n";
        echo "   - Se o MySQL está rodando\n";
        echo "   - Se as credenciais em config.php estão corretas\n";
        echo "   - Se o banco 'advocacia' existe\n";
    } else {
        try {
            // Testar se o banco existe
            $stmt = $pdo->query("SELECT DATABASE()");
            $db = $stmt->fetchColumn();
            
            if ($db === $banco) {
                echo "✅ Conexão com banco '$banco' estabelecida\n";
                $sucessos[] = "Conexão com banco de dados OK";
                
                // Verificar se existem tabelas
                $stmt = $pdo->query("SHOW TABLES");
                $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (count($tabelas) > 0) {
                    echo "   Tabelas encontradas: " . count($tabelas) . "\n";
                    $sucessos[] = "Banco de dados populado com " . count($tabelas) . " tabelas";
                } else {
                    $avisos[] = "Banco de dados existe mas está vazio";
                    echo "⚠️  AVISO: Banco existe mas não tem tabelas\n";
                    echo "   Execute: criar-banco.php ou importe advocacia.sql\n";
                }
            } else {
                $erros[] = "Banco de dados '$banco' não existe";
                echo "❌ ERRO: Banco '$banco' não existe!\n";
                echo "   Execute: criar-banco.php ou importe advocacia.sql\n";
            }
        } catch (Exception $e) {
            $erros[] = "Erro ao acessar banco: " . $e->getMessage();
            echo "❌ ERRO: " . $e->getMessage() . "\n";
        }
    }
} else {
    $avisos[] = "Não foi possível testar conexão (conexao.php não encontrado)";
}
echo "\n";

// 6. Verificar arquivos essenciais
echo "[6/8] Verificando arquivos essenciais...\n";
$arquivos = [
    'index.php' => 'Página de login',
    'autenticar.php' => 'Sistema de autenticação',
    'middleware.php' => 'Middleware de segurança'
];

foreach ($arquivos as $arquivo => $descricao) {
    if (file_exists($arquivo)) {
        echo "✅ $arquivo encontrado ($descricao)\n";
    } else {
        $avisos[] = "Arquivo $arquivo não encontrado";
        echo "⚠️  AVISO: $arquivo não encontrado\n";
    }
}
echo "\n";

// 7. Verificar estrutura de diretórios
echo "[7/8] Verificando estrutura de diretórios...\n";
$diretorios = [
    'admin' => 'Painel administrativo',
    'advogado' => 'Painel do advogado',
    'recepcao' => 'Painel da recepção',
    'css' => 'Estilos CSS',
    'js' => 'Scripts JavaScript',
    'img' => 'Imagens'
];

foreach ($diretorios as $dir => $descricao) {
    if (is_dir($dir)) {
        echo "✅ Diretório $dir/ encontrado ($descricao)\n";
    } else {
        $avisos[] = "Diretório $dir não encontrado";
        echo "⚠️  AVISO: Diretório $dir/ não encontrado\n";
    }
}
echo "\n";

// 8. Verificar Composer (opcional)
echo "[8/8] Verificando Composer (opcional)...\n";
if (file_exists('composer.json')) {
    echo "✅ composer.json encontrado\n";
    
    if (file_exists('vendor/autoload.php')) {
        echo "✅ Composer instalado (vendor/autoload.php encontrado)\n";
        $sucessos[] = "Composer instalado e configurado";
    } else {
        $avisos[] = "Composer não instalado (execute: composer install)";
        echo "⚠️  AVISO: Execute 'composer install' para instalar dependências\n";
    }
} else {
    echo "ℹ️  composer.json não encontrado (opcional)\n";
}
echo "\n";

// Resumo Final
echo "========================================\n";
echo "Resumo da Verificação\n";
echo "========================================\n\n";

if (count($sucessos) > 0) {
    echo "✅ Sucessos (" . count($sucessos) . "):\n";
    foreach ($sucessos as $sucesso) {
        echo "   • $sucesso\n";
    }
    echo "\n";
}

if (count($avisos) > 0) {
    echo "⚠️  Avisos (" . count($avisos) . "):\n";
    foreach ($avisos as $aviso) {
        echo "   • $aviso\n";
    }
    echo "\n";
}

if (count($erros) > 0) {
    echo "❌ Erros (" . count($erros) . "):\n";
    foreach ($erros as $erro) {
        echo "   • $erro\n";
    }
    echo "\n";
}

// Resultado Final
echo "========================================\n";
if (count($erros) === 0) {
    echo "✅ Sistema pronto para rodar!\n\n";
    echo "Próximos passos:\n";
    echo "1. Inicie o servidor PHP: php -S localhost:8000\n";
    echo "2. Acesse no navegador: http://localhost:8000\n";
    echo "3. Faça login com as credenciais padrão\n";
    
    if (count($avisos) > 0) {
        echo "\n⚠️  Revise os avisos acima para melhorar a instalação.\n";
    }
} else {
    echo "❌ Corrija os erros acima antes de continuar.\n\n";
    echo "Dicas:\n";
    echo "- Verifique se o MySQL está rodando\n";
    echo "- Verifique as credenciais em config.php\n";
    echo "- Execute criar-banco.php para criar o banco de dados\n";
    echo "- Consulte INSTALACAO_LOCAL.md para mais detalhes\n";
}
echo "========================================\n";

