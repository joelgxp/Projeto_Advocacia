<?php

namespace Advocacia\Database;

use PDO;
use PDOException;
use Exception;

class Connection
{
    private static $instance = null;
    private $pdo;
    
    private function __construct()
    {
        $this->connect();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect()
    {
        try {
            // Usa a nova classe de configuração
            $config = \Advocacia\Config\Database::getConfig();
            
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            
        } catch (PDOException $e) {
            throw new Exception('Erro ao conectar com o banco de dados: ' . $e->getMessage());
        }
    }
    
    public function getPdo()
    {
        return $this->pdo;
    }
    
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception('Erro na execução da query: ' . $e->getMessage());
        }
    }
    
    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }
    
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    public function rollback()
    {
        return $this->pdo->rollback();
    }
}

