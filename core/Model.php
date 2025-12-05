<?php

namespace Core;

/**
 * Classe base Model
 * Todos os models devem estender esta classe
 */
abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $timestamps = true;
    
    /**
     * Retorna todos os registros
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Encontra um registro pelo ID
     */
    public function find(int $id): ?object
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $result = Database::getInstance()->query($sql, ['id' => $id])->fetch();
        return $result ?: null;
    }
    
    /**
     * Encontra registros por uma condição
     */
    public function where(string $column, $value, string $operator = '='): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        return Database::getInstance()->query($sql, ['value' => $value])->fetchAll();
    }
    
    /**
     * Encontra um único registro por uma condição
     */
    public function findWhere(string $column, $value, string $operator = '='): ?object
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value LIMIT 1";
        $result = Database::getInstance()->query($sql, ['value' => $value])->fetch();
        return $result ?: null;
    }
    
    /**
     * Cria um novo registro
     */
    public function create(array $data): int
    {
        // Filtra apenas os campos permitidos
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        // Adiciona timestamps
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        Database::getInstance()->query($sql, $data);
        
        return Database::getInstance()->lastInsertId();
    }
    
    /**
     * Atualiza um registro
     */
    public function update(int $id, array $data): bool
    {
        // Filtra apenas os campos permitidos
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        // Adiciona timestamp de atualização
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = :{$column}";
        }
        $setString = implode(', ', $sets);
        
        $data['id'] = $id;
        
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = :id";
        Database::getInstance()->query($sql, $data);
        
        return true;
    }
    
    /**
     * Deleta um registro
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        Database::getInstance()->query($sql, ['id' => $id]);
        return true;
    }
    
    /**
     * Conta o total de registros
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = Database::getInstance()->query($sql)->fetch();
        return (int) $result->total;
    }
    
    /**
     * Retorna registros paginados
     */
    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->count();
        $totalPages = ceil($total / $perPage);
        
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        $data = Database::getInstance()->query($sql, [
            'limit' => $perPage,
            'offset' => $offset
        ])->fetchAll();
        
        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
        ];
    }
    
    /**
     * Executa uma query customizada
     */
    public function raw(string $sql, array $params = []): array
    {
        return Database::getInstance()->query($sql, $params)->fetchAll();
    }
}

