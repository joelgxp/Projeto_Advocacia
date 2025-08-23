<?php 
/**
 * Configuração Avançada do Banco de Dados
 * 
 * Este arquivo contém todas as configurações necessárias para conexão
 * com o banco de dados MySQL/MariaDB
 */

date_default_timezone_set("America/Sao_Paulo");
$email_site = "joelvieirasouza@gmail.com";

// ========================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// ========================================

// Configurações básicas
$host = 'localhost';           // Servidor MySQL (localhost, 127.0.0.1, IP)
$porta = 3306;                 // Porta do MySQL (3306 é padrão)
$usuario = 'root';             // Usuário do banco
$senha = '';                   // Senha do usuário (vazia para desenvolvimento)
$banco = 'advocacia';          // Nome do banco de dados

// Configurações avançadas de conexão
$charset = 'utf8mb4';          // Charset para suporte completo a Unicode
$collation = 'utf8mb4_unicode_ci'; // Collation para ordenação

// Configurações de timeout e performance
$timeout_conexao = 30;         // Timeout de conexão em segundos
$timeout_query = 60;           // Timeout de query em segundos

// Configurações de SSL (para produção)
$ssl_verify = false;           // Verificar certificado SSL
$ssl_ca = '';                  // Caminho para certificado CA
$ssl_cert = '';                // Caminho para certificado cliente
$ssl_key = '';                 // Caminho para chave privada

// ========================================
// CONFIGURAÇÕES DO SISTEMA
// ========================================

//VALORES PARA A COMBOBOX DE PAGINAÇÃO
$opcao1 = 10;
$opcao2 = 20;
$opcao3 = 50;

// Configurações de debug
$debug_mode = true;            // Modo debug (true para desenvolvimento)
$log_queries = false;          // Log de todas as queries (cuidado com performance)
$log_errors = true;            // Log de erros

// Configurações de sessão
$session_lifetime = 7200;      // Tempo de vida da sessão em segundos (2 horas)
$session_secure = false;       // Cookies seguros (true para HTTPS)

// ========================================
// FUNÇÕES DE VALIDAÇÃO
// ========================================

/**
 * Valida se as configurações do banco estão corretas
 */
function validarConfiguracaoBanco() {
    global $host, $porta, $usuario, $banco;
    
    $erros = [];
    
    if (empty($host)) {
        $erros[] = 'Host não configurado';
    }
    
    if (empty($porta) || !is_numeric($porta) || $porta < 1 || $porta > 65535) {
        $erros[] = 'Porta inválida (deve ser entre 1 e 65535)';
    }
    
    if (empty($usuario)) {
        $erros[] = 'Usuário não configurado';
    }
    
    if (empty($banco)) {
        $erros[] = 'Nome do banco não configurado';
    }
    
    return $erros;
}

/**
 * Testa a conexão com o banco
 */
function testarConexaoBanco() {
    global $host, $porta, $usuario, $senha, $banco, $charset;
    
    try {
        $dsn = "mysql:host={$host};port={$porta};dbname={$banco};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 30,
        ];
        
        $pdo = new PDO($dsn, $usuario, $senha, $options);
        
        return [
            'success' => true,
            'message' => 'Conexão realizada com sucesso!',
            'info' => [
                'host' => $host,
                'porta' => $porta,
                'banco' => $banco,
                'usuario' => $usuario,
                'charset' => $charset,
                'php_version' => PHP_VERSION,
                'pdo_drivers' => PDO::getAvailableDrivers()
            ]
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro na conexão: ' . $e->getMessage(),
            'error_code' => $e->getCode(),
            'suggestions' => getSugestoesErro($e->getCode())
        ];
    }
}

/**
 * Retorna sugestões baseadas no código de erro
 */
function getSugestoesErro($errorCode) {
    switch ($errorCode) {
        case 1045: // Access denied
            return [
                'Verifique se o usuário e senha estão corretos',
                'Confirme se o usuário tem permissão para acessar o banco',
                'Teste a conexão via phpMyAdmin ou MySQL Workbench'
            ];
        case 1049: // Unknown database
            return [
                'O banco de dados não existe',
                'Execute: CREATE DATABASE advocacia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
            ];
        case 2002: // Can\'t connect to server
            return [
                'Verifique se o MySQL está rodando',
                'Confirme se a porta 3306 está correta',
                'Teste se o host está acessível'
            ];
        default:
            return [
                'Verifique as configurações no arquivo config.php',
                'Confirme se o MySQL está funcionando',
                'Teste a conexão manualmente'
            ];
    }
}

// ========================================
// EXECUÇÃO AUTOMÁTICA DE VALIDAÇÃO
// ========================================

if ($debug_mode) {
    // Valida configurações
    $erros_config = validarConfiguracaoBanco();
    if (!empty($erros_config)) {
        error_log('Erros de configuração: ' . implode(', ', $erros_config));
    }
    
    // Testa conexão se não houver erros de configuração
    if (empty($erros_config)) {
        $teste_conexao = testarConexaoBanco();
        if (!$teste_conexao['success']) {
            error_log('Falha na conexão: ' . $teste_conexao['message']);
        }
    }
}
?>
