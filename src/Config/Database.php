<?php

namespace Advocacia\Config;

class Database
{
    // Configurações padrão
    private static $config = [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'advocacia',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];

    /**
     * Obtém as configurações do banco de dados
     */
    public static function getConfig()
    {
        // Carrega configurações do arquivo config.php se existir
        if (file_exists(dirname(__DIR__, 2) . '/config.php')) {
            require_once dirname(__DIR__, 2) . '/config.php';
            
            // Atualiza com as configurações do arquivo
            if (isset($host)) self::$config['host'] = $host;
            if (isset($porta)) self::$config['port'] = $porta;
            if (isset($usuario)) self::$config['username'] = $usuario;
            if (isset($senha)) self::$config['password'] = $senha;
            if (isset($banco)) self::$config['database'] = $banco;
        }

        return self::$config;
    }

    /**
     * Obtém a string DSN para conexão PDO
     */
    public static function getDsn()
    {
        $config = self::getConfig();
        return "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    }

    /**
     * Obtém as opções PDO
     */
    public static function getOptions()
    {
        return self::getConfig()['options'];
    }

    /**
     * Obtém o usuário do banco
     */
    public static function getUsername()
    {
        return self::getConfig()['username'];
    }

    /**
     * Obtém a senha do banco
     */
    public static function getPassword()
    {
        return self::getConfig()['password'];
    }

    /**
     * Verifica se as configurações estão válidas
     */
    public static function validate()
    {
        $config = self::getConfig();
        
        $errors = [];
        
        if (empty($config['host'])) {
            $errors[] = 'Host do banco não configurado';
        }
        
        if (empty($config['database'])) {
            $errors[] = 'Nome do banco não configurado';
        }
        
        if (empty($config['username'])) {
            $errors[] = 'Usuário do banco não configurado';
        }
        
        return $errors;
    }

    /**
     * Testa a conexão com o banco
     */
    public static function testConnection()
    {
        try {
            $dsn = self::getDsn();
            $username = self::getUsername();
            $password = self::getPassword();
            $options = self::getOptions();
            
            $pdo = new \PDO($dsn, $username, $password, $options);
            
            return [
                'success' => true,
                'message' => 'Conexão realizada com sucesso!',
                'info' => [
                    'host' => self::getConfig()['host'],
                    'database' => self::getConfig()['database'],
                    'php_version' => PHP_VERSION,
                    'pdo_drivers' => \PDO::getAvailableDrivers()
                ]
            ];
            
        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro na conexão: ' . $e->getMessage(),
                'error_code' => $e->getCode()
            ];
        }
    }
}
