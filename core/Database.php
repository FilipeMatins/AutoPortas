<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Classe Database
 * Gerencia a conexão com o banco de dados
 */
class Database
{
    protected static $instance = null;
    protected $pdo;
    protected $stmt;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct()
    {
        $config = require CONFIG_PATH . '/database.php';
        
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            throw new \Exception("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }
    
    /**
     * Retorna a instância única do banco de dados
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Executa uma query
     */
    public function query(string $sql, array $params = []): self
    {
        $this->stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $type = $this->getParamType($value);
            $this->stmt->bindValue(
                is_numeric($key) ? $key + 1 : ":{$key}",
                $value,
                $type
            );
        }
        
        $this->stmt->execute();
        return $this;
    }
    
    /**
     * Retorna o tipo do parâmetro para o PDO
     */
    protected function getParamType($value): int
    {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
    }
    
    /**
     * Retorna todos os resultados
     */
    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }
    
    /**
     * Retorna um único resultado
     */
    public function fetch()
    {
        return $this->stmt->fetch();
    }
    
    /**
     * Retorna o último ID inserido
     */
    public function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
    
    /**
     * Retorna o número de linhas afetadas
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }
    
    /**
     * Inicia uma transação
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
    
    /**
     * Reverte uma transação
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * Retorna a instância do PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}

