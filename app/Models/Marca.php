<?php

namespace App\Models;

use Core\Model;
use Core\Database;

/**
 * Model de Marca
 */
class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nome',
        'descricao',
        'status',
    ];
    
    /**
     * Retorna marcas ativas
     */
    public function getAtivas(): array
    {
        return $this->where('status', 'ativo');
    }
    
    /**
     * Retorna marcas com contagem de peças
     */
    public function getAllWithPecasCount(): array
    {
        $sql = "SELECT m.*, COUNT(p.id) as total_pecas 
                FROM {$this->table} m 
                LEFT JOIN pecas p ON m.id = p.marca_id AND p.status = 'ativo'
                GROUP BY m.id 
                ORDER BY m.nome ASC";
        return Database::getInstance()->query($sql)->fetchAll();
    }
    
    /**
     * Busca marcas
     */
    public function search(string $term): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE nome LIKE :term ORDER BY nome ASC";
        return Database::getInstance()->query($sql, ['term' => "%{$term}%"])->fetchAll();
    }
}

